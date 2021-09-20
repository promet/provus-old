<?php

namespace Drupal\siu_core\Plugin\RecipientType;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\workbench_email\TemplateInterface;
use Drupal\workbench_email\Plugin\RecipientType\Role;

/**
 * Provides a recipient type for Workbench Access Sections.
 *
 * @RecipientType(
 *   id = "roles_with_optout",
 *   title = @Translation("Roles with opt out option"),
 *   description = @Translation("Send to recipients with given role and not opt out."),
 *   settings = {
 *     "roles" = {},
 *   },
 * )
 */
class RolesWithOptOut extends Role {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $build = parent::buildConfigurationForm($form, $form_state);
    $build['roles']['#description'] = $this->t('Send to all users with selected roles who have permission to update the transitioned item and not opt out.');
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRecipients(ContentEntityInterface $entity, TemplateInterface $template) {
    $recipients = [];
    foreach ($this->getRoles() as $role) {
      foreach ($this->entityTypeManager->getStorage('user')->loadByProperties([
        'roles' => $role,
        'status' => 1,
      ]) as $account) {
        if ($entity->access('update', $account) && ($account->field_optout_notification->isEmpty() || $account->field_optout_notification->value === "0")) {
          $recipients[] = $account->getEmail();
        }
      }
    }
    return $recipients;
  }

}
