<?php

namespace Drupal\fullcalendar_view\Plugin\views\style;

use Drupal\field\Entity\FieldStorageConfig;
use Drupal\fullcalendar_view\TaxonomyColor;
use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Style plugin to render content for FullCalendar.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "fullcalendar_view_display",
 *   title = @Translation("Full Calendar Display"),
 *   help = @Translation("Render contents in Full Calendar view."),
 *   theme = "views_view_fullcalendar",
 *   display_types = { "normal" }
 * )
 */
class FullCalendarDisplay extends StylePluginBase {

  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * @var bool
   */
  protected $usesFields = TRUE;
  /**
   * Taxonomy Colours.
   *
   * @var bool
   */
  protected $taxonomyColorService;

  /**
   * Constructs a PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\fullcalendar_view\TaxonomyColor $taxonomyColorService
   *   The Taxonomy Color Service object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TaxonomyColor $taxonomyColorService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->taxonomyColorService = $taxonomyColorService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('fullcalendar_view.taxonomy_color'));
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['default_date_source'] = ['default' => 'now'];
    $options['defaultDate'] = ['default' => ''];
    $options['start'] = ['default' => ''];
    $options['end'] = ['default' => ''];
    $options['title'] = ['default' => ''];
    $options['duration'] = ['default' => ''];
    $options['rrule'] = ['default' => ''];
    $options['bundle_type'] = ['default' => ''];
    $options['tax_field'] = ['default' => ''];
    $options['color_bundle'] = ['default' => []];
    $options['color_taxonomies'] = ['default' => []];
    $options['vocabularies'] = ['default' => ''];
    $options['right_buttons'] = [
      'default' => 'dayGridMonth,timeGridWeek,timeGridDay,listYear',
    ];
    $options['left_buttons'] = [
      'default' => 'prev,next today',
    ];
    $options['more_event_link'] = [
      'default' => 'day',
    ];
    $options['default_view'] = ['default' => 'dayGridMonth'];
    $options['nav_links'] = ['default' => 1];
    $options['timeFormat'] = ['default' => 'hh:mm a'];
    $options['defaultLanguage'] = ['default' => 'en'];
    $options['languageSelector'] = ['default' => 0];
    $options['allowEventOverlap'] = ['default' => 1];
    $options['updateAllowed'] = ['default' => 1];
    $options['updateConfirm'] = ['default' => 1];
    $options['createEventLink'] = ['default' => 0];
    $options['openEntityInNewTab'] = ['default' => 1];
    $options['eventLimit'] = ['default' => 2];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    // Remove the grouping setting.
    if (isset($form['grouping'])) {
      unset($form['grouping']);
    }
    $form['default_date_source'] = [
      '#type' => 'radios',
      '#options' => [
        'now' => $this->t('Current date'),
        'first' => $this->t('Date of first view result'),
        'fixed' => $this->t('Fixed value'),
      ],
      '#title' => $this->t('Default date source'),
      '#default_value' => (isset($this->options['default_date_source'])) ? $this->options['default_date_source'] : '',
      '#description' => $this->t('Source of the initial date displayed when the calendar first loads.'),
    ];
    // Default date of the calendar.
    $form['defaultDate'] = [
      '#type' => 'date',
      '#title' => $this->t('Default date'),
      '#default_value' => (isset($this->options['defaultDate'])) ? $this->options['defaultDate'] : '',
      '#description' => $this->t('Fixed initial date displayed when the calendar first loads.'),
      '#states' => [
        'visible' => [
          [':input[name="style_options[default_date_source]"]' => ['value' => 'fixed']],
        ],
      ],
    ];
    // All selected fields.
    $field_names = $this->displayHandler->getFieldLabels();
    $entity_type = $this->view->getBaseEntityType()->id();
    // Field name of start date.
    $form['start'] = [
      '#title' => $this->t('Start Date Field'),
      '#type' => 'select',
      '#options' => $field_names,
      '#default_value' => (!empty($this->options['start'])) ? $this->options['start'] : '',
    ];
    // Field name of end date.
    $form['end'] = [
      '#title' => $this->t('End Date Field'),
      '#type' => 'select',
      '#options' => $field_names,
      '#empty_value' => '',
      '#default_value' => (!empty($this->options['end'])) ? $this->options['end'] : '',
    ];
    // Field name of title.
    $form['title'] = [
      '#title' => $this->t('Title Field'),
      '#type' => 'select',
      '#options' => $field_names,
      '#default_value' => (!empty($this->options['title'])) ? $this->options['title'] : '',
    ];
    // Display settings.
    $form['display'] = [
      '#type' => 'details',
      '#title' => $this->t('Display'),
      '#description' => $this->t('Calendar display settings.'),
    ];
    // Right side buttons.
    $form['right_buttons'] = [
      '#type' => 'textfield',
      '#fieldset' => 'display',
      '#default_value' => (empty($this->options['right_buttons'])) ? [] : $this->options['right_buttons'],
      '#title' => $this->t('Right side buttons'),
      '#description' => $this->t('Right side buttons. Buttons are seperated by commas or space. See the %fullcalendar_doc for available buttons.',
          [
            '%fullcalendar_doc' => Link::fromTextAndUrl($this->t('Fullcalendar documentation'), Url::fromUri('https://fullcalendar.io/docs/header', ['attributes' => ['target' => '_blank']]))->toString(),
          ]),
    ];
    // Left side buttons.
    $form['left_buttons'] = [
      '#type' => 'textfield',
      '#fieldset' => 'display',
      '#default_value' => (empty($this->options['left_buttons'])) ? [] : $this->options['left_buttons'],
      '#title' => $this->t('Left side buttons'),
      '#description' => $this->t('Left side buttons. Buttons are seperated by commas or space. See the %fullcalendar_doc for available buttons.',
          [
            '%fullcalendar_doc' => Link::fromTextAndUrl($this->t('Fullcalendar documentation'), Url::fromUri('https://fullcalendar.io/docs/header', ['attributes' => ['target' => '_blank']]))->toString(),
          ]),
    ];

    $form['eventLimit'] = [
      '#type' => 'textfield',
      '#fieldset' => 'display',
      '#default_value' => (isset($this->options['eventLimit'])) ? $this->options['eventLimit'] : 2,
      '#title' => $this->t('Event Limit'),
      '#description' => $this->t('The maximum number of events to show before showing the more events link. Set 0 for no limit.')
    ];

    $form['more_event_link'] = [
      '#type' => 'select',
      '#fieldset' => 'display',
      '#options' => [
        'day' => $this->t('day'),
        'week' => $this->t('week'),
        'popover' => $this->t('popover'),
      ],
      '#default_value' => (empty($this->options['more_event_link'])) ? 'day' : $this->options['more_event_link'],
      '#title' => $this->t('More Events Link Destination'),
    ];
    // Default view.
    // Todo: filter out disabled view from options.
    $form['default_view'] = [
      '#type' => 'select',
      '#fieldset' => 'display',
      '#options' => [
        'dayGridMonth' => $this->t('Month'),
        'timeGridWeek' => $this->t('Week'),
        'timeGridDay' => $this->t('Day'),
        'listWeek' => $this->t('List Week'),
        'listMonth' => $this->t('List Month'),
        'listYear' => $this->t('List Year'),
      ],
      '#default_value' => (empty($this->options['default_view'])) ? 'month' : $this->options['default_view'],
      '#title' => $this->t('Default view'),
    ];
    // First day.
    $form['firstDay'] = [
      '#type' => 'select',
      '#fieldset' => 'display',
      '#options' => [
        '0' => $this->t('Sunday'),
        '1' => $this->t('Monday'),
        '2' => $this->t('Tuesday'),
        '3' => $this->t('Wednesday'),
        '4' => $this->t('Thursday'),
        '5' => $this->t('Friday'),
        '6' => $this->t('Saturday'),
      ],
      '#default_value' => (empty($this->options['firstDay'])) ? '0' : $this->options['firstDay'],
      '#title' => $this->t('First Day'),
    ];
    // Nav Links.
    $form['nav_links'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (!isset($this->options['nav_links'])) ? 1 : $this->options['nav_links'],
      '#title' => $this->t('Day/Week are links'),
      '#description' => $this->t('If this option is selected, day/week names will be linked to navigation views.'),
    ];
    // Time format.
    $form['timeFormat'] = [
      '#fieldset' => 'display',
      '#type' => 'textfield',
      '#title' => $this->t('Time Format settings for month view'),
      '#default_value' => (isset($this->options['timeFormat'])) ? $this->options['timeFormat'] : 'hh:mm a',
      '#description' => $this->t('See %momentjs_doc for available formatting options. <br />Leave it blank to use the default format "hh:mm a".', [
        '%momentjs_doc' => Link::fromTextAndUrl($this->t('MomentJS’s formatting characters'), Url::fromUri('http://momentjs.com/docs/#/displaying/format/', ['attributes' => ['target' => '_blank']]))->toString(),
      ]),
      '#size' => 20,
    ];
    // Allow/disallow event overlap.
    $form['allowEventOverlap'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (!isset($this->options['allowEventOverlap'])) ? 1 : $this->options['allowEventOverlap'],
      '#title' => $this->t('Allow calendar events to overlap'),
      '#description' => $this->t('If this option is selected, calendar events are allowed to overlap (default).'),
    ];
    // Allow/disallow event editing.
    $form['updateAllowed'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (!isset($this->options['updateAllowed'])) ? 1 : $this->options['updateAllowed'],
      '#title' => $this->t('Allow event editing.'),
      '#description' => $this->t('If this option is selected, editing by dragging and dropping an event will be enabled.'),
    ];
    // Event update JS confirmation dialog.
    $form['updateConfirm'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (!isset($this->options['updateConfirm'])) ? 1 : $this->options['updateConfirm'],
      '#title' => $this->t('Event update confirmation pop-up dialog.'),
      '#description' => $this->t('If this option is selected, a confirmation dialog will pop-up after dragging and dropping an event.'),
    ];
    // Language and Localization.
    $locale = [
      'current_lang' => $this->t('Current active language on the page'),
      'en' => 'English',
      'af' => 'Afrikaans',
      'ar-dz' => 'Arabic - Algeria',
      'ar-kw' => 'Arabic - Kuwait',
      'ar-ly' => 'Arabic - Libya',
      'ar-ma' => 'Arabic - Morocco',
      'ar-sa' => 'Arabic - Saudi Arabia',
      'ar-tn' => 'Arabic - Tunisia',
      'ar' => 'Arabic',
      'bg' => 'Bulgarian',
      'ca' => 'Catalan',
      'cs' => 'Czech',
      'da' => 'Danish',
      'de-at' => 'German - Austria',
      'de-ch' => 'German - Switzerland',
      'de' => 'German',
      'el' => 'Greek',
      'en-au' => 'English - Australia',
      'en-ca' => 'English - Canada',
      'en-gb' => 'English - United Kingdom',
      'en-ie' => 'English - Ireland',
      'en-nz' => 'English - New Zealand',
      'es-do' => 'Spanish - Dominican Republic',
      'es-us' => 'Spanish - United States',
      'es' => 'Spanish',
      'et' => 'Estonian',
      'eu' => 'Basque',
      'fa' => 'Farsi',
      'fi' => 'Finnish',
      'fr-ca' => 'French - Canada',
      'fr-ch' => 'French - Switzerland',
      'fr' => 'French',
      'gl' => 'Galician',
      'he' => 'Hebrew',
      'hi' => 'Hindi',
      'hr' => 'Croatian',
      'hu' => 'Hungarian',
      'id' => 'Indonesian',
      'is' => 'Icelandic',
      'it' => 'Italian',
      'ja' => 'Japanese',
      'kk' => 'Kannada',
      'ko' => 'Korean',
      'lb' => 'Lebanon',
      'lt' => 'Lithuanian',
      'lv' => 'Latvian',
      'mk' => 'FYRO Macedonian',
      'ms-my' => 'Malay - Malaysia',
      'ms' => 'Malay',
      'nb' => 'Norwegian (Bokmål) - Norway',
      'nl-be' => 'Dutch - Belgium',
      'nl' => 'Dutch',
      'nn' => 'Norwegian',
      'pl' => 'Polish',
      'pt-br' => 'Portuguese - Brazil',
      'pt' => 'Portuguese',
      'ro' => 'Romanian',
      'ru' => 'Russian',
      'sk' => 'Slovak',
      'sl' => 'Slovenian',
      'sq' => 'Albanian',
      'sr-cyrl' => 'Serbian - Cyrillic',
      'sr' => 'Serbian',
      'sv' => 'Swedish',
      'th' => 'Thai',
      'tr' => 'Turkish',
      'uk' => 'Ukrainian',
      'vi' => 'Vietnamese',
      'zh-cn' => 'Chinese - China',
      'zh-tw' => 'Chinese - Taiwan',
    ];
    // Default Language.
    $form['defaultLanguage'] = [
      '#title' => $this->t('Default Language'),
      '#fieldset' => 'display',
      '#type' => 'select',
      '#options' => $locale,
      '#default_value' => (!empty($this->options['defaultLanguage'])) ? $this->options['defaultLanguage'] : 'en',
    ];
    // Language Selector Switch.
    $form['languageSelector'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (empty($this->options['languageSelector'])) ? 0 : $this->options['languageSelector'],
      '#title' => $this->t('Allow client to select language.'),
    ];
    // Display settings.
    $form['businesshoursfieldset'] = [
      '#type' => 'details',
      '#title' => $this->t('Business Hours'),
      '#description' => $this->t('Calendar business hour settings. See %fullcalendar_doc for available settings.',
          [
            '%fullcalendar_doc' => Link::fromTextAndUrl($this->t('Fullcalendar documentation'), Url::fromUri('https://fullcalendar.io/docs/business-hours', ['attributes' => ['target' => '_blank']]))->toString(),
          ]),
      '#fieldset' => 'display',
    ];
    $form['businesshoursfieldset']['businessHours'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'businesshoursfieldset',
      '#default_value' => (empty($this->options['businesshoursfieldset']['businessHours'])) ? 0 : $this->options['businesshoursfieldset']['businessHours'],
      '#title' => $this->t('Only show events inside of business hours'),
      '#description' => $this->t('If this option is selected, the calendar hightlight the business hours on the week and day view'),
    ];
    // Days of week business hours.
    $form['businesshoursfieldset']['businessHoursDaysOfWeek'] = [
      '#type' => 'textfield',
      '#fieldset' => 'businesshoursfieldset',
      '#default_value' => (empty($this->options['businesshoursfieldset']['businessHoursDaysOfWeek'])) ? [] : $this->options['businesshoursfieldset']['businessHoursDaysOfWeek'],
      '#title' => $this->t('Days of week for Business hours'),
    ];
    // Start time for days of week.
    $form['businesshoursfieldset']['startTimeDaysOfWeek'] = [
      '#type' => 'textfield',
      '#fieldset' => 'businesshoursfieldset',
      '#default_value' => (empty($this->options['businesshoursfieldset']['startTimeDaysOfWeek'])) ? [] : $this->options['businesshoursfieldset']['startTimeDaysOfWeek'],
      '#title' => $this->t('Start time for Days of week for Business hours'),
    ];
    // Start time for days of week.
    $form['businesshoursfieldset']['endTimeDaysOfWeek'] = [
      '#type' => 'textfield',
      '#fieldset' => 'businesshoursfieldset',
      '#default_value' => (empty($this->options['businesshoursfieldset']['endTimeDaysOfWeek'])) ? [] : $this->options['businesshoursfieldset']['endTimeDaysOfWeek'],
      '#title' => $this->t('End time for Days of week for Business hours'),
    ];
    // Open details in new window.
    $form['openEntityInNewTab'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => !isset($this->options['openEntityInNewTab']) ? 1 : $this->options['openEntityInNewTab'],
      '#title' => $this->t('Open entities (calendar items) into new tabs'),
    ];
    // Create new event link.
    $form['createEventLink'] = [
      '#type' => 'checkbox',
      '#fieldset' => 'display',
      '#default_value' => (empty($this->options['createEventLink'])) ? 0 : $this->options['createEventLink'],
      '#title' => $this->t('Create a new event via the Off-Canvas dialog.'),
      '#description' => $this->t('If this option is selected, there will be an Add Event link below the calendar that provides the ability to create an event In-Place.'),
    ];
    // Legend colors.
    $form['colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Legend Colors'),
      '#description' => $this->t('Set color value of legends for each content type or each taxonomy.'),
    ];

    $moduleHandler = \Drupal::service('module_handler');
    if ($moduleHandler->moduleExists('taxonomy')) {
      // All vocabularies.
      $cabNames = taxonomy_vocabulary_get_names();
      // Taxonomy reference field.
      $tax_fields = [];
      // Find out all taxonomy reference fields of this View.
      foreach ($field_names as $field_name => $lable) {
        $field_conf = FieldStorageConfig::loadByName($entity_type, $field_name) ?: FieldStorageConfig::loadByName('user', $field_name);
        if (empty($field_conf)) {
          continue;
        }
        if ($field_conf->getType() == 'entity_reference') {
          $tax_fields[$field_name] = $lable;
        }
      }
      // Field name of event taxonomy.
      $form['tax_field'] = [
        '#title' => $this->t('Event Taxonomy Field'),
        '#description' => $this->t('In order to specify colors for event taxonomies, you must select a taxonomy reference field for the View.'),
        '#type' => 'select',
        '#options' => $tax_fields,
        '#empty_value' => '',
        '#disabled' => empty($tax_fields),
        '#fieldset' => 'colors',
        '#default_value' => (!empty($this->options['tax_field'])) ? $this->options['tax_field'] : '',
      ];
      // Color for vocabularies.
      $form['vocabularies'] = [
        '#title' => $this->t('Vocabularies'),
        '#type' => 'select',
        '#options' => $cabNames,
        '#empty_value' => '',
        '#fieldset' => 'colors',
        '#description' => $this->t('Specify which vocabulary is using for calendar event color. If the vocabulary selected is not the one that the taxonomy field belonging to, the color setting would be ignored.'),
        '#default_value' => (!empty($this->options['vocabularies'])) ? $this->options['vocabularies'] : '',
        '#states' => [
          // Only show this field when the 'tax_field' is selected.
          'invisible' => [
            [':input[name="style_options[tax_field]"]' => ['value' => '']],
          ],
        ],
        '#ajax' => [
          'callback' => 'Drupal\fullcalendar_view\Plugin\views\style\FullCalendarDisplay::taxonomyColorCallback',
          'event' => 'change',
          'wrapper' => 'color-taxonomies-div',
          'progress' => [
            'type' => 'throbber',
            'message' => $this->t('Verifying entry...'),
          ],
        ],
      ];
    }

    if (!isset($form_state->getUserInput()['style_options'])) {
      // Taxonomy color input boxes.
      $form['color_taxonomies'] = $this->taxonomyColorService->colorInputBoxs($this->options['vocabularies'], $this->options['color_taxonomies']);
    }
    // Content type colors.
    $form['color_bundle'] = [
      '#type' => 'details',
      '#title' => $this->t('Colors for Bundle Types'),
      '#description' => $this->t('Specify colors for each bundle type. If taxonomy color is specified, this settings would be ignored.'),
      '#fieldset' => 'colors',
    ];
    // All bundle types.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($entity_type);
    // Options list.
    $bundlesList = [];
    foreach ($bundles as $id => $bundle) {
      $label = $bundle['label'];
      $bundlesList[$id] = $label;
      // Content type colors.
      $form['color_bundle'][$id] = [
        '#title' => $label,
        '#default_value' => isset($this->options['color_bundle'][$id]) ? $this->options['color_bundle'][$id] : '#337ab7',
        '#type' => 'color',
      ];
    }

    // Recurring event.
    $form['recurring'] = [
      '#type' => 'details',
      '#title' => $this->t('Recurring event settings'),
        // '#description' =>  $this->t('Settings for recurring event.'),.
    ];
    // Field name of rrules.
    $form['rrule'] = [
      '#title' => $this->t('RRule Field for recurring events.'),
      '#description' => $this->t('You can generate an valid rrule string via <a href=":tool-url" target="_blank">the online toole</a><br><a href=":doc-url" target="_blank">See the documentation</a> for more about RRule.',
          [
            ':tool-url' => 'https://jakubroztocil.github.io/rrule/',
            ':doc-url' => 'https://github.com/jakubroztocil/rrule'
          ]),
      '#type' => 'select',
      '#empty_value' => '',
      '#fieldset' => 'recurring',
      '#options' => $field_names,
      '#default_value' => (!empty($this->options['rrule'])) ? $this->options['rrule'] : '',
    ];
    // Field name of rrules.
    $form['duration'] = [
      '#fieldset' => 'recurring',
      '#title' => $this->t('Event duration field.'),
      '#description' => $this->t('For specifying the end time of each recurring event instance. The field value should be a string in the format hh:mm:ss.sss, hh:mm:sss or hh:mm. For example, "05:00" signifies 5 hours.'),
      '#type' => 'select',
      '#empty_value' => '',
      '#options' => $field_names,
      '#empty_value' => '',
      '#default_value' => (!empty($this->options['duration'])) ? $this->options['duration'] : '',
      '#states' => [
        // Only show this field when the 'rrule' is specified.
        'invisible' => [
          [':input[name="style_options[rrule]"]' => ['value' => '']],
        ],
      ],
    ];

    // New event bundle type.
    $form['bundle_type'] = [
      '#title' => $this->t('Event bundle (Content) type'),
      '#description' => $this->t('The bundle (content) type of a new event. Once this is set, you can create a new event by double clicking a calendar entry.'),
      '#type' => 'select',
      '#options' => $bundlesList,
      '#default_value' => (!empty($this->options['bundle_type'])) ? $this->options['bundle_type'] : '',
    ];
    // Extra CSS classes.
    $form['classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CSS classes'),
      '#default_value' => (isset($this->options['classes'])) ? $this->options['classes'] : '',
      '#description' => $this->t('CSS classes for further customization of this view.'),
    ];
  }

  /**
   * Options form submit handle function.
   *
   * @see \Drupal\views\Plugin\views\PluginBase::submitOptionsForm()
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    $options = &$form_state->getValue('style_options');
    $input_value = $form_state->getUserInput();
    $input_colors = isset($input_value['style_options']['color_taxonomies']) ? $input_value['style_options']['color_taxonomies'] : [];
    // Save the input of colors.
    foreach ($input_colors as $id => $color) {
      if (!empty($color)) {
        $options['color_taxonomies'][$id] = $color;
      }
    }

    // Sanitize user input.
    $options['timeFormat'] = Xss::filter($options['timeFormat']);

    parent::submitOptionsForm($form, $form_state);
  }

  /**
   * Taxonomy colors Ajax callback function.
   */
  public static function taxonomyColorCallback(array &$form, FormStateInterface $form_state) {
    $options = $form_state->getValue('style_options');
    $vid = $options['vocabularies'];
    $taxonomy_color_service = \Drupal::service('fullcalendar_view.taxonomy_color');

    if (isset($options['color_taxonomies'])) {
      $defaultValues = $options['color_taxonomies'];
    }
    else {
      $defaultValues = [];
    }
    // Taxonomy color boxes.
    $form['color_taxonomies'] = $taxonomy_color_service->colorInputBoxs($vid, $defaultValues, TRUE);

    return $form['color_taxonomies'];
  }

  /**
   * Should the output of the style plugin be rendered even if it's a empty view.
   */
  public function evenEmpty() {
    // An empty calendar should be displayed if there are no calendar items.
    return TRUE;
  }

}
