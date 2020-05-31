<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides Article Pre Title block content.
 *
 * @Block(
 *   id = "article_pre_title",
 *   admin_label = @Translation("Article Pre Title")
 * )
 */
class ArticlePreTitle extends BlockBase {

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
      $content_type = $node->bundle();
      if ($content_type == 'county_article') {
        $build = $node->get('field_type')->view([
          'type' => 'list_string',
          'label' => 'hidden',
          'settings' => [],
        ]);
      }
      $build['#cache'] = [
        'contexts' => ['url'],
        'tags' => $node->getCacheTags(),
      ];
    }

    return $build;

  }

}
