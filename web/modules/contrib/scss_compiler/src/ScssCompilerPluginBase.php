<?php

namespace Drupal\scss_compiler;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Component\Plugin\PluginBase;

/**
 * Defines base abstract class for ScssCompiler plugins.
 */
abstract class ScssCompilerPluginBase extends PluginBase implements ScssCompilerPluginInterface, ContainerFactoryPluginInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * The scss compiler service.
   *
   * @var \Drupal\scss_compiler\ScssCompilerInterface
   */
  protected $scssCompiler;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a SCSS Compiler base plugin.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\scss_compiler\ScssCompilerInterface $scss_compiler
   *   The scss compiler service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ScssCompilerInterface $scss_compiler, RequestStack $request_stack, FileSystemInterface $file_system, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->scssCompiler = $scss_compiler;
    $this->request = $request_stack->getCurrentRequest();
    $this->fileSystem = $file_system;
    $this->moduleHandler = $module_handler;

    $this->init();
  }

  /**
   * Calls a code on plugin initialization.
   */
  public function init() {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('scss_compiler'),
      $container->get('request_stack'),
      $container->get('file_system'),
      $container->get('module_handler')
    );
  }

}
