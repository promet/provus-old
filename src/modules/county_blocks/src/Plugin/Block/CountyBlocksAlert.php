<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a CountyBlocks block.
 *
 * @Block(
 *   id = "county_blocks_alert",
 *   admin_label = @Translation("County Blocks Alert")
 * )
 */
class CountyBlocksAlert extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * Config store.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $cfgStore;

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
   * Time.
   *
   * @var \Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * Extended constructor for the PageBlock class.
   *
   * @param array $config
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param string $plugin_def
   *   The plugin definition.
   * @param Drupal\Core\Config\ConfigFactoryInterface $cfgStore
   *   The config factory.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   The current route service.
   * @param Drupal\Component\Datetime\Time $time
   *   Time.
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   */
  public function __construct(array $config, $plugin_id, $plugin_def, ConfigFactoryInterface $cfgStore, CurrentRouteMatch $route, Time $time, Request $request) {
    parent::__construct($config, $plugin_id, $plugin_def);
    $this->cfgStore = $cfgStore;
    $this->request = $request;
    $this->route = $route;
    $this->time = $time;
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
    return new static($config, $plugin_id, $plugin_def, $pkg->get('config.factory'), $pkg->get('current_route_match'), $pkg->get('datetime.time'), $pkg->get('request_stack')->getCurrentRequest());
  }

  /**
   * Build the county_blocks block render array.
   *
   * @return array
   *   A Drupal render array.
   */
  public function build() {
    return [
      '#theme' => 'county_blocks_alert_block',
      '#cache' => ['max-age' => 0],
    ];
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

  /**
   * Select the previous and next node IDs.
   *
   * @param int $nid
   *   The node ID.
   *
   * @return string
   *   The node URL.
   */
  protected function getNodeUrl($nid) {
    return $nid ? Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString() : '';
  }

}
