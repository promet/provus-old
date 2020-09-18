<?php

namespace Drupal\county_blocks;

use GuzzleHttp\Client;

/**
 * Provides helper methods for county_blockss.
 */
class CountyBlocksHelper {

  /**
   * Fetch the alert feed.
   */
  public static function fetchAlerts() {
    $url = static::getConfig('url');

    if (!$url) {
      return;
    }
    $client = new Client(['verify' => FALSE]);

    $response = $client->request('GET', $url);

    $json = $response->getBody()->getContents();

    static::setData('alerts', json_decode($json, TRUE));
  }

  /**
   * Configuration get wrapper.
   *
   * @param string $key
   *   The field name.
   *
   * @return mixed
   *   The value of the supplied field.
   */
  public static function getConfig($key = '') {
    $cfg = &drupal_static(__CLASS__ . '_' . __FUNCTION__, \Drupal::configFactory()->get('county_blocks.config'));

    return $key ? $cfg->get($key) : (object) $cfg->get();
  }

  /**
   * Data get wrapper.
   *
   * @param string $key
   *   The field name.
   *
   * @return mixed
   *   The value of the supplied field.
   */
  public static function getData($key = '') {
    $cfg = &drupal_static(__CLASS__ . '_' . __FUNCTION__, \Drupal::configFactory()->get('county_blocks.data'));

    return $key ? $cfg->get($key) : (object) $cfg->get();
  }

  /**
   * Configuration set wrapper.
   *
   * @param string $key
   *   The field name.
   * @param mixed $val
   *   The field value.
   *
   * @return mixed
   *   The value of the supplied field.
   */
  public static function setConfig($key = '', $val = NULL) {
    $cfg = &drupal_static(__CLASS__ . '_' . __FUNCTION__, \Drupal::configFactory()->getEditable('county_blocks.config'));

    return $key ? $cfg->set($key, $val)->save() : NULL;
  }

  /**
   * Data set wrapper.
   *
   * @param string $key
   *   The field name.
   * @param mixed $val
   *   The field value.
   *
   * @return mixed
   *   The value of the supplied field.
   */
  public static function setData($key = '', $val = NULL) {
    $cfg = &drupal_static(__CLASS__ . '_' . __FUNCTION__, \Drupal::configFactory()->getEditable('county_blocks.data'));

    return $key ? $cfg->set($key, $val)->save() : NULL;
  }

}
