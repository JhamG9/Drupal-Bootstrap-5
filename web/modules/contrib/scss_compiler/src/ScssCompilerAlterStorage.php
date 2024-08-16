<?php

namespace Drupal\scss_compiler;

/**
 * Collects alter data.
 *
 * Invoke alters on each file which run thru compiler is not effective, so this
 * storage collects all data and allow performing altering based on
 * module/theme name or file path.
 */
class ScssCompilerAlterStorage {

  /**
   * Array with collected data.
   *
   * @var array
   */
  protected $storage = [];

  /**
   * The ScssCompiler service.
   *
   * @var \Drupal\scss_compiler\ScssCompilerInterface
   */
  protected $scssCompiler;

  /**
   * Constructs ScssCompilerAlterStorage object.
   *
   * @param \Drupal\scss_compiler\ScssCompilerInterface $scss_compiler
   *   The ScssCompiler service.
   */
  public function __construct(ScssCompilerInterface $scss_compiler) {
    $this->scssCompiler = $scss_compiler;
  }

  /**
   * Set data by module/theme name.
   *
   * @param array $values
   *   Array with data.
   * @param string $namespace
   *   Module/theme name. By default global scope.
   */
  public function set(array $values, $namespace = '_global') {
    if (!isset($this->storage['namespace'][$namespace])) {
      $this->storage['namespace'][$namespace] = [];
    }
    $this->storage['namespace'][$namespace] = array_merge($this->storage['namespace'][$namespace], $values);
  }

  /**
   * Set data by file name.
   *
   * @param array $values
   *   Array with data.
   * @param string $file_path
   *   Path to a source file from DRUPAL_ROOT. Supports tokens like @my_module.
   */
  public function setByFile(array $values, $file_path) {
    $file_path = $this->scssCompiler->replaceTokens($file_path);
    if ($file_path) {
      if (!isset($this->storage['file'][$file_path])) {
        $this->storage['file'][$file_path] = [];
      }
      $this->storage['file'][$file_path] = array_merge($this->storage['file'][$file_path], $values);
    }
  }

  /**
   * Get data by module/theme name.
   *
   * @param string $namespace
   *   Module/theme name. By default global scope.
   *
   * @return array
   *   Array with data.
   */
  public function get($namespace = '_global') {
    if (!isset($this->storage['namespace'][$namespace])) {
      return [];
    }
    return $this->storage['namespace'][$namespace];
  }

  /**
   * Get data by file path.
   *
   * @param string $file_path
   *   Path to source file from DRUPAL_ROOT.
   *
   * @return array
   *   Array with data.
   */
  public function getByFile($file_path) {
    if (!isset($this->storage['file'][$file_path])) {
      return [];
    }
    return $this->storage['file'][$file_path];
  }

  /**
   * Returns merged data in all scopes.
   *
   * @param string $namespace
   *   Module/theme name.
   * @param string $file_path
   *   Path to source file from DRUPAL_ROOT.
   *
   * @return array
   *   Array with data.
   */
  public function getAll($namespace, $file_path) {
    return array_merge($this->get(), $this->get($namespace), $this->getByFile($file_path));
  }

  /**
   * Removes variable from the storage.
   *
   * @param string $type
   *   Storage type, file or namespace.
   * @param string $path
   *   Depends on the type. Module/theme name or file path.
   * @param string $key
   *   Key to delete. If omitted, all keys from given path will be deleted.
   */
  public function unset($type, $path, $key = NULL) {
    if (isset($this->storage[$type][$path])) {
      if ($key) {
        if (isset($this->storage[$type][$path][$key])) {
          unset($this->storage[$type][$path][$key]);
        }
      }
      else {
        unset($this->storage[$type][$path]);
      }
    }
  }

  /**
   * Returns entire storage.
   *
   * @return array
   *   Array with data.
   */
  public function getStorage() {
    return $this->storage;
  }

}
