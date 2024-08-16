<?php

namespace Drupal\scss_compiler\Commands;

use Drush\Commands\DrushCommands;
use Drupal\scss_compiler\ScssCompilerInterface;

/**
 * ScssCompiler drush commands.
 */
class ScssCompilerCommands extends DrushCommands {

  /**
   * Scss compiler service.
   *
   * @var \Drupal\scss_compiler\ScssCompilerInterface
   */
  protected $scssCompiler;

  /**
   * ScssCompilerCommands constructor.
   *
   * @param \Drupal\scss_compiler\ScssCompilerInterface $scss_compiler
   *   ScssCompiler service.
   */
  public function __construct(ScssCompilerInterface $scss_compiler) {
    parent::__construct();
    $this->scssCompiler = $scss_compiler;
  }

  /**
   * Flush compiler cache.
   *
   * @command compiler:cr
   * @usage drush ccr.
   * @aliases ccr.
   */
  public function flushCache() {
    $this->scssCompiler->flushCache();
  }

}
