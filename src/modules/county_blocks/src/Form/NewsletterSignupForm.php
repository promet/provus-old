<?php

namespace Drupal\county_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Newsletter Signup Form.
 *
 * @package Drupal\county_blocks\Form
 */
class NewsletterSignupForm extends FormBase {
  use StringTranslationTrait;

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Configuration Factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      $container->get('config.factory')
    );
  }

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
    $site_config = $this->configFactory->getEditable('oc_site.settings');
    $topic_id = $site_config->get('newsletter_topic_id');

    $form['#method'] = 'get';
    $form['#action'] = 'https://public.govdelivery.com/accounts/CAORANGE/subscriber/qualify';

    if ($topic_id) {
      $form['topic_id'] = [
        '#type' => 'hidden',
        '#value' => $topic_id,
      ];
    }

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your E-mail'),
      '#required' => TRUE,
      '#title_display' => 'invisible',
      '#attributes' => ['placeholder' => $this->t('Your E-mail')],
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
