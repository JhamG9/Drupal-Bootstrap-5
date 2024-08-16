<?php

namespace Drupal\scss_compiler\Plugin\ScssCompiler;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Drupal\scss_compiler\ScssCompilerPluginBase;
use Drupal\Core\File\FileSystemInterface;

/**
 * Plugin implementation of the Libsass compiler.
 *
 * @ScssCompilerPlugin(
 *   id   = "scss_compiler_libsass",
 *   name = "Libsass (Experimental)",
 *   description = "",
 *   extensions = {
 *     "scss" = "scss",
 *     "sass" = "sass",
 *   }
 * )
 */
class LibsassCompiler extends ScssCompilerPluginBase {

  /**
   * Base cli command.
   *
   * @var string
   */
  const BASE_COMMAND = 'node';

  /**
   * Valid hash sum of the node script.
   */
  const SCRIPT_HASH = 'b0f0ed985ed9a065c7ceace66b7d44a9806e9df403ec8401480f224a442baca6347de40372458810ee9b29a21cffbc71519aeb1e64179c56c4b312808bca871f';

  /**
   * Node script file size.
   */
  const SCRIPT_SIZE = 1485;

  /**
   * Array with queued files.
   *
   * @var array
   */
  protected $queueList;

  /**
   * Flag indicates if queue already compiled.
   *
   * @var string
   */
  protected $queueRun;

  /**
   * Path to nodejs script.
   *
   * @var string
   */
  protected $scriptPath;

  /**
   * {@inheritdoc}
   */
  public function init() {
    $status = self::getStatus();
    if ($status !== TRUE) {
      throw new \Exception($status);
    }
    $module_path = DRUPAL_ROOT . '/' . \Drupal::service('extension.list.module')->getPath('scss_compiler');
    $this->scriptPath = $module_path . '/js/libsass.js';

    // Prevent the execution of the script if it contains changes.
    if (hash_file('sha512', $this->scriptPath) !== self::SCRIPT_HASH || filesize($this->scriptPath) !== self::SCRIPT_SIZE) {
      throw new \Exception($this->t('Compiler initialization failed. The execution script is modified.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getVersion() {
    // Parse package.json and find libsass version, because of run node-sass -v
    // take around 150ms.
    $node_modules_path = \Drupal::service('scss_compiler')->getOption('node_modules_path');
    if (file_exists($node_modules_path . '/node-sass/package.json')) {
      $package = file_get_contents($node_modules_path . '/node-sass/package.json');
      $match = [];
      preg_match('/"libsass":\s"([\d\.]+)"/', $package, $match);
      if (!empty($match[1])) {
        return $match[1];
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function getStatus() {
    if (!function_exists('proc_open')) {
      return t('@function disabled.', [
        '@function' => 'proc_open',
      ]);
    }
    // Checks if node-sass binary exists, run any cli commands will take to many
    // time.
    if (!file_exists(\Drupal::service('scss_compiler')->getOption('node_modules_path') . '/node-sass/bin/node-sass')) {
      return t('Node-sass library not found');
    }
    return TRUE;
  }

  /**
   * Compile all queued files.
   */
  public function compileQueue() {
    if ($this->queueRun || empty($this->queueList)) {
      return;
    }
    try {
      $this->queueRun = TRUE;
      $command = self::BASE_COMMAND . ' ' . $this->scriptPath;

      $cache_folder = $this->scssCompiler->getCacheFolder();
      $this->fileSystem->prepareDirectory($cache_folder, FileSystemInterface::CREATE_DIRECTORY);
      $files = $this->queueList;

      foreach ($files as &$file) {
        $css_dir = dirname($file['css_path']);
        $this->fileSystem->prepareDirectory($css_dir, FileSystemInterface::CREATE_DIRECTORY);
      }

      $import_paths = [
        DRUPAL_ROOT,
      ];
      if ($this->scssCompiler->getAdditionalImportPaths()) {
        $import_paths = array_merge($import_paths, $this->scssCompiler->getAdditionalImportPaths());
      }

      $data = [
        'config' => [
          'sourcemaps'    => $this->scssCompiler->getOption('sourcemaps'),
          'output_format' => $this->scssCompiler->getOption('output_format'),
          'import_paths'  => $import_paths,
        ],
        'files' => $files,
      ];

      $data = json_encode($data);
      file_put_contents($cache_folder . '/libsass_temp.json', $data);

      $process = new Process($command);
      $process->run(NULL, [
        'SCSS_COMPILER_NODE_MODULES_PATH' => $this->scssCompiler->getOption('node_modules_path'),
        'SCSS_COMPILER_DRUPAL_ROOT'       => DRUPAL_ROOT,
        'SCSS_COMPILER_CACHE_FOLDER'      => $this->fileSystem->realpath($cache_folder),
      ]);

      $this->fileSystem->delete($cache_folder . '/libsass_temp.json');

      if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
      }
    }
    catch (\Exception $e) {
      $this->messenger()->addError($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function compile(array $scss_file) {
    // Node-sass cli has awful performance, so we collect all scss files and run
    // them through custom script.
    $this->queueList[] = $scss_file;
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
        $extension = '.' . pathinfo($file_path, PATHINFO_EXTENSION);
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

}
