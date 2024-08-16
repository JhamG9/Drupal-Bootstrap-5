<?php

namespace Drupal\scss_compiler;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides an Scss Compiler plugin manager.
 *
 * @see \Drupal\scss_compiler\Annotation\ScssCompilerPlugin
 * @see \Drupal\scss_compiler\ScssCompilerPluginInterface
 * @see plugin_api
 */
class ScssCompilerPluginManager extends DefaultPluginManager {

  /**
   * List of active compiler instances.
   *
   * @var array
   */
  protected $compilers;

  /**
   * Constructs a ScssCompilerPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/ScssCompiler',
      $namespaces,
      $module_handler,
      '\Drupal\scss_compiler\ScssCompilerPluginInterface',
      '\Drupal\scss_compiler\Annotation\ScssCompilerPlugin'
    );
    $this->alterInfo('scss_compiler_info');
    $this->setCacheBackend($cache_backend, 'scss_compiler_info_plugins');
  }

  /**
   * Returns compiler instance by id.
   *
   * @return \Drupal\scss_compiler\ScssCompilerPluginInterface
   *   Compiler instance.
   */
  public function getInstanceById($id) {
    if (isset($this->compilers[$id])) {
      return ($this->compilers[$id]);
    }

    try {
      $definitions = $this->getDefinitions();
      $this->compilers[$id] = FALSE;
      if (isset($definitions[$id])) {
        $this->compilers[$id] = $this->createInstance($id);
      }

      return $this->compilers[$id];
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

  }

}
