<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'ExampleBlock' block.
 *
 * @Block(
 *   id = "example_block",
 *   admin_label = @Translation("Example Block")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Cargar un nodo por su ID.
    $node = Node::load(1); // Cambia el ID del nodo según tus necesidades.

    return [
      '#theme' => 'example_block',
      '#node' => $node,
      '#cache' => [
        'max-age' => 0, // Deshabilitar caché para desarrollo, quitar esto en producción.
      ],
    ];
  }
}