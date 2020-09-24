<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Url;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SocialLinksBlock' block.
 *
 * @Block(
 *  id = "social_links_block",
 *  admin_label = @Translation("Social links block"),
 * )
 */
class SocialLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $data = $this->formatLinks($this->getUrl(), $this->getPageTitle());

    $cache = ['contexts' => ['url']];
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      $cache['tags'] = $node->getCacheTags();
    }

    return [
      '#theme' => 'county_blocks_social_links',
      '#data'  => $data,
      '#cache' => $cache,
    ];
  }

  /**
   * Returns full URL with http or https (as used by the request) including the domain.
   */
  private function getUrl() {
    $url = Url::fromRoute('<current>', [], ["absolute" => TRUE])->toString();
    return $url;
  }

  /**
   * Returns the page title.
   *
   * More info on why so cumbersome.
   * @link https://www.drupal.org/project/drupal/issues/2264043
   */
  private function getPageTitle() {
    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    $title = \Drupal::service('title_resolver')->getTitle($request, $route_match->getRouteObject());
    return $title;
  }

  /**
   * Returns an array with social links each with title, url and icon.
   */
  private function formatLinks($url, $text) {

    $facebook = [
      'title' => 'Share this page to Facebook',
      'url' => "https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&u=$url&display=popup&ref=plugin&src=share_button",
      'icon' => 'facebook'
    ];
    $twitter = [
      'title' => 'Share this page to Twitter',
      'url' => "http://twitter.com/share?text=$text&url=$url&hashtags=",
      'icon' => 'twitter'
    ];
    $linkedin = [
      'title' => 'Share this page to Linkedin',
      'url' => "https://www.linkedin.com/sharing/share-offsite/?url=$url",
      'icon' => 'linkedin'
    ];
    $link = [
      'title' => 'Copy this page as a Link',
      'url' => "$url",
      'icon' => 'link'
    ];

    return [$facebook, $twitter, $linkedin, $link];
  }

}
