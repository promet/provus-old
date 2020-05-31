<?php

namespace Drupal\oc_site\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * OC site config.
 */
class OcSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'oc_site.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'oc_site_config_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['site_css'] = [
      '#type' => 'select',
      '#title' => $this->t('Style'),
      '#description' => $this->t('Style to use for the site'),
      '#options' => [
        'main' => $this->t('Main'),
        'orange' => $this->t('Orange'),
        'green' => $this->t('Green'),
        'blue' => $this->t('Blue'),
      ],
      '#default_value' => $config->get('site_css'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('site_css', $form_state->getValue('site_css'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
