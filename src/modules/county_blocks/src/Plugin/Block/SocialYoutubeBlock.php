<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Google_Client;
use Google_Service_YouTube;

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

    $service = \Drupal::service('county_blocks.youtube_feed');
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
