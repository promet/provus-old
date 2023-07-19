<?php

namespace Drupal\provus_sms\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for Provus SMS settings.
 */
class ProvusSmsSettingsForm extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\Extension\ModuleHandlerInterface definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new SmsSettingsForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type managers interface.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'provus_sms_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['provus_sms.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('provus_sms.settings');

    $form['enable'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enable SMS notifications'),
    ];

    $types = $this->entityTypeManager
      ->getStorage('node_type')
      ->loadMultiple();
    $types_options = [];
    $states_options = [];
    foreach ($types as $type => $data) {
      $types_options[$type] = $data->label();
      $states_options['input.form-checkbox[name="types[' . $type . ']"]'] = ['checked' => FALSE];
    }

    $form['enable']['types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Types'),
      '#options' => $types_options,
      '#default_value' => $config->get('types') ?? [],
      '#description' => $this->t('If no types are selected, SMS notifications will be enabled to all types.'),
    ];

    $form['templates'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Message templates'),
    ];
    
    $templates = $config->get('templates');
    $form['templates']['default_template'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Default'),
      '#default_value' => isset($templates['default_template']) ? $templates['default_template'] : '',
      '#required' => TRUE,
    ];

    foreach ($types as $type => $data) {
      $form['templates'][$type] = [
        '#type' => 'details',
        '#title' => $this->t('@type', ['@type' => $data->label()]),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#states' => [
          'visible' => [
            ['input.form-checkbox[name="types[' . $type . ']"]' => ['checked' => TRUE]],
            'or',
            [$states_options],
          ],
        ],
      ];

      $form['templates'][$type][$type . '_template'] = [
        '#type' => 'textarea',
        '#default_value' => isset($templates[$type . '_template']) ? $templates[$type . '_template'] : '',
      ];
    }

    // Display the list of available placeholders if token module is installed.
    if ($this->moduleHandler
      ->moduleExists('token')) {
      $form['templates']['token_help'] = array(
        '#theme' => 'token_tree_link',
        '#global_types' => TRUE,
        '#token_types' => ['node'],
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('provus_sms.settings');
    $config->set('types', $form_state->getValue('types'));
    $templates = [];
    $templates['default_template'] = $form_state->getValue('default_template');
    $types = $this->entityTypeManager
      ->getStorage('node_type')
      ->loadMultiple();
    foreach ($types as $type => $data) {
      $templates[$type . '_template'] = $form_state->getValue($type . '_template');
    }
    $config->set('templates', $templates);
    $config->save();
  }

}
