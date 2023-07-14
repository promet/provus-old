<?php

namespace Drupal\provus_sms;

use Drupal\node\NodeInterface;

/**
 * Notifications helper service.
 */
class NotificationsHelper {

  /**
   * Sends SMS notifications to subscribers.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity object.
   *
   * @return array
   *   A simple renderable array.
   */
  public function send(NodeInterface $node) {
    \Drupal::messenger()->addMessage(t('@todo: Notifications.'));
  }

}
