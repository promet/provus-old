<?php

namespace Drupal\fullcalendar_view\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Fullcalendar view processor item annotation object.
 *
 * @see \Drupal\fullcalendar_view\Plugin\FullcalendarViewProcessorManager
 * @see plugin_api
 *
 * @Annotation
 */
class FullcalendarViewProcessor extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * Supported field types.
   *
   * @var array
   */
  public $field_types;

}
