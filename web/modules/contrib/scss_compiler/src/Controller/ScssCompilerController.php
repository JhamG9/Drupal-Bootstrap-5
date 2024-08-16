<?php

namespace Drupal\scss_compiler\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\scss_compiler\ScssCompilerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * ScssCompiler controller object.
 */
class ScssCompilerController extends ControllerBase {

  /**
   * A scss compiler service instance.
   *
   * @var \Drupal\scss_compiler\ScssCompilerService
   */
  protected $scssCompiler;

  /**
   * A request stack symfony instance.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a ScssCompilerController object.
   *
   * @param \Drupal\scss_compiler\ScssCompilerInterface $scss_compiler
   *   A scss compiler service instance.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   A request stack symfony instance.
   */
  public function __construct(ScssCompilerInterface $scss_compiler, RequestStack $request_stack) {
    $this->scssCompiler = $scss_compiler;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('scss_compiler'),
      $container->get('request_stack')
    );
  }

  /**
   * Reload the previous page.
   */
  public function reloadPage() {
    $request = $this->requestStack->getCurrentRequest();
    if ($request->server->get('HTTP_REFERER')) {
      return $request->server->get('HTTP_REFERER');
    }
    else {
      return '/';
    }
  }

  /**
   * Recompile all source files.
   */
  public function flush() {
    $this->scssCompiler->flushCache();
    return new RedirectResponse($this->reloadPage());
  }

}
