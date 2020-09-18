<?php

namespace Drupal\county_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Social Youtube Settings.
 */
class SocialYoutubeForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'county_blocks.socialyoutubesettings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'county_blocks_social_youtube_settings';
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

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#description' => $this->t('API Key from google developer app.'),
      '#default_value' => $config->get('api_key'),
    ];

    $form['youtube_channel_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Youtube Channel ID'),
      '#description' => $this->t('The id of the youtube channel that will be pulled. Ex: UCBi2mrWuNuyYy4gbM6fU18Q'),
      '#default_value' => $config->get('youtube_channel_id'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('youtube_channel_id', $form_state->getValue('youtube_channel_id'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
