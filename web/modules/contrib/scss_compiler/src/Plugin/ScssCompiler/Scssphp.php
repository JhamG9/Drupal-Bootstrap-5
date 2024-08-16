<?php

namespace Drupal\scss_compiler\Plugin\ScssCompiler;

use ScssPhp\ScssPhp\Compiler as ScssPhpCompiler;
use ScssPhp\ScssPhp\Type;
use ScssPhp\ScssPhp\Compiler\Environment;

/**
 * Extends ScssPhp Compiler.
 *
 * Adds path variable to handle path to static resources relative to
 * theme/module.
 */
class Scssphp extends ScssPhpCompiler {

  /**
   * Path to theme/module.
   *
   * @var string
   */
  public $assetsPath = '';

  /**
   * {@inheritdoc}
   */
  public function compileValue($value, $quote = TRUE) {
    $original_value = $value;

    if ($value[0] === Type::T_FUNCTION) {
      $value = $this->reduce($value);
      $args = !empty($value[2]) ? $this->compileValue($value[2], $quote) : '';
      if ($value[1] == 'url' && $args) {
        $args = trim($args, '"\'');
        if (substr($args, 0, 5) === 'data:') {
          return "$value[1](\"$args\")";
        }
        elseif (substr($args, 0, 1) === '@') {
          $path = \Drupal::service('scss_compiler')->replaceTokens($args);
          return "$value[1](\"/$path\")";
        }
        else {
          return "$value[1](\"$this->assetsPath$args\")";
        }
      }
      else {
        return "$value[1]($args)";
      }
    }

    return parent::compileValue($original_value, $quote);

  }

  /**
   * Allows override variables without !default flag.
   */
  protected function set($name, $value, $shadow = FALSE, Environment $env = NULL, $valueUnreduced = NULL) {

    if (isset($this->registeredVars[$name])) {
      $store = $this->getStoreEnv()->store;
      if (isset($store[$name])) {
        $value = $store[$name];
      }
    }

    parent::set($name, $value, $shadow, $env, $valueUnreduced);
  }

}
