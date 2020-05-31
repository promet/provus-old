<?php

namespace Drupal\county_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Newsletter Signup Form.
 *
 * @package Drupal\county_blocks\Form
 */
class NewsletterSignupForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'newsletter_signup_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#method'] = 'get';
    $form['#action'] = 'https://public.govdelivery.com/accounts/CAORANGE/subscriber/qualify';

    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Your E-mail'),
      '#required' => TRUE,
      '#title_display' => 'invisible',
      '#attributes' => ['placeholder' => t('Your E-mail')],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Subscribe'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
