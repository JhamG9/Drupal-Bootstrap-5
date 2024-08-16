<?php

namespace Drupal\scss_compiler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\StreamWrapper\LocalStream;

/**
 * Defines a class for scss compiler service.
 */
class ScssCompilerService implements ScssCompilerInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  const CACHE_FOLDER = 'public://scss_compiler';

  /**
   * Configuration object of scss compiler.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The default cache bin.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Current theme name.
   *
   * @var string
   */
  protected $activeThemeName;

  /**
   * Path to cache folder.
   *
   * @var string
   */
  protected $cacheFolder;

  /**
   * Flag if cache enabled.
   *
   * @var bool
   */
  protected $isCacheEnabled;

  /**
   * List of last modified source files.
   *
   * @var array
   */
  protected $lastModifyList;

  /**
   * Flag indicates if need to update last modify list cache.
   *
   * @var bool
   */
  protected $fileIsModified;

  /**
   * Additional import paths for compiler.
   *
   * @var array
   */
  protected $additionalImportPaths;

  /**
   * Altering variables.
   *
   * @var \Drupal\scss_compiler\ScssCompilerAlterStorage
   */
  protected $variables;

  /**
   * List of replacement tokens.
   *
   * @var array
   */
  protected $tokens;

  /**
   * Constructs a SCSS Compiler service object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   The configuration object factory.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme manager.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The default cache bin.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(ConfigFactoryInterface $config, ThemeManagerInterface $theme_manager, ModuleHandlerInterface $module_handler, RequestStack $request_stack, CacheBackendInterface $cache, FileSystemInterface $file_system) {
    $this->config = $config->get('scss_compiler.settings');
    $this->themeManager = $theme_manager;
    $this->moduleHandler = $module_handler;
    $this->request = $request_stack->getCurrentRequest();
    $this->cache = $cache;
    $this->fileSystem = $file_system;

    $this->activeThemeName = $theme_manager->getActiveTheme()->getName();
    $this->cacheFolder = self::CACHE_FOLDER;
    $this->isCacheEnabled = $this->config->get('cache');
    $this->tokens = [
      '@drupal_root' => '',
    ];

    if (!$this->isCacheEnabled() && $this->config->get('check_modify_time')) {
      $this->lastModifyList = [];
      if ($cache = $this->cache->get('scss_compiler_modify_list')) {
        $this->lastModifyList = $cache->data;
      }
    }

    // Since Drupal 9.1.4 calling a cache->set method in the class destructor
    // causes an error.
    if (!$this->isCacheEnabled()) {
      register_shutdown_function([$this, 'destroy']);
    }
  }

  /**
   * Saves last modify time of files to the cache.
   */
  public function destroy() {
    if ($this->config->get('check_modify_time') && $this->fileIsModified) {
      $this->cache->set('scss_compiler_modify_list', $this->lastModifyList, CacheBackendInterface::CACHE_PERMANENT);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isCacheEnabled() {
    return $this->isCacheEnabled;
  }

  /**
   * {@inheritdoc}
   */
  public function getOption($option) {
    if (!is_string($option)) {
      return NULL;
    }
    return $this->config->get($option);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheFolder() {
    return $this->cacheFolder;
  }

  /**
   * {@inheritdoc}
   */
  public function setCompileList(array $files) {
    // Save list of scss files which need to be recompiled to the cache.
    // Each theme has own list of files, to prevent recompile files
    // which not loaded in active theme.
    $data = [];
    if ($cache = $this->cache->get('scss_compiler_list')) {
      $data = $cache->data;
      if (!empty($data[$this->activeThemeName])) {
        $old_files = $data[$this->activeThemeName];
        if (is_array($old_files)) {
          $files = array_replace_recursive($old_files, $files);
        }
      }
    }
    $data[$this->activeThemeName] = $files;
    $this->cache->set('scss_compiler_list', $data, CacheBackendInterface::CACHE_PERMANENT);
  }

  /**
   * {@inheritdoc}
   */
  public function getCompileList($all = FALSE) {
    $files = [];
    if ($cache = $this->cache->get('scss_compiler_list')) {
      $data = $cache->data;
      if ($all) {
        foreach ($data as $namespace) {
          foreach ($namespace as $key => $file) {
            if (!isset($files[$key])) {
              $files[$key] = [];
            }
            $files[$key] = array_merge($files[$key], $file);
          }
        }
      }
      elseif (!empty($data[$this->activeThemeName])) {
        $files = $data[$this->activeThemeName];
      }
    }

    return $files;
  }

  /**
   * {@inheritdoc}
   */
  public function compileAll($all = FALSE, $flush = FALSE) {
    $scss_files = $this->getCompileList($all);
    if (!empty($scss_files)) {
      foreach ($scss_files as $namespace) {
        foreach ($namespace as $scss_file) {
          $this->compile($scss_file, $flush);
        }
      }
      $this->compileComplete();
    }
  }

  /**
   * Indicates that all files was compiled.
   *
   * Run compileQueue function if plugin supports it.
   */
  public function compileComplete() {
    $plugins = $this->config->get('plugins');
    foreach ($plugins as $plugin) {
      $compiler = \Drupal::service('plugin.manager.scss_compiler')->getInstanceById($plugin);
      if (method_exists($compiler, 'compileQueue')) {
        $compiler->compileQueue();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildCompilationFileInfo(array $info) {
    try {
      if (empty($info['data']) || empty($info['namespace'])) {
        $error_message = $this->t('Compilation file info build is failed. Required parameters are missing.');
        throw new \Exception($error_message);
      }

      $namespace_path = $this->getNamespacePath($info['namespace']);

      $assets_path = '';
      if (isset($info['assets_path'])) {
        if (substr($info['assets_path'], 0, 1) === '@') {
          $assets_path = '/' . trim($this->replaceTokens($info['assets_path']), '/. ') . '/';
        }
      }
      elseif (!empty($namespace_path)) {
        $assets_path = '/' . trim($namespace_path, '/') . '/';
      }

      $name = pathinfo($info['data'], PATHINFO_FILENAME);
      if (!empty($info['css_path'])) {
        if (substr($info['css_path'], 0, 1) === '@') {
          $css_path = trim($this->replaceTokens($info['css_path']), '/. ') . '/' . $name . '.css';
        }
        elseif (!empty($namespace_path)) {
          $css_path = $namespace_path . '/' . trim($info['css_path'], '/. ') . '/' . $name . '.css';
        }
      }

      if (!isset($css_path)) {
        // Get source file path relative to theme/module and add it to css path
        // to prevent overwriting files when two source files with the same name
        // defined in different folders.
        $source_folder = dirname($info['data']);
        if (substr($source_folder, 0, strlen($namespace_path)) === $namespace_path) {
          $internal_folder = substr($source_folder, strlen($namespace_path));
          $css_path = $this->getCacheFolder() . '/' . $info['namespace'] . '/' . trim($internal_folder, '/ ') . '/' . $name . '.css';
        }
        else {
          $css_path = $this->getCacheFolder() . '/' . $info['namespace'] . '/' . $name . '.css';
        }
      }

      return [
        'name'        => $name,
        'namespace'   => $info['namespace'],
        'assets_path' => $assets_path,
        'source_path' => $info['data'],
        'css_path'    => $css_path,
      ];
    }
    catch (\Exception $e) {
      $this->messenger()->addError($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function replaceTokens($path) {
    // If string starts with @ replace it with the proper path.
    if( !is_string($path) ) {
      return $path;
    }

    if (substr($path, 0, 1) === '@') {
      $namespace = [];
      if (preg_match('#([^/]+)/#', $path, $namespace)) {
        $namespace_path = $this->getNamespacePath(substr($namespace[1], 1));
        if (!$namespace_path) {
          return FALSE;
        }
        $path = str_replace($namespace[1], $namespace_path, $path);
      }
      else {
        if (!$namespace_path = $this->getNamespacePath(substr($path, 1))) {
          return FALSE;
        }
        $path = $namespace_path;
      }
    }

    return $path;
  }

  /**
   * Returns namespace path.
   *
   * @param string $namespace
   *   Namespace name.
   *
   * @throws Exception
   *   If namespace is invalid.
   *
   * @return string
   *   Path to theme/module of given namespace.
   */
  protected function getNamespacePath($namespace) {
    if (isset($this->tokens[$namespace])) {
      return $this->tokens[$namespace];
    }
    $type = 'theme';
    if ($this->moduleHandler->moduleExists($namespace)) {
      $type = 'module';
    }
    switch ($type) {
      case 'theme':
        $path = \Drupal::service('extension.list.theme')->getPath($namespace);
        break;

      case 'module':
        $path = \Drupal::service('extension.list.module')->getPath($namespace);
        break;
    }

    if (empty($path)) {
      $path = '';
    }
    $this->tokens[$namespace] = $path;
    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalImportPaths() {
    if (isset($this->additionalImportPaths)) {
      return $this->additionalImportPaths;
    }

    $this->additionalImportPaths = [];
    $this->moduleHandler->alter('scss_compiler_import_paths', $this->additionalImportPaths);
    $activeTheme = $this->themeManager->getActiveTheme();
    $this->themeManager->alterForTheme($activeTheme, 'scss_compiler_import_paths', $this->additionalImportPaths);
    if (!is_array($this->additionalImportPaths)) {
      $this->additionalImportPaths = [];
    }
    return $this->additionalImportPaths;
  }

  /**
   * {@inheritdoc}
   */
  public function getVariables() {
    if (isset($this->variables)) {
      return $this->variables;
    }
    $this->variables = new ScssCompilerAlterStorage($this);
    $this->moduleHandler->alter('scss_compiler_variables', $this->variables);
    $activeTheme = $this->themeManager->getActiveTheme();
    $this->themeManager->alterForTheme($activeTheme, 'scss_compiler_variables', $this->variables);
    return $this->variables;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(array $source_file, $flush = FALSE) {
    try {
      if (!file_exists($source_file['source_path'])) {
        $error_message = $this->t('File @path not found', [
          '@path' => $source_file['source_path'],
        ]);
        throw new \Exception($error_message);
      }

      $extension = pathinfo($source_file['source_path'], PATHINFO_EXTENSION);
      $plugins = $this->config->get('plugins');
      if (!empty($plugins[$extension])) {
        $compiler = \Drupal::service('plugin.manager.scss_compiler')->getInstanceById($plugins[$extension]);
      }

      if (empty($compiler)) {
        $error_message = $this->t('Compiler for @extension extension not found', [
          '@extension' => $extension,
        ]);
        throw new \Exception($error_message);
      }

      // Replace all local stream wrappers by real path.
      foreach ([&$source_file['source_path'], &$source_file['css_path']] as &$path) {
        if (\Drupal::service('stream_wrapper_manager')->getScheme($path)) {
          $wrapper = \Drupal::service('stream_wrapper_manager')->getViaUri($path);
          if ($wrapper instanceof LocalStream) {
            $host = $this->request->getSchemeAndHttpHost();
            $wrapper_path = $wrapper->getExternalUrl();
            $path = trim(str_replace($host, '', $wrapper_path), '/');
          }
        }
      }

      $source_content = file_get_contents($source_file['source_path']);
      if ($this->config->get('check_modify_time') && !$flush && !$this->checkLastModifyTime($source_file, $source_content)) {
        return;
      }

      $content = $compiler->compile($source_file);
      if (!empty($content)) {
        $css_folder = dirname($source_file['css_path']);
        $this->fileSystem->prepareDirectory($css_folder, FileSystemInterface::CREATE_DIRECTORY);
        file_put_contents($source_file['css_path'], trim($content));
      }

    }
    catch (\Exception $e) {
      // If error occurrence during compilation, reset last modified time of the
      // file.
      if (!empty($this->lastModifyList[$source_file['source_path']])) {
        $this->lastModifyList[$source_file['source_path']] = 0;
      }
      $this->messenger()->addError($e->getMessage());
    }
  }

  /**
   * Checks if file was changed.
   *
   * @param array $source_file
   *   Compilation file info.
   * @param string $content
   *   Content of source file.
   *
   * @return bool
   *   TRUE if file was changed else FALSE.
   */
  protected function checkLastModifyTime(array &$source_file, &$content) {
    // If file wasn't changed and compiled css file exists don't recompile it
    // to increase performance. Each plugin has own realization.
    if (empty($this->lastModifyList[$source_file['source_path']]) || !file_exists($source_file['css_path'])) {
      $last_modify_time = filemtime($source_file['source_path']);
      $this->lastModifyList[$source_file['source_path']] = $last_modify_time;
      $this->fileIsModified = TRUE;
      return TRUE;
    }

    $extension = pathinfo($source_file['source_path'], PATHINFO_EXTENSION);
    $plugins = $this->config->get('plugins');
    if (!empty($plugins[$extension])) {
      $compiler = \Drupal::service('plugin.manager.scss_compiler')->getInstanceById($plugins[$extension]);
      $last_modify_time = $compiler->checkLastModifyTime($source_file);

      if ($last_modify_time > $this->lastModifyList[$source_file['source_path']]) {
        $this->lastModifyList[$source_file['source_path']] = $last_modify_time;
        $this->fileIsModified = TRUE;
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function flushCache() {
    $this->messenger()->addStatus($this->t('Compiler cache cleared.'));

    $cache_folder = $this->getCacheFolder();
    if ($this->fileSystem->prepareDirectory($cache_folder)) {
      $this->fileSystem->deleteRecursive($cache_folder);
    }

    $this->compileAll(TRUE, TRUE);
    // Reset data cache to rebuild aggregated css files.
    \Drupal::service('cache.data')->deleteAll();
    \Drupal::service('asset.css.collection_optimizer')->deleteAll();
  }

}
