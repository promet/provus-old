<?php

namespace Drupal\provus_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides Node Page Top block content.
 *
 * @Block(
 *   id = "node_page_top",
 *   admin_label = @Translation("Node Page Top")
 * )
 */
class NodePageTop extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Node entity.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $nodeObj;

  /**
   * Node view builder.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $nodeView;

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
   * @param Drupal\Core\Entity\EntityTypeManager $mgr
   *   User services.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   The current route service.
   */
  public function __construct(array $config, $plugin_id, $plugin_def, Request $request, EntityTypeManager $mgr, CurrentRouteMatch $route) {
    parent::__construct($config, $plugin_id, $plugin_def);
    $this->nodeObj = $mgr->getStorage('node');
    $this->request = $request;
    $this->route = $route;
    $this->nodeView = $mgr->getViewBuilder('node');
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
    $accepted_types = [
      'event',
      'location',
    ];
    $node = $this->getNode();

    if (!is_object($node)) {
      return $build;
    }
    if (in_array($node->bundle(), $accepted_types)) {
      $build = $this->nodeView->view($node, 'content_top');
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
      $node = $this->nodeObj->loadRevision($m[1]);
    }
    elseif (preg_match('/^\/node\/(\d+)/', $path, $m)) {
      $node = $this->nodeObj->load($m[1]);
    }
    else {
      $node = $this->route->getParameter('node');
    }
    return is_object($node) ? $node : NULL;
  }

}
