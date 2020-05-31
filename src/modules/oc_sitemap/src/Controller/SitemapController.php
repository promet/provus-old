<?php

namespace Drupal\oc_sitemap\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the oc_sitemap module.
 */
class SitemapController extends ControllerBase {

  /**
   * Returns a sitemap page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function build() {
    $block_manager = \Drupal::service('plugin.manager.block');
    $config = [];
    $plugin_block = $block_manager->createInstance('sitemap', $config);
    $access_result = $plugin_block->access(\Drupal::currentUser());
    if (is_object($access_result) && $access_result->isForbidden() || is_bool($access_result) && !$access_result) {
      return [];
    }
    $render = $plugin_block->build();
    return $render;
  }

}
