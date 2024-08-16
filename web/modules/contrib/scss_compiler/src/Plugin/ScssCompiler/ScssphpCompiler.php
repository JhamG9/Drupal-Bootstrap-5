<?php

namespace Drupal\scss_compiler\Plugin\ScssCompiler;

use Drupal\scss_compiler\Plugin\ScssCompiler\Scssphp as Compiler;
use ScssPhp\ScssPhp\Version;
use Drupal\scss_compiler\ScssCompilerPluginBase;
use Drupal\Core\File\FileSystemInterface;

/**
 * Plugin implementation of the Scss compiler.
 *
 * @ScssCompilerPlugin(
 *   id   = "scss_compiler_scssphp",
 *   name = "ScssPhp Compiler",
 *   description = "Compiler for SCSS written in PHP",
 *   extensions = {
 *     "scss" = "scss",
 *   }
 * )
 */
class ScssphpCompiler extends ScssCompilerPluginBase {

  /**
   * Compiler object instance.
   *
   * @var \Drupal\scss_compiler\Plugin\ScssCompiler\Scssphp
   */
  protected $parser;

  /**
   * {@inheritdoc}
   */
  public function init() {

    $status = self::getStatus();
    if ($status !== TRUE) {
      throw new \Exception($status);
    }

    $this->parser = new Compiler();
    $this->parser->setFormatter($this->getScssPhpFormatClass($this->scssCompiler->getOption('output_format')));
    // Disable utf-8 support to increase performance.
    $this->parser->setEncoding(TRUE);

  }

  /**
   * {@inheritdoc}
   */
  public static function getVersion() {
    if (class_exists('ScssPhp\ScssPhp\Version')) {
      return Version::VERSION;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function getStatus() {
    $compiler_class_exists = class_exists('ScssPhp\ScssPhp\Compiler');
    if (!$compiler_class_exists && !file_exists(DRUPAL_ROOT . '/libraries/scssphp/scss.inc.php')) {
      return t('ScssPhp Compiler library not found. Install it via composer "composer require scssphp/scssphp"');
    }

    // If library didn't autoload from the vendor folder, load it from the
    // libraries folder.
    if (!$compiler_class_exists) {
      require_once DRUPAL_ROOT . '/libraries/scssphp/scss.inc.php';

      // leafo/scssphp no longer supported, it was forked to
      // scssphp/scssphp.
      // @see https://github.com/leafo/scssphp/issues/707
      if (!class_exists('ScssPhp\ScssPhp\Compiler')) {
        $error_message = t('leafo/scssphp no longer supported. Update compiler library to scssphp/scssphp @url', [
          '@url' => '(https://github.com/scssphp/scssphp/releases)',
        ]);
      }
    }
    if (!empty($error_message)) {
      return $error_message;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function compile(array $scss_file) {

    $import_paths = [
      dirname($scss_file['source_path']),
      DRUPAL_ROOT,
      [$this, 'getImportNamespace'],
    ];
    if ($this->scssCompiler->getAdditionalImportPaths()) {
      $import_paths = array_merge($import_paths, $this->scssCompiler->getAdditionalImportPaths());
    }

    $this->parser->setImportPaths($import_paths);

    // Alter variables.
    $variables = $this->scssCompiler->getVariables()->getAll($scss_file['namespace'], $scss_file['source_path']);
    $this->parser->setVariables($variables);

    // Add assets path to compiler. By default it's theme/module root folder.
    $this->parser->assetsPath = isset($scss_file['assets_path']) ? $scss_file['assets_path'] : '';

    $css_folder = dirname($scss_file['css_path']);
    if ($this->scssCompiler->getOption('sourcemaps')) {
      $this->parser->setSourceMap(Compiler::SOURCE_MAP_FILE);
      $sourcemap_file = $css_folder . '/' . $scss_file['name'] . '.css.map';
      $this->parser->setSourceMapOptions([
        'sourceMapWriteTo'  => $sourcemap_file,
        'sourceMapURL'      => $scss_file['name'] . '.css.map',
        'sourceMapBasepath' => DRUPAL_ROOT,
        'sourceMapRootpath' => '/',
      ]);
    }
    $this->fileSystem->prepareDirectory($css_folder, FileSystemInterface::CREATE_DIRECTORY);
    $source_content = file_get_contents($scss_file['source_path']);

    return $this->parser->compile($source_content, $scss_file['source_path']);

  }

  /**
   * {@inheritdoc}
   */
  public function checkLastModifyTime(array &$source_file) {
    $last_modify_time = filemtime($source_file['source_path']);
    $source_folder = dirname($source_file['source_path']);
    $import = [];
    $content = file_get_contents($source_file['source_path']);
    preg_match_all('/@import(.*);/', $content, $import);
    if (!empty($import[1])) {
      foreach ($import[1] as $file) {
        // Normalize @import path.
        $file_path = trim($file, '\'" ');
        $pathinfo = pathinfo($file_path);
        $extension = '.scss';
        $filename = $pathinfo['filename'];
        $dirname = $pathinfo['dirname'] === '.' ? '' : $pathinfo['dirname'] . '/';

        $file_path = $source_folder . '/' . $dirname . $filename . $extension;
        $scss_path = $source_folder . '/' . $dirname . '_' . $filename . $extension;

        if (file_exists($file_path) || file_exists($file_path = $scss_path)) {
          $file_modify_time = filemtime($file_path);
          if ($file_modify_time > $last_modify_time) {
            $last_modify_time = $file_modify_time;
          }
        }
      }
    }
    return $last_modify_time;
  }

  /**
   * Processes the import paths using prefixed module/theme.
   *
   * @param string $path
   *   The import path to process.
   *
   * @return string|null
   *   Path to file or NULL if path not found.
   */
  public function getImportNamespace($path) {
    if (!preg_match('#([^/]+)/#', $path, $match)) {
      return NULL;
    }

    $namespace = $match[1];
    // Prevent name conflicts when module/theme name same as subfolder name,
    // use @module to import from module.
    if (substr($namespace, 0, 1) === '@') {
      $namespace = substr($namespace, 1);
    }
    $namespace_path = substr($path, strlen($match[0]));

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
      return NULL;
    }

    // Try different extensions to allow for import from the usual scss sources.
    $base_path = DRUPAL_ROOT . '/' . $path . '/' . $namespace_path;
    foreach (['', '.scss', '.css'] as $extension) {
      if ($path = realpath($base_path . $extension)) {
        return $path;
      }
    }
    return NULL;
  }

  /**
   * Returns ScssPhp Compiler format classname.
   *
   * @param string $format
   *   Format name.
   *
   * @return string
   *   Format type classname.
   */
  private function getScssPhpFormatClass($format) {
    switch ($format) {
      case 'expanded':
        return '\ScssPhp\ScssPhp\Formatter\Expanded';

      case 'nested':
        return '\ScssPhp\ScssPhp\Formatter\Nested';

      case 'compact':
        return '\ScssPhp\ScssPhp\Formatter\Compact';

      case 'crunched':
        return '\ScssPhp\ScssPhp\Formatter\Crunched';

      default:
        return '\ScssPhp\ScssPhp\Formatter\Compressed';
    }
  }

}
