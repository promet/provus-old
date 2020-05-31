<?php

namespace Drupal\oc_sitemap\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\system\Plugin\Block\SystemMenuBlock;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "sitemap",
 *   admin_label = @Translation("Sitemap"),
 *   category = @Translation("Sitemap"),
 * )
 */
class SitemapBlock extends SystemMenuBlock {

  /**
   * The menu link tree service.
   *
   * @var MenuLinkTree
   */
  protected $menuTree;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->setMenuTree($container->get('menu.link_tree'));

    return $instance;
  }

  /**
   * Set menu link tree service.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_link_tree
   *   The menu link tree service.
   *
   * @return $this
   */
  public function setMenuTree(MenuLinkTreeInterface $menu_link_tree) {
    $this->menuTree = $menu_link_tree;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $menu_name = 'main';
    $menu_parameters = new MenuTreeParameters();
    $tree = $this->menuTree->load($menu_name, $menu_parameters);
    $items = $this->menuTree->build($tree);
    if (empty($items['#items'])) {
      return [];
    }
    $menu = [];
    $this->buildMenuRecursive($items['#items'], $menu);
    return [
      '#theme' => 'oc_sitemap_sitemap',
      '#items' => $menu,
      '#attached' => [
        'library' => ['oc_sitemap/sitemap'],
      ],
    ];
  }

  /**
   * Build the menu theme array recursively.
   */
  public function buildMenuRecursive($items, &$menu) {
    foreach ($items as $key => $menu_link) {
      $menu[$key] = [
        '#theme' => 'oc_sitemap_link',
        '#title' => $menu_link['title'],
        '#link' => $menu_link['url']->toString(),
      ];
      if (!empty($menu_link['below'])) {
        $menu[$key]['#items'] = [];
        $this->buildMenuRecursive($menu_link['below'], $menu[$key]['#items']);
      }
    }
  }

}
