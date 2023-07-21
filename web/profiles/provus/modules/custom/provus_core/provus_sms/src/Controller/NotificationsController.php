<?php

namespace Drupal\provus_sms\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * Notifications class.
 *
 * @package Drupal\provus_sms\Controller
 */
class NotificationsController extends ControllerBase {

  /**
   * Checks access for user Notifications page.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   * @param \Drupal\user\UserInterface $user
   *   The user entity object.
   */
  public function access(AccountInterface $account, UserInterface $user = NULL) {
    // Check if user can access SMS notifications page and this is the current
    // user's profile page.
    // Adding NULL handling for $user and set NULL default value for $user in
    // the argument.
    if (!($user instanceof UserInterface)) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowedIf($account->hasPermission('subscribe to sms notifications') && $account->id() == $user->id());
  }

  /**
   * Returns the username as title.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user entity object.
   *
   * @return array
   *   A simple renderable array.
   */
  public function title(UserInterface $user) {
    return t('@username', ['@username' => $user->getDisplayName()]);
  }

}
