<?php

namespace Drupal\provus_core\EventSubscriber;

use Drupal\Core\Block\BlockPluginInterface;
use Drupal\layout_builder\Event\SectionComponentBuildRenderArrayEvent;
use Drupal\layout_builder\LayoutBuilderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Alters built render arrays from layout builder block content components.
 *
 * @internal
 *   Tagged services are internal.
 */
class LbBlockComponentRenderArray implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[LayoutBuilderEvents::SECTION_COMPONENT_BUILD_RENDER_ARRAY] = ['onBuildRender',-100];
    return $events;
  }

  /**
   * Alters the built render arrays for block content block plugins.
   *
   * @param \Drupal\layout_builder\Event\SectionComponentBuildRenderArrayEvent $event
   *   The section component render event.
   */
  public function onBuildRender(SectionComponentBuildRenderArrayEvent $event) {
    $block = $event->getPlugin();
    if ($block instanceof BlockPluginInterface) {
      $build = $event->getBuild();
      $content = $block->build();
      if (!empty($content['#block_content'])) {
        // Get the preview label from the block instead of the block plugin.
        $build['#attributes']['data-layout-content-preview-placeholder-label'] = $content['#block_content']->label();
        $event->setBuild($build);
      }
    }
  }

}
