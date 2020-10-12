<?php

namespace Drupal\county_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides Newsletter Signup block content.
 *
 * @Block(
 *   id = "newsletter_signup",
 *   admin_label = @Translation("Newsletter Signup")
 * )
 */
class NewsletterSignup extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\county_blocks\Form\NewsletterSignupForm'); // phpcs:ignore
    return $form;

  }

}
