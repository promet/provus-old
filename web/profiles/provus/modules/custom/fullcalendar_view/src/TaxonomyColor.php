<?php

namespace Drupal\fullcalendar_view;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class TaxonomyColor.
 */
class TaxonomyColor {
  use StringTranslationTrait;

  /**
   * Entity type manager.
   *
   * @var bool
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Color input box for taxonomy terms of a vocabulary.
   */
  public function colorInputBoxs($vid, array $defaultValues, $open = FALSE) {
    // Taxonomy color details.
    $elements = [
      '#type' => 'details',
      '#title' => $this->t('Colors for Taxonomies'),
      '#fieldset' => 'colors',
      '#open' => $open,
      '#prefix' => '<div id="color-taxonomies-div">',
      '#suffix' => '</div>',
      '#states' => [
        // Only show this field when the 'vocabularies' is selected.
        'invisible' => [
          [':input[name="style_options[vocabularies]"]' => ['value' => '']],
        ],
      ],
    ];
    // Term IDs of the vocabulary.
    $terms = $this->getTermIds($vid);
    if (isset($terms[$vid])) {
      // Create a color box for each terms.
      foreach ($terms[$vid] as $taxonomy) {
         // If the term name is a valid hex color, use it for the initial default color.
        $initial_color = preg_match('/^#[a-fA-F0-9]{6}$/', $taxonomy->name->value) ? $taxonomy->name->value : '#3a87ad';
        $color = isset($defaultValues[$taxonomy->id()]) ? $defaultValues[$taxonomy->id()] : $initial_color;
        $elements[$taxonomy->id()] = [
          '#title' => $taxonomy->name->value,
          '#default_value' => $color,
          '#type' => 'color',
          '#states' => [
           // Only show this field when the 'tax_field' is selected.
            'invisible' => [
             [':input[name="style_options[tax_field]"]' => ['value' => '']],
            ],
          ],
          '#attributes' => [
            'value' => $color,
            'name' => 'style_options[color_taxonomies][' . $taxonomy->id() . ']',
          ],
        ];
      }
    }

    return $elements;
  }

  /**
   * Get all terms of a vocabulary.
   */
  private function getTermIds($vid) {
    if (empty($vid)) {
      return [];
    }
    $terms = &drupal_static(__FUNCTION__);
    // Get taxonomy terms from database if they haven't been loaded.
    if (!isset($terms[$vid])) {
      // Get terms Ids.
      $query = $this->entityTypeManager->getStorage('taxonomy_term')->getQuery();
      $query->condition('vid', $vid);
      $tids = $query->execute();
      $terms[$vid] = $this->entityTypeManager->getStorage('taxonomy_term')->loadMultiple($tids);
    }

    return $terms;
  }

}
