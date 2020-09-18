<?php

namespace Drupal\county_blocks\Controller;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\county_blocks\CountyBlocksTxt;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for county_blocks module routes.
 */
class CountyBlocksJsonController extends ControllerBase {
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
   * Time.
   *
   * @var \Drupal\Component\Datetime\Time
   */
  protected $time;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Config\ConfigFactoryInterface $cfgStore
   *   The config factory.
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   * @param Drupal\Component\Datetime\Time $time
   *   Time.
   */
  public function __construct(ConfigFactoryInterface $cfgStore, Request $request, Time $time) {
    $this->cfgStore = $cfgStore;
    $this->request = $request;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'), $container->get('request_stack')->getCurrentRequest(), $container->get('datetime.time'));
  }

  /**
   * JSON callback for alerts.
   *
   * @return object
   *   A JSON formatted feed.
   */
  public function countyAlert() {
    return $this->render($this->cfgStore->get('county_blocks.config')->get('view'), $this->cfgStore->get('county_blocks.config')->get('block'));
  }

  /**
   * Process the text field.
   *
   * @param string $txt
   *   Text field.
   *
   * @return string
   *   A filterd text.
   */
  protected function filterJsonTxt($txt) {
    $txt = CountyBlocksTxt::filter($txt, 'hex');
    $txt = CountyBlocksTxt::filter($txt, 'entity');
    $txt = CountyBlocksTxt::filter($txt, 'html');

    $regx = [
      '/<[^>]+>/'            => ' ',
      '/\s+/'                => ' ',
      '/^\s+|\s+$/'          => '',
      '/[^[:print:]]/'       => '',
      '/[^(\x20-\x7F\n)]+/u' => '',
    ];
    return preg_replace(array_keys($regx), array_values($regx), $txt);
  }

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'county_blocks';
  }

  /**
   * Extract the broadcast flag.
   *
   * @param object $obj
   *   A view entity object.
   *
   * @return bool
   *   A broadcast flag.
   */
  protected function getBroadcast($obj) {
    return !empty($obj->field_alert_broadcast) ? (bool) $obj->get('field_alert_broadcast')->value : FALSE;
  }

  /**
   * Extract and clean the visibility paths.
   *
   * @param object $obj
   *   A view entity object.
   *
   * @return string
   *   A cleaned string.
   */
  protected function getPaths($obj) {
    static $regex = [
      '/<[^>]*>/s' => ' ',
      '/\s+/s'     => ' ',
      '/^\s|\s$/s' => '',
    ];
    if (empty($obj->field_alert_visibility)) {
      return '';
    }
    $paths = preg_replace(array_keys($regex), array_values($regex), $obj->get('field_alert_visibility')->value);

    return preg_match('/\w+/', $paths) ? $paths : '';
  }

  /**
   * Build a JSON response.
   *
   * @param array $data
   *   The view result data.
   *
   * @return object
   *   A JSON object.
   */
  protected function getReply(array $data) {
    $reply = new Response(json_encode($data, JSON_UNESCAPED_UNICODE));
    $reply->headers->set('Content-Type', 'application/json; charset=UTF-8');

    return $reply;
  }

  /**
   * Build an alert object.
   *
   * @param object $obj
   *   The view row object.
   *
   * @return object
   *   An alert object.
   */
  protected function getRowData($obj) {
    return (object) [
      'nid'      => !empty($obj->nid) ? $obj->get('nid')->value : '',
      'title'    => !empty($obj->title) ? $this->filterJsonTxt($obj->get('title')->value) : '',
      'body'     => !empty($obj->body) ? $this->filterJsonTxt($obj->get('body')->value) : '',
      'changed'  => !empty($obj->changed) ? $obj->get('changed')->value : 0,
      'created'  => !empty($obj->created) ? $obj->get('created')->value : 0,
      'fetch'    => $this->time->getCurrentTime(),
      'external' => (int) $this->getBroadcast($obj),
    ];
  }

  /**
   * Render the videos as JSON.
   *
   * @param string $view
   *   The view's machine_name.
   * @param string $block
   *   The block's machine_name.
   *
   * @return object
   *   A JSON formatted feed.
   */
  protected function render($view, $block) {
    $data = [];
    $rows = views_get_view_result($view, $block);

    if (empty($rows)) {
      return $this->getReply([]);
    }
    foreach ($rows as $row) {
      if (!$this->getBroadcast($row->_entity)) {
        continue;
      }
      $data[] = $this->getRowData($row->_entity);
    }
    return $this->getReply($data);
  }

}
