<?php

namespace Drupal\provus_sms;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Notifications helper service.
 */
class NotificationsHelper {

  /**
   * Subscribe permision.
   */
  CONST SUBSCRIBER_PERMISSION = 'subscribe to sms notifications';

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }
  
  /**
   * Gets the list of subscribers.
   * 
   * @return array $subscribers
   *   The list of subscribers.
   */
  private function getSubscribers() {
    $user_storage = $this->entityTypeManager
      ->getStorage('user');

    $subscribers = [];
    $query = $user_storage->getQuery()
      ->condition('status', 1)
      ->condition('field_sms_opt_in', 1)
      ->exists('phone_number');

    // Limit to allowed roles to subscribe.
    $roles = $this->getSubscriberRoles();
    if (!empty($roles)) {
      $or_condition = $query->orConditionGroup();
      $or_condition->condition('roles', $roles, 'IN');
      $or_condition->condition('uid', 1);
      $query->condition($or_condition);
    }
    else {
      $query->condition('uid', 1);
    }

    $user_ids = $query->execute();
    if (!empty($user_ids)) {
      $subscribers = $user_storage->loadMultiple($user_ids);
    }

    return $subscribers;
  }

  /**
   * Gets the list of subscriber roles.
   * 
   * @return array $roles
   *   The list of subscriber roles.
   */
  private function getSubscriberRoles() {
    $user_role_storage = $this->entityTypeManager
      ->getStorage('user_role');

    $roles = [];
    $user_roles = $user_role_storage->loadMultiple();
    foreach ($user_roles as $user_rid => $user_role) {
      if ($user_role->hasPermission(self::SUBSCRIBER_PERMISSION)) {
        $roles[] = $user_rid;
      }
    }

    return $roles;
  }

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
    dsm($this->getSubscribers());
  }

}
