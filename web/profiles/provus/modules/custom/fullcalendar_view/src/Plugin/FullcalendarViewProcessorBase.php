<?php

namespace Drupal\fullcalendar_view\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Fullcalendar view processor plugins.
 */
abstract class FullcalendarViewProcessorBase extends PluginBase implements FullcalendarViewProcessorInterface {

  /**
   * Process the view variable array.
   *
   * @param array $variables
   *   Template variables.
   */
  abstract public function process(array &$variables);

}
