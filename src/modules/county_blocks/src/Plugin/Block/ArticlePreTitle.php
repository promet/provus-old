<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides Article Pre Title block content.
 *
 * @Block(
 *   id = "article_pre_title",
 *   admin_label = @Translation("Article Pre Title")
 * )
 */
class ArticlePreTitle extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Node entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $nodeMgr;

  /**
   * Request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $route;

  /**
   * Class constructor.
   *
   * @param array $config
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param string $plugin_def
   *   The plugin definition.
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   * @param Drupal\Core\Entity\EntityTypeManager $nodeMgr
   *   User services.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   The current route service.
   */
  public function __construct(array $config, $plugin_id, $plugin_def, Request $request, EntityTypeManager $nodeMgr, CurrentRouteMatch $route) {
    parent::__construct($config, $plugin_id, $plugin_def);
    $this->nodeMgr = $nodeMgr->getStorage('node');
    $this->request = $request;
    $this->route = $route;
  }

  /**
   * BlockBase extended constructor.
   *
   * @param Symfony\Component\DependencyInjection\ContainerInterface $pkg
   *   The container interface.
   * @param array $config
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param string $plugin_def
   *   The plugin definition.
   */
  public static function create(ContainerInterface $pkg, array $config, $plugin_id, $plugin_def) {
    return new static($config, $plugin_id, $plugin_def, $pkg->get('request_stack')->getCurrentRequest(), $pkg->get('entity_type.manager'), $pkg->get('current_route_match'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#cache']['contexts'] = ['url'];
    $node = $this->getNode();

    if (!is_object($node)) {
      return $build;
    }
    if ($node->bundle() == 'county_article') {
      $build = $node->get('field_type')->view([
        'type'     => 'list_string',
        'label'    => 'hidden',
        'settings' => [],
      ]);
    }
    $build['#cache'] = [
      'contexts' => ['url'],
      'tags'     => $node->getCacheTags(),
    ];
    return $build;
  }

  /**
   * Get the node revision.
   *
   * @return object
   *   A node object.
   */
  protected function getNode() {
    $path = $this->request->getRequestUri();

    if (preg_match('/^\/node\/\d+\/revisions\/(\d+)\/view$/', $path, $m)) {
      $node = $this->nodeMgr->loadRevision($m[1]);
    }
    elseif (preg_match('/^\/node\/(\d+)/', $path, $m)) {
      $node = $this->nodeMgr->load($m[1]);
    }
    else {
      $node = $this->route->getParameter('node');
    }
    return is_object($node) ? $node : NULL;
  }

}
