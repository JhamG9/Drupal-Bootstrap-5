<?php

namespace Drupal\scss_compiler;

/**
 * Provides an interface defining a SCSS Compiler service.
 */
interface ScssCompilerInterface {

  /**
   * Compiles single scss file into css.
   *
   * @param array $scss_file
   *   An associative array with scss file info.
   *   - name: filename. Required.
   *   - namespace: theme/module name. Required.
   *   - source_path: source file path. Required.
   *   - css_path: css file destination path. Required.
   * @param bool $flush
   *   If TRUE ignore last modified time.
   */
  public function compile(array $scss_file, $flush);

  /**
   * Compiles all scss files which was registered.
   *
   * @param bool $all
   *   If TRUE compiles all scss files from all themes in system,
   *   else compiles only scss files from active theme.
   * @param bool $flush
   *   If TRUE ignore last modified time.
   */
  public function compileAll($all, $flush);

  /**
   * Returns list of scss files which need to be recompiled.
   *
   * @param bool $all
   *   If TRUE loads all scss files from all themes in system,
   *   else loads only scss files from active theme.
   *
   * @return array
   *   An associative array with scss files info.
   */
  public function getCompileList($all);

  /**
   * Saves list of scss files which need to be recompiled.
   *
   * @param array $files
   *   List of scss files.
   */
  public function setCompileList(array $files);

  /**
   * Gets a specific option.
   *
   * @param string $option
   *   The name of the option.
   *
   * @return mixed
   *   The value for a specific option,
   *   or NULL if it does not exist.
   */
  public function getOption($option);

  /**
   * Returns info about cache.
   *
   * @return bool
   *   TRUE if cache enabled else FALSE.
   */
  public function isCacheEnabled();

  /**
   * Returns path to cache folder where compiled files save.
   *
   * @return string
   *   Internal drupal path to cache folder.
   */
  public function getCacheFolder();

  /**
   * Returns compile file info.
   *
   * @param array $info
   *   An associative array containing:
   *   - namespace: theme/module name. Required.
   *   - data: source file path. Required.
   *   - css_path: custom destination css path. Optional.
   *
   * @return array|null
   *   Compile file info:
   *   - name: filename.
   *   - namespace: theme/module name.
   *   - source_path: source file path.
   *   - css_path: css file destination path.
   *
   *   or NULL if source data is incorrect.
   */
  public function buildCompilationFileInfo(array $info);

  /**
   * Returns additional import paths.
   *
   * @see hook_scss_compiler_import_paths_alter()
   *
   * @return array
   *   An array with additional paths.
   */
  public function getAdditionalImportPaths();

  /**
   * Returns altering variables.
   *
   * @see hook_scss_compiler_variables_alter()
   *
   * @return \Drupal\scss_compiler\ScssCompilerAlterStorage
   *   A storage with altering variables.
   */
  public function getVariables();

  /**
   * Replace path tokens into real path.
   *
   * @param string $path
   *   String for replacement.
   */
  public function replaceTokens($path);

  /**
   * Flushes all compiler caches and reset css aggregation.
   */
  public function flushCache();

}
