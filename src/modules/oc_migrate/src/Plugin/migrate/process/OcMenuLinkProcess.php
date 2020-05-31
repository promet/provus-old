<?php

namespace Drupal\oc_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\file\FileInterface;
use Drupal\migrate\Row;
use Drupal\media_entity\Entity\Media;
use Drupal\Core\Database\Database;
use Drupal\Component\Utility\Unicode;
use Drupal\redirect\Entity\Redirect;

/**
 * Perform custom value transformations.
 *
 * @MigrateProcessPlugin(
 *   id = "oc_menu_link_process"
 * )
 *
 * To do custom value transformations use the following:
 *
 * @code
 * field_text:
 *   plugin: oc_menu_link_process
 *   source: text
 * @endcode
 */
class OcMenuLinkProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $items = $row->getSource();
    $path = $items['link']['uri'];
    $link = ['uri' => $items['link']['uri']];
    if (substr($items['link']['uri'], 0, 1) == '/') {
      $path = substr($items['link']['uri'], 1);
    }
    if ($nid = $this->getNidByPath($path)) {
      $link = ['uri' => 'entity:node/' . $nid];
    }
    // If the /path doesn't exist then use the full URL.
    elseif (substr($items['link']['uri'], 0, 1) == '/') {
      $link = ['uri' => $items['link']['url']];
    }
    return $link;
  }

  /**
   * Finds nid by title.
   */
  private function getNidByPath($path) {
    $rids = \Drupal::entityQuery('redirect')
      ->condition('redirect_source__path', $path)
      ->execute();

    if (count($rids) > 1) {
      // TODO: Add migrate log message if duplicate titles.
    }
    if ($rid = array_shift($rids)) {
      $entity = Redirect::load($rid);
      $redirect = $entity->getRedirect();

      return str_replace('internal:/node/', '', $redirect['uri']);
    }
    else {
      return FALSE;
    }
  }

}
