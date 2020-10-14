<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides Social Feed Youtube block content.
 *
 * @Block(
 *   id = "social_youtube_block",
 *   admin_label = @Translation("Social Feed Youtube Block")
 * )
 */
class SocialYoutubeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $service = \Drupal::service('county_blocks.youtube_feed'); // phpcs:ignore
    $items = $service->getYoutubePosts();

    $build = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      'child' => $items,
      '#cache' => ['max-age' => 0],
    ];

    return $build;

  }

}
