
CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Features
 * API


INTRODUCTION
------------

This is a View plugin module that provides a calendar view format powered by FullCalendar JavaScript library

 * For a full description of the module visit:
   https://www.drupal.org/project/fullcalendar_view

 * To submit bug reports and feature suggestions, or to track changes visit:
   https://www.drupal.org/project/issues/fullcalendar_view


REQUIREMENTS
------------

This module requires no modules outside of Drupal core. If you don't need to host any required library locally, you can skip this section. Fullcalendar View module will load all of them for you from CDN.
If you want to host third-party libraries locally, You need to download the files for each library into following folders.

 * Fullcalendar JS
 
/libraries/fullcalendar/core/main.min.js

/libraries/fullcalendar/core/locales-all.min.js

/libraries/fullcalendar/moment/main.min.js

/libraries/fullcalendar/daygrid/main.min.js

/libraries/fullcalendar/interaction/main.min.js

/libraries/fullcalendar/timegrid/main.min.js

/libraries/fullcalendar/list/main.min.js

/libraries/fullcalendar/rrule/main.min.js

/libraries/fullcalendar/core/main.min.css

/libraries/fullcalendar/daygrid/main.min.css

/libraries/fullcalendar/timegrid/main.min.css

/libraries/fullcalendar/list/main.min.css


 * Moment.js
 
/libraries/moment/2.26.0/moment.min.js

* RRule JS

/libraries/rrule/2.6.4/rrule.min.js

* JsFrame.js

/libraries/JSFrame/1.5.16/jsframe.min.js


INSTALLATION
------------

 * Install the Full Calendar View Plugin module as you would normally install a
   contributed Drupal module. Visit https://www.drupal.org/node/1897420 for
   further information.


CONFIGURATION
-------------

    1. Navigate to Administration > Extend and enable the module.
    2. Navigate to Administration > Structure > views and create a view.
    3. Select the Full Calendar Display as the view format.
    4. Choose the fields for Title, Start date, End date (optional). The description field (showing in the pop-up dialog) is the last field of the fields list.
    5. Format settings (Default Date, field of start date, field of end date).
    6. Other view settings (optional), such as filter criteria, pager.

To configure a recurring event:

    1. Add a long plain text field to present the RRULE string for recurring logic of an event. For example,

    DTSTART:20200302T100000Z EXDATE:20200309T100000Z EXDATE:20200311T100000Z RRULE:FREQ=WEEKLY;UNTIL=20200331T120000Z;INTERVAL=1;WKST=MO;BYDAY=MO,WE

    The rule above means a recurring event start from 2020-03-02 to 2020-03-31 and occurs every Monday and Wednesday except 2020-03-09 and 2020-03-11. The RRULE supports excluded time as well.
    More information about the RRULE, please see https://github.com/jakubroztocil/rrule.
    
    2. Then go the view setting page to specify the field as the RRUle field which is under Format:Full Calendar Display->Settings->Recurring Event Settings->RRule Field for recurring events.
    
    3. The duration field is optional, which specifying the end time of each recurring event instance. The field value should be a string in the format hh:mm:ss.sss, hh:mm:sss or hh:mm. For example, "05:00" signifies 5 hours. 

FEATURES:
-------------

 * Day, week, month view
 * Creating a new event by double clicking
 * Event colors based on taxonomy or content type
 * In-Place editing
 * Event drag and drop
 * Resize event (Only available for all day event)
 * Recurring (Repeated) event
 * Supports Multilingual
 * Off-Canvas editing
 * Popup Tooltip for event's description
 
API:
-------------

 * Service
 
 
     * View preprocess service (Drupal\fullcalendar_view\FullcalendarViewPreprocess)
     
         This service implements the business logic of the calendar. Other module can decorate or override this service to define their own business logic.
     
     
     * Timezone conversion service (Drupal\fullcalendar_view\TimezoneService)
     
     
     * Taxonomy color service (Drupal\fullcalendar_view\TaxonomyColor)
     
     
 
 * Plugin type
 
 
     * View processor plugin (FullcalendarViewProcessor)
     
         This plugin type provides the capability to alter the view preprocess working with other modules.
         
         
         
 * Drupal JavaScript Settings (drupalSettings)
 
 
     * languageSelector
     
         Allow client to select language, if it is 1.
         
     * updateConfirm 
     
         Event update confirmation pop-up dialog. If it is 1, a confirmation dialog will pop-up after dragging and dropping an event.
         
     * dialogWindow
     
         Open event links in dialog window. If it is 1, event links in the calendar will open in a dialog window.
         
     * eventBundleType
     
         The bundle (content) type of a new event.
         
     * startField
     
         The machine name of start date field.
         
     * endField
     
         The machine name of end date field.
         
     * dblClickToCreate
     
         Allow to create a new event by double clicking, if it is 1.
         
     * entityType
     
         Entity type.
         
     * addForm
     
         URL of the new event form.
       
     * token
     
         CSRF token.
         
     * openEntityInNewTab
     
         Show an event details in a new window (tab).
         
     * calendar_options
     
         The options of the Fullcalendar object.
         
     * dialog_options
     
         The options of the pop-up dialog object.
         

