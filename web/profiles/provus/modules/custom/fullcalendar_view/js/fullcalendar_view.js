/**
 * @file
 * Fullcalendar View plugin JavaScript file.
 */

(function($, Drupal) {
  var initialLocaleCode = 'en';
  // Dialog index.
  var dialogIndex = 0;
  // Dialog objects.
  var dialogs = [];
  // Date entry clicked.
  var slotDate;

  /**
   * Event render handler
   */
  function eventRender (info) {
    // Event title html markup.
    let eventTitleEle = info.el.getElementsByClassName('fc-title');
    if(eventTitleEle.length > 0) {
      eventTitleEle[0].innerHTML = info.event.title;
    }
    // Event list tile html markup.
    let eventListTitleEle = info.el.getElementsByClassName('fc-list-item-title');
    if(eventListTitleEle.length > 0) {
      if (info.event.url) {
        eventListTitleEle[0].innerHTML = '<a href="' + info.event.url + '">' + info.event.title + '</a>';
      }
      else {
        eventListTitleEle[0].innerHTML = info.event.title;
      }
    }
  }
  /**
   * Event resize handler
   */
  function eventResize(info) {
    const end = info.event.end;
    const start = info.event.start;
    let strEnd = '';
    let strStart = '';
    let viewIndex = parseInt(this.el.getAttribute("calendar-view-index"));
    let viewSettings = drupalSettings.fullCalendarView[viewIndex];
    const formatSettings = {
        month: '2-digit',
        year: 'numeric',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'UTC',
        locale: 'sv-SE'
      };
    // define the end date string in 'YYYY-MM-DD' format.
    if (end) {
      // The end date of an all-day event is exclusive.
      // For example, the end of 2018-09-03
      // will appear to 2018-09-02 in the calendar.
      // So we need one day subtract
      // to ensure the day stored in Drupal
      // is the same as when it appears in
      // the calendar.
      if (end.getHours() == 0 && end.getMinutes() == 0 && end.getSeconds() == 0) {
        end.setDate(end.getDate() - 1);
      }
      // String of the end date.
      strEnd = FullCalendar.formatDate(end, formatSettings);
    }
    // define the start date string in 'YYYY-MM-DD' format.
    if (start) {
      strStart = FullCalendar.formatDate(start, formatSettings);
    }
    const title = info.event.title.replace(/(<([^>]+)>)/ig,"");;
    const msg = Drupal.t('@title end is now @event_end. Do you want to save this change?', {
      '@title': title,
      '@event_end': strEnd
    });

    if (
        viewSettings.updateConfirm === 1 &&
        !confirm(msg)
    ) {
      info.revert();
    }
    else {
      /**
       * Perform ajax call for event update in database.
       */
      jQuery
        .post(
          drupalSettings.path.baseUrl +
            "fullcalendar-view-event-update",
          {
            eid: info.event.extendedProps.eid,
            entity_type: viewSettings.entityType,
            start: strStart,
            end: strEnd,
            start_field: viewSettings.startField,
            end_field: viewSettings.endField,
            token: viewSettings.token
          }
        )
        .done(function(data) {
          if (data !== '1') {
            alert("Error: " + data);
            info.revert();
          }
        });
    }
  }

  // Day entry click call back function.
  function dayClickCallback(info) {
    slotDate = info.dateStr;
  }

  // Event click call back function.
  function eventClick(info) {
    slotDate = null;
    info.jsEvent.preventDefault();
    let thisEvent = info.event;
    let viewIndex = parseInt(this.el.getAttribute("calendar-view-index"));
    let viewSettings = drupalSettings.fullCalendarView[viewIndex];
    let des = thisEvent.extendedProps.des;
    // Show the event detail in a pop up dialog.
    if (viewSettings.dialogWindow) {
      let dataDialogOptionsDetails = {};
      if ( des == '') {
        return false;
      }

      const jsFrame = new JSFrame({
        parentElement:info.el,//Set the parent element to which the jsFrame is attached here
      });
      // Position offset.
      let posOffset = dialogIndex * 20;
      // Dialog options.
      let dialogOptions = JSON.parse(viewSettings.dialog_options);
      dialogOptions.left += posOffset + info.jsEvent.pageX;
      dialogOptions.top += posOffset + info.jsEvent.pageY;
      dialogOptions.title = dialogOptions.title ? dialogOptions.title : thisEvent.title.replace(/(<([^>]+)>)/ig,"");
      dialogOptions.html = des;
      //Create window
      dialogs[dialogIndex] = jsFrame.create(dialogOptions);

      dialogs[dialogIndex].show();
      dialogIndex++;

      return false;
    }
    // Open a new window to show the details of the event.
    if (thisEvent.url) {
      if (viewSettings.openEntityInNewTab) {
        // Open a new window to show the details of the event.
       window.open(thisEvent.url);
       return false;
      }
      else {
        // Open in same window
        window.location.href = thisEvent.url;
        return false;
      }
    }

    return false;
  }

  // Event drop call back function.
  function eventDrop(info) {
    const end = info.event.end;
    const start = info.event.start;
    let strEnd = '';
    let strStart = '';
    let viewIndex = parseInt(this.el.getAttribute("calendar-view-index"));
    let viewSettings = drupalSettings.fullCalendarView[viewIndex];
    const formatSettings = {
        month: '2-digit',
        year: 'numeric',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'UTC',
        locale: 'sv-SE'
      };
    // define the end date string in 'YYYY-MM-DD' format.
    if (end) {
      // The end date of an all-day event is exclusive.
      // For example, the end of 2018-09-03
      // will appear to 2018-09-02 in the calendar.
      // So we need one day subtract
      // to ensure the day stored in Drupal
      // is the same as when it appears in
      // the calendar.
      if (end.getHours() == 0 && end.getMinutes() == 0 && end.getSeconds() == 0) {
        end.setDate(end.getDate() - 1);
      }
      // String of the end date.
      strEnd = FullCalendar.formatDate(end, formatSettings);
    }
    // define the start date string in 'YYYY-MM-DD' format.
    if (start) {
      strStart = FullCalendar.formatDate(start, formatSettings);
    }
    const title = info.event.title.replace(/(<([^>]+)>)/ig,"");;
    const msg = Drupal.t('@title end is now @event_end. Do you want to save this change?', {
      '@title': title,
      '@event_end': strEnd
    });

    if (
        viewSettings.updateConfirm === 1 &&
        !confirm(msg)
    ) {
      info.revert();
    }
    else {
      /**
       * Perform ajax call for event update in database.
       */
      jQuery
        .post(
          drupalSettings.path.baseUrl +
            "fullcalendar-view-event-update",
          {
            eid: info.event.extendedProps.eid,
            entity_type: viewSettings.entityType,
            start: strStart,
            end: strEnd,
            start_field: viewSettings.startField,
            end_field: viewSettings.endField,
            token: viewSettings.token
          }
        )
        .done(function(data) {
          if (data !== '1') {
            alert("Error: " + data);
            info.revert();
          }
        });

    }
  }

  // Todo:
  // Set up the weith for the behavior.
  // Make sure it run after all others.
  // @see https://www.drupal.org/project/drupal/issues/2367655
  Drupal.behaviors.fullcalendarView = {
    attach: function(context, settings) {
      // If the BigPipe module is enabled,
      // We need to rebuild the Calendar during Drupal.behavior loops,
      // In case the DOM has changed.
      // @see https://www.drupal.org/project/fullcalendar_view/issues/3136764
      if (drupalSettings.calendar  && settings.bigPipePlaceholderIds) {
        // Rebuild the calendars.
        drupalSettings.calendar.forEach(function(calendar) {
          calendar.destroy();
          calendar.render();
          calendar.updateSize();
        })
      }
      else {
        $('.js-drupal-fullcalendar', context)
        .once("fullCalendarBehavior")
        .each(function() {
          let calendarEl = this;
          let viewIndex = parseInt(calendarEl.getAttribute("calendar-view-index"));
          let viewSettings = drupalSettings.fullCalendarView[viewIndex];
          var calendarOptions = JSON.parse(viewSettings.calendar_options);

          // Bind the resize event handler.
          calendarOptions.eventResize = eventResize;
          // Bind the day click handler.
          calendarOptions.dateClick = dayClickCallback;
          // Bind the event click handler.
          calendarOptions.eventClick = eventClick;
          // Bind the drop event handler.
          calendarOptions.eventDrop = eventDrop;
          // Render HTML correctly.
          calendarOptions.eventContent = function(eventInfo) {
            if (eventInfo.timeText && eventInfo.timeText.length && eventInfo.view.type === 'dayGridMonth') {
              return { html: `${eventInfo.timeText} ${eventInfo.event.title}` }
            }
            return { html: eventInfo.event.title }
          };
          // Language select element.
          var localeSelectorEl = document.getElementById('locale-selector-' + viewIndex);
          // Initial the calendar.
          if (calendarEl) {
            if (drupalSettings.calendar) {
              drupalSettings.calendar[viewIndex] = new FullCalendar.Calendar(calendarEl, calendarOptions);
            }
            else {
              drupalSettings.calendar = [];
              drupalSettings.calendar[viewIndex] = new FullCalendar.Calendar(calendarEl, calendarOptions);
            }
            let calendarObj = drupalSettings.calendar[viewIndex];
            calendarObj.render();

            // Accessibility fixes.
            function accessibilityFixes() {
              $('.fc-daygrid-more-link').each(function(){
                $(this).attr('tabindex', "0");
              });
              $('.fc-daygrid-day-number').each(function(){
                var label = $(this).closest('td').attr('data-date');
                $(this).attr("aria-label", "Click to go to the day view for " + label);
              });
              $('.fc-col-header-cell-cushion').each(function(){
                $(this).replaceWith(function() {
                    return '<span class="fc-col-header-cell-cushion">' + this.innerHTML + '</span>';
                });
              });
            }

            $(document).ready(function(){
              accessibilityFixes();
            });
            $(window).on("load", function(){
              accessibilityFixes();
            });
            $('.fc-button').on("click", function(){
              accessibilityFixes();
            });

            // Language dropdown box.
            if (viewSettings.languageSelector) {
              // build the locale selector's options
              calendarObj.getAvailableLocaleCodes().forEach(function(localeCode) {
                var optionEl = document.createElement('option');
                optionEl.value = localeCode;
                optionEl.selected = localeCode == calendarOptions.locale;
                optionEl.innerText = localeCode;
                localeSelectorEl.appendChild(optionEl);
              });
              // when the selected option changes, dynamically change the calendar option
              localeSelectorEl.addEventListener('change', function() {
                if (this.value) {
                  let viewIndex = parseInt(this.getAttribute("calendar-view-index"));
                  drupalSettings.calendar[viewIndex].setOption('locale', this.value);
                }
              });
            }
            else if (localeSelectorEl){
              localeSelectorEl.style.display = "none";
            }

            // Double click event.
            calendarEl.addEventListener('dblclick' , function(e) {
              let viewIndex = parseInt(this.getAttribute("calendar-view-index"));
              let viewSettings = drupalSettings.fullCalendarView[viewIndex];
              // New event window can be open if following conditions match.
              // * The new event content type are specified.
              // * Allow to create a new event by double click.
              // * User has the permission to create a new event.
              // * The add form for the new event type is known.
              if (
                  slotDate &&
                  viewSettings.eventBundleType &&
                  viewSettings.dblClickToCreate &&
                  viewSettings.addForm !== ""
                ) {
                  // Open a new window to create a new event (content).
                  window.open(
                      drupalSettings.path.baseUrl +
                      viewSettings.addForm +
                      "?start=" +
                      slotDate +
                      "&start_field=" +
                      viewSettings.startField +
                      "&destination=" + window.location.pathname,
                    "_blank"
                  );
                }

            });
          }
        });
      }
    }
  };
})(jQuery, Drupal);
