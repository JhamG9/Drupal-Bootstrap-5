<?php

namespace Drupal\scss_compiler;

/**
 * Provides an interface defining a SCSS Compiler plugins.
 */
interface ScssCompilerPluginInterface {

  /**
   * Compiles single source file.
   *
   * @param array $source_file
   *   An associative array with file info.
   *   - name: filename. Required.
   *   - namespace: theme/module name. Required.
   *   - source_path: source file path. Required.
   *   - css_path: css file destination path. Required.
   */
  public function compile(array $source_file);

  /**
   * Checks if file was changed.
   *
   * @param array $source_file
   *   Compilation file info.
   *
   * @return string
   *   Last modify timestamp.
   */
  public function checkLastModifyTime(array &$source_file);

  /**
   * Returns status of compiler library.
   *
   * @return string|bool
   *   TRUE if library installed or string with error message.
   */
  public static function getStatus();

  /**
   * Returns compiler version.
   *
   * @return string|bool
   *   Compiler version or FALSE if version not defined.
   */
  public static function getVersion();

}
