<?php

namespace Drupal\siu_core\Plugin\RecipientType;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\workbench_email\Plugin\RecipientTypeBase;
use Drupal\workbench_email\TemplateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a plugin for mailing to last revision author.
 *
 * @RecipientType(
 *   id = "last_revision_author_with_optout",
 *   title = @Translation("Last revision author with opt out option"),
 *   description = @Translation("Send to previous revision author and not opt out."),
 * )
 */
class LastRevisionAuthorWithOptOut extends RecipientTypeBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    return $instance->setEntityTypeManager($container->get('entity_type.manager'));
  }

  /**
   * Sets entity type manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   *
   * @return $this
   */
  protected function setEntityTypeManager(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRecipients(ContentEntityInterface $entity, TemplateInterface $template) {
    $entityStorage = $this->entityTypeManager->getStorage($entity->getEntityTypeId());
    $id_key = $entity->getEntityType()->getKey('id');
    if (!$id_key) {
      return [];
    }
    $revisions = $entityStorage
      ->getQuery()
      ->condition($id_key, $entity->id())
      ->accessCheck(FALSE)
      ->allRevisions()
      ->execute();
    ksort($revisions);
    // Remove current revision.
    array_pop($revisions);
    $revision_ids = array_keys($revisions);
    $revision_id = array_pop($revision_ids);
    if ($revision_id && ($lastRevision = $entityStorage->loadRevision($revision_id)) && $lastRevision instanceof RevisionLogInterface) {
      $account = $lastRevision->getRevisionUser();
      if ($account->field_optout_notification->isEmpty() || $account->field_optout_notification->value === "0") {
        return [$account->getEmail()];
      }
    }
    return [];
  }

}
