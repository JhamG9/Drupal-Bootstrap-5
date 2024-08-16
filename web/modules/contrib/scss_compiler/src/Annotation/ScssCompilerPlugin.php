<?php

namespace Drupal\scss_compiler\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a ScssCompiler plugin annotation object.
 *
 * Plugin Namespace: Plugin\ScssCompiler.
 *
 * @Annotation
 */
class ScssCompilerPlugin extends Plugin {

  /**
   * The compiler plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the compiler plugin.
   *
   * @var string
   */
  public $name;

  /**
   * The compiler plugin description.
   *
   * @var string
   */
  public $description;

  /**
   * An array with supported extensions by this compiler.
   *
   * @var array
   */
  public $extensions;

}
