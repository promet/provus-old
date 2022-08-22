<?php

namespace Drupal\provus;

use Drupal\Core\Extension\Extension;
use Drupal\Core\Extension\ExtensionDiscovery;

/**
 * Helper object to locate provus components and sub-components.
 *
 * Leveraged from code provided by Acquia for the Lightning distribution.
 */
class ComponentDiscovery {

  /**
   * Prefix that provus components are expected to start with.
   */
  const COMPONENT_PREFIX = 'provus_';

  /**
   * The extension discovery iterator.
   *
   * @var \Drupal\Core\Extension\ExtensionDiscovery
   */
  protected $discovery;

  /**
   * The provus profile extension object.
   *
   * @var \Drupal\Core\Extension\Extension
   */
  protected $profile;

  /**
   * Cache of all discovered components.
   *
   * @var \Drupal\Core\Extension\Extension[]
   */
  protected $components;

  /**
   * ComponentDiscovery constructor.
   *
   * @param string $app_root
   *   The application root directory.
   */
  public function __construct($app_root) {
    @trigger_error(__CLASS__ . ' is deprecated in provus:8.x-4.0 and will be removed in provus:8.x-4.1. If you need it, copy it into your project. See https://www.drupal.org/node/3156219', E_USER_DEPRECATED);
    $this->discovery = new ExtensionDiscovery($app_root);
  }

  /**
   * Returns an extension object for the provus profile.
   *
   * @return \Drupal\Core\Extension\Extension
   *   The provus profile extension object.
   *
   * @throws \RuntimeException
   *   If the provus profile is not found in the system.
   */
  protected function getProfile() {
    if (empty($this->profile)) {
      $profiles = $this->discovery->scan('profile');

      if (empty($profiles['provus'])) {
        throw new \RuntimeException('provus profile not found.');
      }
      $this->profile = $profiles['provus'];
    }
    return $this->profile;
  }

  /**
   * Returns extension objects for all provus components.
   *
   * @return \Drupal\Core\Extension\Extension[]
   *   Array of extension objects for all provus components.
   */
  public function getAll() {
    if (is_null($this->components)) {
      $identifier = self::COMPONENT_PREFIX;

      $filter = function (Extension $module) use ($identifier) {
        return strpos($module->getName(), $identifier) === 0;
      };

      $this->components = array_filter($this->discovery->scan('module'), $filter);
    }
    return $this->components;
  }

  /**
   * Returns extension objects for all main provus components.
   *
   * @return \Drupal\Core\Extension\Extension[]
   *   Array of extension objects for top-level provus components.
   */
  public function getMainComponents() {
    $identifier = self::COMPONENT_PREFIX;

    $filter = function (Extension $module) use ($identifier) {
      // Assumes that:
      // 1. provus sub-components are always in a sub-directory within the
      //    main component.
      // 2. The main component's directory starts with "provus_".
      // E.g.: "/provus_core/modules/provus_search".
      $path = explode(DIRECTORY_SEPARATOR, $module->getPath());
      $parent = $path[count($path) - 3];
      return strpos($parent, $identifier) !== 0;
    };

    return array_filter($this->getAll(), $filter);
  }

  /**
   * Returns extension object for all provus sub-components.
   *
   * @return \Drupal\Core\Extension\Extension[]
   *   Array of extension objects for provus sub-components.
   */
  public function getSubComponents() {
    return array_diff_key($this->getAll(), $this->getMainComponents());
  }

}
