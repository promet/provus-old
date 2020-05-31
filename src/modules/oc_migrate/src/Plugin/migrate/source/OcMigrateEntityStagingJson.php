<?php

namespace Drupal\oc_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\entity_staging\Plugin\migrate\source\EntityStagingJson;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;

/**
 * Drupal migrate source from JSON.
 *
 * @MigrateSource(
 *   id = "oc_migrate_entity_staging_json",
 * )
 */
class OcMigrateEntityStagingJson extends EntityStagingJson {

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    if ($this->iterator) {
      return json_encode($this->iterator->current(), JSON_PRETTY_PRINT);
    }
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $source = $row->getSource();
    foreach ($source as $key => &$item_list) {
      if (is_scalar($item_list)) {
        continue;
      }
      if (count($item_list) > 1) {
        $item = $item_list;
      }
      else {
        $item = reset($item_list);
      }

      if (in_array($key, [
        'type',
        'shortcut_set',
        'vid',
        'bundle',
        'queue',
      ]) && isset($item['target_id'])) {
        $value = $item['target_id'];
      }
      elseif (isset($item['target_uuid'])) {
        if (isset($item['alt']) && isset($item['title'])) {
          $row->setSourceProperty($key . '_alt', $item['alt']);
          $row->setSourceProperty($key . '_title', $item['title']);
        }
        $value = $item['target_uuid'];
      }
      elseif (is_scalar($item) || (count($item) != 1 && !isset($item['pid']))) {
        if (isset($item[0]) && isset($item[0]['target_uuid'])) {
          $value = [];
          foreach ($item as $it) {
            $value[] = $it['target_uuid'];
          }
        }
        else {
          $value = $item;
        }
      }
      elseif (isset($item['value'])) {
        $value = $item['value'];
      }
      elseif (isset($item['pid'])) {
        $value = $item['alias'];
      }
      else {
        $value = $item;
      }

      // This is the only difference from "EntityStagingJson".
      if ($key == 'uri' && isset($value['value'])) {
        $row->setSourceProperty('filepath', $value['value']);
      }

      if (empty($item)) {
        $value = NULL;
      }
      $row->setSourceProperty($key, $value);
    }
  }

}
