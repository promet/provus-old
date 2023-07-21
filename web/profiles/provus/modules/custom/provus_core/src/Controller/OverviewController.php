<?php

namespace Drupal\provus_core\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\system\SystemManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Overview class.
 *
 * @package Drupal\provus_core\Controller
 */
class OverviewController extends ControllerBase {

  /**
   * The menu link manager.
   *
   * @var \Drupal\Core\Menu\MenuLinkManagerInterface
   */
  protected $menuLinkManager;

  /**
   * The system manager.
   *
   * @var \Drupal\system\SystemManager
   */
  protected $systemManager;

  /**
   * Constructs a new OverviewController.
   *
   * @param \Drupal\Core\Menu\MenuLinkManagerInterface $menu_link_manager
   *   The entity type managers interface.
   * @param \Drupal\system\SystemManager $system_manager
   *   System manager service.
   */
  public function __construct(MenuLinkManagerInterface $menu_link_manager, SystemManager $system_manager) {
    $this->menuLinkManager = $menu_link_manager;
    $this->systemManager = $system_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.menu.link'),
      $container->get('system.manager')
    );
  }

  /**
   * Checks access for Provus admin overview page.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   */
  public function access(AccountInterface $account) {
    // Check if user can access administration pages and
    // Provus overview menu tree is not empty.
    $links = $this->menuLinkManager
      ->loadLinksByRoute('provus_core.admin');
    $link = reset($links);
    return AccessResult::allowedIf($account->hasPermission('access administration pages') && $link && $this->systemManager->getAdminBlock($link));
  }

}
