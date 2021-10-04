<?php

namespace Drupal\views_component_block_display\Plugin\views\display;

use Drupal\Core\Url;
use Drupal\Component\Plugin\Discovery\CachedDiscoveryInterface;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\DependencyInjection\DeprecatedServicePropertyTrait;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\Block\ViewsBlock;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The plugin that handles a block.
 *
 * @ingroup views_display_plugins
 *
 * @ViewsDisplay(
 *   id = "views_component_block_display",
 *   title = @Translation("Component Block"),
 *   help = @Translation("Display the view as a block."),
 *   theme = "views_view",
 *   register_theme = FALSE,
 *   uses_hook_block = TRUE,
 *   contextual_links_locations = {"views_component_block_display"},
 *   admin = @Translation("Component Block")
 * )
 *
 * @see \Drupal\views\Plugin\Block\ViewsBlock
 * @see \Drupal\views\Plugin\Derivative\ViewsBlock
 */
class ComponentBlock extends DisplayPluginBase {
  use DeprecatedServicePropertyTrait;

  /**
   * {@inheritdoc}
   */
  protected $deprecatedProperties = ['entityManager' => 'entity.manager'];

  /**
   * Whether the display allows attachments.
   *
   * @var bool
   */
  protected $usesAttachments = TRUE;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

   /**
   * EntityDisplayRepository.
   *
   * @var Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;


  /**
   * Constructs a new Block instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param \Drupal\Core\Block\BlockManagerInterface $block_manager
   *   The block manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, BlockManagerInterface $block_manager, EntityDisplayRepositoryInterface $entityDisplayRepository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
    $this->blockManager = $block_manager;
    $this->entityDisplayRepository = $entityDisplayRepository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.block'),
      $container->get('entity_display.repository')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['views_component_block_display_description'] = ['default' => ''];
    $options['views_component_block_display_hide_empty'] = ['default' => FALSE];
    $options['views_component_block_display_items_per_page'] = ['default' => TRUE];
    $options['views_component_block_display_filters'] = ['default' => TRUE];
    $options['views_component_block_display_sorts'] = ['default' => TRUE];
    $options['views_component_block_display_view_mode'] = ['default' => []];

    return $options;
  }

  /**
   * Returns plugin-specific settings for the block.
   *
   * @param array $settings
   *   The settings of the block.
   *
   * @return array
   *   An array of block-specific settings to override the defaults provided in
   *   \Drupal\views\Plugin\Block\ViewsBlock::defaultConfiguration().
   *
   * @see \Drupal\views\Plugin\Block\ViewsBlock::defaultConfiguration()
   */
  public function blockSettings(array $settings) {
    $settings['items_per_page'] = 'none';
    $settings['filters'] = 'none';
    $settings['view_mode'] = 'none';
    return $settings;
  }

  /**
   * The display block handler returns the structure necessary for a block.
   */
  public function execute() {
    // Prior to this being called, the $view should already be set to this
    // display, and arguments should be set on the view.
    $element = $this->view->render();
    if ($this->outputIsEmpty() && $this->getOption('block_hide_empty') && empty($this->view->style_plugin->definition['even empty'])) {
      return [];
    }
    else {
      return $element;
    }
  }

  /**
   * Provide the summary for page options in the views UI.
   *
   * This output is returned as an array.
   */
  public function optionsSummary(&$categories, &$options) {
    parent::optionsSummary($categories, $options);

    $categories['views_component_block_display'] = [
      'title' => $this->t('Component Block settings'),
      'column' => 'second',
      'build' => [
        '#weight' => -10,
      ],
    ];

    $block_description = strip_tags($this->getOption('views_component_block_display_description'));
    if (empty($block_description)) {
      $block_description = $this->t('None');
    }

    $options['views_component_block_display_description'] = [
      'category' => 'views_component_block_display',
      'title' => $this->t('Block name'),
      'value' => views_ui_truncate($block_description, 24),
    ];
    $items = $this->getOption('views_component_block_display_items_per_page') ? $this->t('Yes') : $this->t('No');
    $options['views_component_block_display_items_per_page'] = [
      'category' => 'views_component_block_display',
      'title' => $this->t('Items per page'),
      'value' => views_ui_truncate($items, 24),
    ];
    $filters = $this->getOption('views_component_block_display_filters') ? $this->t('Yes') : $this->t('No');
    $options['views_component_block_display_filters'] = [
      'category' => 'views_component_block_display',
      'title' => $this->t('Exposed Filters'),
      'value' => views_ui_truncate($filters, 24),
    ];
    $sorts = $this->getOption('views_component_block_display_sorts') ? $this->t('Yes') : $this->t('No');
    $options['views_component_block_display_sorts'] = [
      'category' => 'views_component_block_display',
      'title' => $this->t('Exposed sorts'),
      'value' => views_ui_truncate($sorts, 24),
    ];
    $allViewModes = $this->getViewModes();
    if ($viewModes = array_filter($this->getOption('views_component_block_display_view_mode'))) {
      $viewModes = implode($this->viewModeLabels($viewModes, ', '));
    }
    else {
      $viewModes = $this->t('None selected');
    }
    $options['views_component_block_display_view_mode'] = [
      'category' => 'views_component_block_display',
      'title' => $this->t('Row Style'),
      'value' => views_ui_truncate($viewModes, 24),
    ];
  }

  /**
   * Adds labels to view modes.
   */
  private function viewModeLabels($viewModes) {
    $allViewModes = $this->getViewModes();
    foreach ($viewModes as $key => $value) {
        $viewModes[$key] = $allViewModes[$value]; 
    }
    return $viewModes;
  }

  /**
   * Provide the default form for setting options.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    switch ($form_state->get('section')) {
      case 'views_component_block_display_description':
        $form['#title'] .= $this->t('Block admin description');
        $form['views_component_block_display_description'] = [
          '#type' => 'textfield',
          '#description' => $this->t('This will appear as the name of this block in administer >> structure >> blocks.'),
          '#default_value' => $this->getOption('views_component_block_display_description'),
        ];
        break;

      case 'views_component_block_display_hide_empty':
        $form['#title'] .= $this->t('Block empty settings');

        $form['views_component_block_display_hide_empty'] = [
          '#title' => $this->t('Hide block if no result/empty text'),
          '#type' => 'checkbox',
          '#description' => $this->t('Hide the block if there is no result and no empty text and no header/footer which is shown on empty result'),
          '#default_value' => $this->getOption('views_component_block_display_hide_empty'),
        ];
        break;

      case 'views_component_block_display_items_per_page':
        $form['#title'] .= $this->t('Allow settings in the block configuration');
        $form['views_component_block_display_items_per_page'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Items per page'),
          '#description' => $this->t('Select to enable users to choose number of items per page.'),
          '#default_value' => $this->getOption('views_component_block_display_items_per_page'),
        ];
        break;

      case 'views_component_block_display_sorts':
        $form['#title'] .= $this->t('Allow settings in the block configuration');
        $form['views_component_block_display_sorts'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Exposed sorts'),
          '#description' => $this->t('Select to enable users to choose from exposed sorts.'),
          '#default_value' => $this->getOption('views_component_block_display_sorts'),
        ];
        break;

      case 'views_component_block_display_filters':
        $form['#title'] .= $this->t('Allow settings in the block configuration');
        $form['views_component_block_display_filters'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Exposed filters'),
          '#description' => $this->t('Select to enable users to choose from exposed filters.'),
          '#default_value' => $this->getOption('views_component_block_display_filters'),
        ];
        break;

      case 'views_component_block_display_view_mode':
        $form['#title'] .= $this->t('Allow settings in the block configuration');
        $form['views_component_block_display_view_mode'] = [
          '#description' => $this->t('Select view modes for users to choose from.'),
          '#type' => 'checkboxes',
          '#default_value' => array_filter($this->getOption('views_component_block_display_view_mode')),
          '#title' => $this->t('Select view modes to allow the user to select from on the component block.'),
          '#options' => $this->getViewModes(),
        ];
        break;
    }
  }

  /**
   * Get view modes.
   */
  private function getViewModes() {
    $entityType = $this->view->getBaseEntityType()->id();
    $viewModesRaw = $this->entityDisplayRepository->getViewModes($entityType);
    $viewModes = [];
    foreach($viewModesRaw as $key => $viewMode) {
      $viewModes[$key] = $viewMode['label'];
    }
    return $viewModes;
  }

  /**
   * Get options.
   */
  private function getAllowOptions() {
    return [
      'items_per_page' => $this->t('Items per page'),
      'filters' => $this->t('Exposed Filters'),
      'view_mode' => $this->t('View Modes'),
    ];
  }

  /**
   * Perform any necessary changes to the form values prior to storage.
   * There is no need for this function to actually store the data.
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    parent::submitOptionsForm($form, $form_state);
    $section = $form_state->get('section');
    switch ($section) {
      case 'views_component_block_display_description':
      case 'views_component_block_display_items_per_page':
      case 'views_component_block_display_filters':
      case 'views_component_block_display_sorts':
      case 'views_component_block_display_view_mode':
      case 'views_component_block_display_hide_empty':
        $this->setOption($section, $form_state->getValue($section));
        break;
    }
  }

  /**
   * Adds the configuration form elements specific to this views block plugin.
   *
   * This method allows block instances to override the views items_per_page.
   *
   * @param \Drupal\views\Plugin\Block\ViewsBlock $block
   *   The ViewsBlock plugin.
   * @param array $form
   *   The form definition array for the block configuration form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The renderable form array representing the entire configuration form.
   *
   * @see \Drupal\views\Plugin\Block\ViewsBlock::blockForm()
   */
  public function blockForm(ViewsBlock $block, array &$form, FormStateInterface $form_state) {
    $items_per_page_settings = $this->getOption('views_component_block_display_items_per_page');
    $filters_settings = $this->getOption('views_component_block_display_filters');
    $view_mode_settings = array_filter($this->getOption('views_component_block_display_view_mode'));

    $block_configuration = $block->getConfiguration();

    if ($view_mode_settings) {
      $form['override']['view_mode'] = [
        '#type' => 'radios',
        '#description' => $this->t('Select a view mode to display.'),
        '#required' => TRUE,
        '#title' => $this->t('View Mode'),
        '#default_value' => $block_configuration['view_mode'],
        '#options' => $this->viewModeLabels($view_mode_settings),
      ];
    }
    if ($items_per_page_settings) {
      $form['override']['items_per_page'] = [
        '#type' => 'select',
        '#title' => $this->t('Items per block'),
        '#options' => [
          'none' => $this->t('@count (default setting)', ['@count' => $this->getPlugin('pager')->getItemsPerPage()]),
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
          6 => 6,
          10 => 10,
          12 => 12,
          20 => 20,
          24 => 24,
          40 => 40,
          48 => 48,
        ],
        '#default_value' => $block_configuration['items_per_page'],
      ];
    }
    if ($filters_settings) {
      $view = $block->getViewExecutable();
      $view->initHandlers();
      $form_state = (new FormState())
        ->setStorage([
          'view' => $view,
          'display' => &$view->display_handler->display,
          'rerender' => TRUE,
        ])
        ->setMethod('get')
        ->setAlwaysProcess()
        ->disableRedirect();
      $form_state->set('rerender', NULL);
      $views_form = \Drupal::formBuilder()->buildForm('\Drupal\views\Form\ViewsExposedForm', $form_state);

      $filters = [];
      foreach($views_form['#info'] as $field) {
        $value = isset($block_configuration['filters'][$field['value']]) ? $block_configuration['filters'][$field['value']] : 'none';
        $filters[$field['value']] = [
          '#type' => $views_form[$field['value']]['#type'],
          '#options' => $views_form[$field['value']]['#options'],
          '#title' => $field['label'],
          '#default_value' => $value,
        ];
      }
      $form['override']['filters'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Filters'),
        'fields' => $filters,
      ];
    }

    return $form;
  }

  /**
   * Handles form validation for the views block configuration form.
   *
   * @param \Drupal\views\Plugin\Block\ViewsBlock $block
   *   The ViewsBlock plugin.
   * @param array $form
   *   The form definition array for the block configuration form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see \Drupal\views\Plugin\Block\ViewsBlock::blockValidate()
   */
  public function blockValidate(ViewsBlock $block, array $form, FormStateInterface $form_state) {
  }

  /**
   * Handles form submission for the views block configuration form.
   *
   * @param \Drupal\views\Plugin\Block\ViewsBlock $block
   *   The ViewsBlock plugin.
   * @param array $form
   *   The form definition array for the full block configuration form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @see \Drupal\views\Plugin\Block\ViewsBlock::blockSubmit()
   */
  public function blockSubmit(ViewsBlock $block, $form, FormStateInterface $form_state) {
    if ($items_per_page = $form_state->getValue(['override', 'items_per_page'])) {
      $block->setConfigurationValue('items_per_page', $items_per_page);
    }
    if ($filters = $form_state->getValue(['override', 'filters'])) {
      $block->setConfigurationValue('filters', $filters['fields']);
    }
    if ($view_mode = $form_state->getValue(['override', 'view_mode'])) {
      $block->setConfigurationValue('view_mode', $view_mode);
    }

    $form_state->unsetValue(['override', 'filters']);
    $form_state->unsetValue(['override', 'view_mode']);
    $form_state->unsetValue(['override', 'items_per_page']);
  }

  /**
   * Allows to change the display settings right before executing the block.
   *
   * @param \Drupal\views\Plugin\Block\ViewsBlock $block
   *   The block plugin for views displays.
   */
  public function preBlockBuild(ViewsBlock $block) {
    $config = $block->getConfiguration();
    if ($config['items_per_page'] !== 'none') {
      $this->view->setItemsPerPage($config['items_per_page']);
    }
    if ($config['filters'] !== 'none') {
      $filters = $this->view->getDisplay()->getOption('filters');
      foreach ($config['filters'] as $field => $value) {
        if (isset($filters[$field]) && $value != 'All') {
          $filters[$field]["value"] = [$value => $value];
        }
      }
      $this->view->display_handler->overrideOption('filters', $filters);
    }
    if ($config['view_mode'] !== 'none') {
      $this->view->storage->set('view_mode', $config['view_mode']);
    }
  }

  /**
   * Block views use exposed widgets only if AJAX is set.
   */
  public function usesExposed() {
    if ($this->ajaxEnabled()) {
      return parent::usesExposed();
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function remove() {
    parent::remove();

    if ($this->entityTypeManager->hasDefinition('block')) {
      $plugin_id = 'views_block:' . $this->view->storage->id() . '-' . $this->display['id'];
      foreach ($this->entityTypeManager->getStorage('block')->loadByProperties(['plugin' => $plugin_id]) as $block) {
        $block->delete();
      }
    }
    if ($this->blockManager instanceof CachedDiscoveryInterface) {
      $this->blockManager->clearCachedDefinitions();
    }
  }

}
