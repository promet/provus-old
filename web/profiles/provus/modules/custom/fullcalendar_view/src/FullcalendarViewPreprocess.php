<?php

namespace Drupal\fullcalendar_view;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Full Calendar.
 */
class FullcalendarViewPreprocess {
  use StringTranslationTrait;

  /**
   * Static index.
   *
   * @var bool
   */
  protected static $viewIndex = 0;

  /**
   * Process the view variable array.
   */
  public function process(array &$variables) {
    /* @var \Drupal\views\ViewExecutable $view */
    $view = $variables['view'];
    // View index.
    $view_index = self::$viewIndex++;
    $style = $view->style_plugin;
    $options = $style->options;
    $fields = $view->field;

    // Get current language.
    $language = \Drupal::languageManager()->getCurrentLanguage();

    // Current user.
    $user = $variables['user'];
    // CSRF token.
    $token = '';
    if (!$user->isAnonymous()) {
      $token = \Drupal::csrfToken()->get($user->id());
    }
    //
    // New event bundle type.
    $event_bundle_type = $options['bundle_type'];
    $entity_type = $view->getBaseEntityType();
    if ($entity_type->id() === 'node') {
      $add_form = 'node/add/' . $event_bundle_type;
    }
    else {
      $entity_links = $entity_type->get('links');
      if (isset($entity_links['add-form'])) {
        $add_form = str_replace('{' . $entity_type->id() . '}', $event_bundle_type, $entity_links['add-form']);
      }
      elseif (isset($entity_links['add-page'])) {
        $add_form = str_replace('{' . $entity_type->id() . '}', $event_bundle_type, $entity_links['add-page']);
      }
    }

    // Can the user add a new event?
    $entity_manager = \Drupal::entityTypeManager();
    $access_handler = $entity_manager->getAccessControlHandler($entity_type->id());
    $dbl_click_to_create = FALSE;
    if ($access_handler->createAccess($event_bundle_type)) {
      $dbl_click_to_create = TRUE;
    }
    // Pass entity type to twig template.
    $variables['entity_id'] = $entity_type->id();
    // Update options for twig.
    $variables['options'] = $options;
    // Hide the create event link from user who doesn't have the permission.
    // or if this feature is turn off.
    $variables['showAddEvent'] = $dbl_click_to_create
    && $options['createEventLink'];
    // Time format.
    $timeFormat = $options['timeFormat'];
    // Field machine name of start date.
    $start_field = $options['start'];
    // Start date field is critical.
    if (empty($start_field)) {
      return;
    }
    // Field machine name of end date.
    $end_field = $options['end'];
    // Field machine name of taxonomy field.
    $tax_field = $options['tax_field'];
    // Field machine name of event duration.
    $duration_field = isset($options['duration']) ? $options['duration'] : NULL;
    // Field machine name of excluding dates field.
    $rrule_field = isset($options['rrule']) ? $options['rrule'] : NULL;

    // Default date of the calendar.
    switch ($options['default_date_source']) {
      case 'now':
        $default_date = date('Y-m-d');
        break;

      case 'fixed':
        $default_date = $options['defaultDate'];
        break;

      default:
        // Don't do anything, we'll set it below.
    }
    // Default view.
    $default_view = isset($options['default_view']) ? $options['default_view'] : 'dayGridMonth';
    // Default Language.
    $default_lang = $options['defaultLanguage'] === 'current_lang' ? $this->fullcalendarViewMapLangcodes($language->getId()) : $options['defaultLanguage'];
    // Color for bundle types.
    $color_content = $options['color_bundle'];
    // Color for taxonomies.
    $color_tax = $options['color_taxonomies'];
    // Date fields.
    $start_field_option = $fields[$start_field]->options;
    $end_field_option = empty($end_field) ? NULL : $fields[$end_field]->options;
    // Custom timezone or user timezone.
    $timezone = !empty($start_field_option['settings']['timezone_override']) ?
    $start_field_option['settings']['timezone_override'] : date_default_timezone_get();
    // Set the first day setting.
    $first_day = isset($options['firstDay']) ? intval($options['firstDay']) : 0;
    // Left side buttons.
    $left_buttons = Xss::filter($options['left_buttons']);
    // Right side buttons.
    $right_buttons = Xss::filter($options['right_buttons']);
    // Event Limits.
    $event_limit = isset($options['eventLimit']) ? intval($options['eventLimit']) : 2;
    $event_limit = $event_limit === 0 ? FALSE : $event_limit;
    // Day and week to be Links.
    $nav_links = ($options['nav_links'] == '1' ? TRUE : FALSE);
    // Event overlap.
    $event_overlap = ($options['allowEventOverlap'] == '1' ? TRUE : FALSE);
    // Allow editing events in place.
    $event_editing = ($options['updateAllowed'] == '1' ? TRUE : FALSE);
    // Days of week.
    $days_of_week = Xss::filter($options['businesshoursfieldset']['businessHoursDaysOfWeek']);
    // Start time.
    $business_hours_start = Xss::filter($options['businesshoursfieldset']['startTimeDaysOfWeek']);
    // End time.
    $business_hours_end = Xss::filter($options['businesshoursfieldset']['endTimeDaysOfWeek']);
    // More event click.
    $more_event_link = isset($options['more_event_link']) ? $options['more_event_link'] : 'day';
    // Update confirmation.
    $updateConfirm = ($options['updateConfirm'] == '1' ? TRUE : FALSE);

    $entries = [];

    if (!empty($start_field)) {
      // Allowed tags for title markup.
      $title_allowed_tags = Xss::getAdminTagList();
      // Remove the 'a' tag from allowed list.
      if (($tag_key = array_search('a', $title_allowed_tags)) !== FALSE) {
        unset($title_allowed_tags[$tag_key]);
      }
      // Timezone conversion service.
      $timezone_service = \Drupal::service('fullcalendar_view.timezone_conversion_service');
      // Save view results into entries array.
      foreach ($view->result as $row) {
        // Set the row_index property used by advancedRender function.
        $view->row_index = $row->index;
        // Result entity of current row.
        $current_entity = $row->_entity;
        // Start field is vital, if it doesn't exist then ignore this entity.
        if (!$current_entity->hasField($start_field)) {
          continue;
        }
        // Entity id.
        $entity_id = $current_entity->id();
        // Entity bundle type.
        $entity_bundle = $current_entity->bundle();
        // Background color based on taxonomy field.
        if (!empty($view->field[$tax_field])) {
          $event_type = $view->style_plugin->getFieldValue($row->index, $tax_field);
          if (!empty($event_type)) {
            if (is_array($event_type)) {
              // The taxonomy field might have multiple values.
              // We just need the first one as the color code.
              $event_type = reset($event_type);
            }
          }
          else {
            $event_type = '';
          }
        }
        // Calendar event start date.
        $start_dates = $current_entity->get($start_field)->getValue();
        // Calendar event end date.
        $end_dates = empty($end_field) || !$current_entity->hasField($end_field) ? '' :
        $current_entity->get($end_field)->getValue();
        // Render all other fields to so they can be used in rewrite.
        foreach ($fields as $name => $field) {
          if (method_exists($field, 'advancedRender')) {
            // Set the row_index property used by advancedRender function.
            $field->view->row_index = $row->index;
            $des = $field->advancedRender($row);
          }
        }
        // Event title.
        if (empty($options['title']) || $options['title'] == 'title') {
          $title = $fields['title']->advancedRender($row);
        }
        elseif (!empty($fields[$options['title']])) {
          $title = $fields[$options['title']]->advancedRender($row);
        }
        else {
          $title = t('Invalid event title');
        }
        $link_url = strstr($title, 'href="');
        if ($link_url) {
          $link_url = substr($link_url, 6);
          $link_url = strstr($link_url, '"', TRUE);
        }
        else {
          $link_url = '';
        }
        // For multiple value field, create a respective calendar entry.
        // For each date value.
        if (!empty($start_dates) && is_array($start_dates)) {
          foreach ($start_dates as $i => $start_date) {
            $entry = [
              'title' => Xss::filter($title, $title_allowed_tags),
              'id' => $row->index . "-$i",
              'eid' => $entity_id,
              'url' => $link_url,
              'des' => isset($des) ? $des : ''
            ];
            // Event duration.
            if (!empty($duration_field) && !empty($fields[$duration_field])) {
              $entry['duration'] = $fields[$duration_field]->advancedRender($row);
            }
            if (!empty($start_date)) {
              $start_date_value = $start_date['value'];
              // Examine the field type.
              if ($start_field_option['type'] === 'timestamp') {
                $start_date_value = intval($start_date_value);
                $start_date_value = date(DATE_ATOM, $start_date_value);
              }
              elseif (strpos($start_field_option['type'], 'datetime') === FALSE
                  && strpos($start_field_option['type'], 'daterange') === FALSE
                  && strpos($start_field_option['type'], 'date_recur') === FALSE) {
                if (empty($variables['fullcalendar_fieldtypes'])) {
                  // This field is not a valid date time field.
                  continue 2;
                }
                else {
                  $valid = FALSE;
                  // Checking supported field types form plugin defintions.
                  foreach ($variables['fullcalendar_fieldtypes'] as $fieldtype) {
                    if (strpos($start_field_option['type'], $fieldtype) === 0) {
                      $valid = TRUE;
                      break;
                    }
                  }
                  if (!$valid) {
                    // This field is not a valid date time field.
                    continue 2;
                  }
                }
              }

              // A user who doesn't have the permission can't edit an event.
              if (!$current_entity->access('update')) {
                $entry['editable'] = FALSE;
              }

              // If we don't yet know the default_date (we're configured to use the
              // date from the first row, and we haven't set it yet), do so now.
              if (!isset($default_date)) {
                // Only use the first 10 digits since we only care about the date.
                $default_date = substr($start_date_value, 0, 10);
              }

              $all_day = (strlen($start_date_value) < 11) ? TRUE : FALSE;

              if ($all_day) {
                $entry['start'] = $start_date_value;
                $entry['allDay'] = TRUE;
              }
              else {
                // Drupal store date time in UTC timezone.
                // So we need to convert it into user timezone.
                $entry['start'] = $timezone_service->utcToLocal($start_date_value, $timezone);
              }
            }
            else {
              // A event must have start date.
              // If not, skip this row.
              continue 2;
            }

            // Deal with the end date in the same way as start date above.
            if (!empty($end_dates[$i])) {
              if ($end_field_option['type'] === 'timestamp') {
                $end_date = $end_dates[$i]['value'];
                $end_date = intval($end_date);
                $end_date = date(DATE_ATOM, $end_date);
              }
              elseif (strpos($end_field_option['type'], 'daterange') !== FALSE
                  || strpos($end_field_option['type'], 'date_recur') !== FALSE) {
                $end_date = $end_dates[$i]['end_value'];
              }
              elseif (strpos($end_field_option['type'], 'datetime') === FALSE) {
                // This field is not a valid date time field.
                $end_date = '';
              }
              else {
                $end_date = $end_dates[$i]['value'];
              }

              if (!empty($end_date)) {
                $all_day = (strlen($end_date) < 11) ? TRUE : FALSE;
                if ($all_day) {
                  $end = new DrupalDateTime($end_date);
                  // The end date is inclusive for a all day event,
                  // which is not what we want. So we need one day offset.
                  $end->modify('+1 day');
                  $entry['end'] = $end->format('Y-m-d');
                  $entry['allDay'] = TRUE;
                }
                else {
                  // Drupal store date time in UTC timezone.
                  // So we need to convert it into user timezone.
                  $entry['end'] = $timezone_service->utcToLocal($end_date, $timezone);
                }
              }
            }
            else {
              // Without end date field, this event can't be resized.
              $entry['eventDurationEditable'] = FALSE;
            }
            // Set the color for this event.
            if (isset($event_type) && isset($color_tax[$event_type])) {
              $entry['backgroundColor'] = $color_tax[$event_type];
            }
            elseif (isset($color_content[$entity_bundle])) {
              $entry['backgroundColor'] = $color_content[$entity_bundle];
            }
            // Recurring event.
            if (!empty($rrule_field)) {
              // Get output of rule from views field rather than entity.
              $rrule = $fields[$rrule_field]->advancedRender($row);
              if (!empty($rrule)) {
                $entry['rrule'] = Xss::filter($rrule);
                // Recurring events are read-only.
                $entry['editable'] = FALSE;
              }
            }
            // Add this event into the array.
            $entries[] = $entry;
          }
        }
      }

      // Remove the row_index property as we don't it anymore.
      unset($view->row_index);
      // Fullcalendar options.
      $calendar_options = [
        'initialView' => $default_view,
        'timeZone' => $timezone,
        'headerToolbar' => [
          'left' => $left_buttons,
          'center' => 'title',
          'right' => $right_buttons,
        ],
        'navLinks' => $nav_links,
        'firstDay' => $first_day,
        'moreLinkClick' => $more_event_link,
        'events' => $entries,
        // Limits the number of events displayed on a day.
        'dayMaxEvents' => $event_limit,
        'eventOverlap' => $event_overlap,
        'editable' => $event_editing,
        'updateConfirm' => $updateConfirm,
        'businessHours' => [
          'daysOfWeek' => $days_of_week,
          'startTime' => $business_hours_start,
          'endTime' => $business_hours_end,
        ],
      ];

      // Dialog options.
      // Other modules can override following options by custom plugin.
      // For reference of JSFrame options see:
      // https://github.com/riversun/JSFrame.js/
      $dialog_options = [
        'left' => 0,
        'top' => 0,
        'width' => 640,
        'height' => 480,
        'movable' => TRUE,
        'resizable' => TRUE,
        'style' => [
          'backgroundColor' => 'rgba(255,255,255,0.9)',
          'font-size' => '1rem'
        ]
      ];

      // Load the fullcalendar js library.
      $variables['#attached']['library'][] = 'fullcalendar_view/fullcalendar';

      $variables['view_index'] = $view_index;
      // View name.
      $variables['view_id'] = $view->storage->id();
      // Display name.
      $variables['display_id'] = $view->current_display;
      // Pass data to js file.
      $variables['#attached']['drupalSettings']['fullCalendarView'][$view_index] = [
        // Allow client to select language, if it is 1.
        'languageSelector' => $options['languageSelector'],
        // Event update confirmation pop-up dialog.
        // If it is 1, a confirmation dialog will pop-up after dragging and dropping an event.
        'updateConfirm' => $options['updateConfirm'],
        // The bundle (content) type of a new event.
        'eventBundleType' => $event_bundle_type,
        // The machine name of start date field.
        'startField' => $start_field,
        // The machine name of end date field.
        'endField' => $end_field,
        // Allow to create a new event by double clicking.
        'dblClickToCreate' => $dbl_click_to_create,
        // Entity type.
        'entityType' => $entity_type->id(),
        // URL of the new event form.
        'addForm' => isset($add_form) ? $add_form : '',
        // CSRF token.
        'token' => $token,
        // Show an event details in a new window (tab).
        'openEntityInNewTab' => $options['openEntityInNewTab'],
        // The options of the Fullcalendar object.
        'calendar_options' => json_encode($calendar_options),
        // The options of the pop-up dialog object.
        'dialog_options' => json_encode($dialog_options),
      ];
    }
  }

  /**
   * Map Drupal language codes to those used by FullCalendar.
   *
   * @param string $langcode
   *   Drupal language code.
   *
   * @return string
   *   Returns the mapped langcode.
   */
  private function fullcalendarViewMapLangcodes($langcode) {
    switch ($langcode) {
      case "en-x-simple":
        return "en";

      case "pt-pt":
        return "pt";

      case "zh-hans":
        return "zh-cn";

      case "zh-hant":
        return "zh-tw";

      default:
        return $langcode;

    }
  }

}
