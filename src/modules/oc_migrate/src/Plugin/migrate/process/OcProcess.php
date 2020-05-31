<?php

namespace Drupal\oc_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\file\FileInterface;
use Drupal\migrate\Row;
use Drupal\media_entity\Entity\Media;
use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Unicode;

/**
 * Perform custom value transformations.
 *
 * @MigrateProcessPlugin(
 *   id = "oc_process"
 * )
 *
 * To do custom value transformations use the following:
 *
 * @code
 * field_text:
 *   plugin: oc_process
 *   source: text
 * @endcode
 */
class OcProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $path = $row->getSourceProperty('path');
    $item = json_decode(file_get_contents($path));
    if ($destination_property == 'type') {
      return $item->type;
    }
    elseif ($destination_property == 'title') {
      return $item->title;
    }
    elseif ($destination_property == 'body/value') {
      return $item->body;
    }
    elseif ($destination_property == 'field_type') {
      return $item->article_type;
    }
    elseif ($destination_property == 'field_publish_date') {
      return $item->date;
    }
    elseif ($destination_property == 'redirect') {
      if (substr($item->url, 0, 1) == '/') {
        return substr($item->url, 1);
      }
      else {
        return $item->url;
      }
    }
    elseif ($destination_property == 'url') {
      return $item->url;
    }
    elseif ($destination_property == 'created' || $destination_property == 'changed') {
      return (!empty($item->date)) ? strtotime($item->date) : time();
    }
    else {
      return '';
    }
  }

}
