<?php

namespace Drupal\scss_compiler\Plugin\ScssCompiler;

use Drupal\scss_compiler\ScssCompilerPluginBase;
use Drupal\Core\File\FileSystemInterface;

/**
 * Plugin implementation of the Less compiler.
 *
 * @ScssCompilerPlugin(
 *   id   = "scss_compiler_lessphp",
 *   name = "LessPhp Compiler",
 *   description = "PHP port of the official LESS processor",
 *   extensions = {
 *     "less" = "less",
 *   }
 * )
 */
class LessphpCompiler extends ScssCompilerPluginBase {

  /**
   * Compiler object instance.
   *
   * @var \Less_Parser
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
    $this->parser = new \Less_Parser();
    $format = $this->scssCompiler->getOption('output_format');
    switch ($format) {
      case 'compressed':
      case 'crunched':
        $this->parser->setOption('compress', TRUE);
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getVersion() {
    if (class_exists('Less_Version')) {
      return \Less_Version::version;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function getStatus() {
    $compiler_class_exists = class_exists('Less_Parser');
    if (!$compiler_class_exists && !file_exists(DRUPAL_ROOT . '/libraries/less.php/lessc.inc.php')) {
      return t('LessPhp Compiler library not found. Install it via composer "composer require wikimedia/less.php"');
    }
    // If library didn't autoload from the vendor folder, load it from the
    // libraries folder. Added to manage the library without composer.
    // @see https://www.drupal.org/project/scss_compiler/issues/3213427
    if (!$compiler_class_exists) {
      require_once DRUPAL_ROOT . '/libraries/less.php/lessc.inc.php';
      if (version_compare(PHP_VERSION, '7.2.9', '<')) {
        $error_message = t('LessPhp requires at least php 7.2.9');
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
    ];
    if ($this->scssCompiler->getAdditionalImportPaths()) {
      $import_paths = array_merge($import_paths, $this->scssCompiler->getAdditionalImportPaths());
    }
    $this->parser->setImportDirs($import_paths);
    $this->parser->setOption('import_callback', [$this, 'importCallback']);

    $css_folder = dirname($scss_file['css_path']);
    if ($this->scssCompiler->getOption('sourcemaps')) {
      $sourcemap_file = $css_folder . '/' . $scss_file['name'] . '.css.map';
      $this->parser->setOptions([
        'sourceMap'         => TRUE,
        'sourceMapWriteTo'  => $sourcemap_file,
        'sourceMapURL'      => $scss_file['name'] . '.css.map',
        'sourceMapBasepath' => DRUPAL_ROOT,
        'sourceMapRootpath' => '/',
      ]);
    }

    $this->fileSystem->prepareDirectory($css_folder, FileSystemInterface::CREATE_DIRECTORY);
    $this->parser->parseFile($scss_file['source_path'], $scss_file['assets_path']);

    // Alter variables.
    $variables = $this->scssCompiler->getVariables()->getAll($scss_file['namespace'], $scss_file['source_path']);
    $this->parser->ModifyVars($variables);

    $content = $this->parser->getCss();

    return $content;
  }

  /**
   * {@inheritdoc}
   */
  public function checkLastModifyTime(array &$source_file) {
    $last_modify_time = filemtime($source_file['source_path']);
    $source_folder = trim(dirname($source_file['source_path']), '/') . '/';
    $import = [];
    $content = file_get_contents($source_file['source_path']);
    preg_match_all('/@import.*["|\'](.*)["|\'].*;/', $content, $import);
    if (!empty($import[1])) {
      foreach ($import[1] as $file) {
        // Normalize @import path.
        $file_path = trim($file, '\'" ');
        $replaced_path = $this->scssCompiler->replaceTokens($file_path);
        if ($file_path !== $replaced_path) {
          $file_path = $replaced_path;
          $source_folder = '';
        }
        $pathinfo = pathinfo($file_path);
        if (empty($pathinfo['dirname'])) {
          continue;
        }
        $extension = '.less';
        $filename = $pathinfo['filename'];
        $dirname = $pathinfo['dirname'] === '.' ? '' : $pathinfo['dirname'] . '/';

        $file_path = $source_folder . $dirname . $filename . $extension;
        $less_path = $source_folder . $dirname . '_' . $filename . $extension;

        if (file_exists($file_path) || file_exists($file_path = $less_path)) {
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
   * Replaces tokens in import paths.
   *
   * @param \Less_Tree_Import $import
   *   Compiler import object.
   */
  public function importCallback(\Less_Tree_Import $import) {
    if (!empty($import->path->value)) {
      $path = $this->scssCompiler->replaceTokens($import->path->value);
      if ($path) {
        $import->path->value = $path;
      }
    }
  }

}
