<?php

namespace Drupal\provus_sms;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\sms\Direction;
use Drupal\sms\Exception\RecipientRouteException;
use Drupal\sms\Message\SmsMessage;
use Drupal\sms\Provider\SmsProviderInterface;
use Drupal\token\TokenInterface;

/**
 * Notifications service.
 */
class Notifications {

  /**
   * Subscribe permision.
   */
  CONST SUBSCRIBER_PERMISSION = 'subscribe to sms notifications';

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The SMS provider.
   *
   * @var \Drupal\sms\Provider\SmsProviderInterface
   */
  protected $smsProvider;

  /**
   * The token service.
   * 
   * @var \Drupal\token\Token
   */
  protected $tokenService;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\sms\Provider\SmsProviderInterface $sms_provider
   *   The SMS provider.
   * @param \Drupal\token\TokenInterface $tokenService
   *   The token service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, SmsProviderInterface $sms_provider, TokenInterface $token_service) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->smsProvider = $sms_provider;
    $this->tokenService = $token_service;
  }

  /**
   * Get the message template of the node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity object.
   *
   * @return string $message
   *   A message with token already replaced.
   */
  private function getMessageTemplate(NodeInterface $node) {
    $message = '';
    
    // Get the appropriate message template of the node bundle.
    $config = $this->configFactory
      ->get('provus_sms.settings');
    $templates = $config->get('templates');
    $template = isset($templates[ $node->bundle() . '_template']) && !empty($templates[ $node->bundle() . '_template']) ?
      $templates[$node->bundle() . '_template'] :
      $templates['default_template'];

    // Convert the tokens of the message template.
    $message = $this->tokenService
      ->replace($template, [
        'node' => $node,
      ]);

    return $message; 
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
    
    // Get a list of active users who subscribed and has a phone number.
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
   * Checks if node is allowed send out notifications.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity object.
   *
   * @return boolean $allowed
   *   Whether the node is allowed to send out notifications or not.
   */
  private function isNodeAllowedNotifications(NodeInterface $node) {
    $allowed = FALSE;

    $config = $this->configFactory
      ->get('provus_sms.settings');
    $types = $config->get('types');
    $types = array_filter($types, function($type) { return ($type !== 0); });
    // If there are no types enabled, it will be enabled to all.
    // Otherwise check if the bundle is enabled.
    if (empty($types) ||
      isset($types[$node->bundle()])) {
      $allowed = TRUE;
    }

    return $allowed;
  }

  /**
   * Sends SMS notifications to subscribers.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity object.
   */
  public function send(NodeInterface $node) {
    if ($this->isNodeAllowedNotifications($node)) {
      $message = $this->getMessageTemplate($node);
      $subscribers = $this->getSubscribers();
      foreach ($subscribers as $subscriber) {
        $phone = $subscriber->get('phone_number')->value;
        $sms = new SmsMessage();
        $sms->setMessage($message)
          ->addRecipient($phone)
          ->setDirection(Direction::OUTGOING);
        try {
          $this->smsProvider
            ->queue($sms);
        }
        catch (RecipientRouteException $e) {
          // @todo: Add a receipient route exception here.
        }
        catch (\Exception $e) {
          // @todo: Add an exception here.
        }
      }
    }
  }

}
