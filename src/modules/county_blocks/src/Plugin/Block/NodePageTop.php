<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides Node Page Top block content.
 *
 * @Block(
 *   id = "node_page_top",
 *   admin_label = @Translation("Node Page Top")
 * )
 */
class NodePageTop extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#cache' => [
        'contexts' => ['url'],
      ]
    ];

    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      $accepted_types = [
        'event',
        'location',
      ];
      $content_type = $node->bundle();
      if (in_array($content_type, $accepted_types)) {
        $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
        $build = $view_builder->view($node, 'content_top');
      }
      $build['#cache'] = [
        'contexts' => ['url'],
        'tags' => $node->getCacheTags(),
      ];
    }

    return $build;

  }

}
