<?php

namespace Drupal\county_blocks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\county_blocks\CountyBlocksHelper;

/**
 * Suggestion configuration form.
 */
class CountyBlocksAdminForm extends FormBase {

  /**
   * The county_blocks configuration form.
   *
   * @param array $form
   *   A drupal form array.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   A Drupal form state object.
   *
   * @return array
   *   A Drupal form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $cfg = CountyBlocksHelper::getConfig();

    $form['view'] = [
      '#title'         => $this->t('View'),
      '#description'   => $this->t('The view machine_name, (default: site_alert).'),
      '#type'          => 'textfield',
      '#default_value' => $cfg->view ? $cfg->view : '',
      '#size'          => 35,
      '#maxlength'     => 32,
      '#required'      => TRUE,
    ];
    $form['block'] = [
      '#title'         => $this->t('Block'),
      '#description'   => $this->t('The block machine_name, (default: alert_site_block_1).'),
      '#type'          => 'textfield',
      '#default_value' => $cfg->block ? $cfg->block : '',
      '#size'          => 35,
      '#maxlength'     => 32,
      '#required'      => TRUE,
    ];
    $form['url'] = [
      '#title'         => $this->t('URL'),
      '#description'   => $this->t('The URL to the JSON data, (default: https://tesla-orange.vbx/county-alert).'),
      '#type'          => 'textfield',
      '#default_value' => $cfg->url ? $cfg->url : '',
      '#size'          => 100,
      '#maxlength'     => 200,
      '#required'      => TRUE,
    ];
    $form['submit'] = [
      '#type'   => 'submit',
      '#value'  => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * The form ID.
   *
   * @return string
   *   The form ID.
   */
  public function getFormId() {
    return 'county_blocks_admin';
  }

  /**
   * Submit function for the county_blocks configuration form.
   *
   * @param array $form
   *   A drupal form array.
   * @param Drupal\Core\Form\FormStateInterface $form_state
   *   A Drupal form state object.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    CountyBlocksHelper::setConfig('view', $form_state->getValue('view'));
    CountyBlocksHelper::setConfig('block', $form_state->getValue('block'));
    CountyBlocksHelper::setConfig('url', $form_state->getValue('url'));
  }

}
