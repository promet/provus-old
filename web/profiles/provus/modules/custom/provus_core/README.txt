Node Card Style Configuring
--------------------------------

There are hooks in this module that facilitate a way of configuring display
modes for any kind of node without creating a config file for each view mode
on each node type.  This makes the development process easier when developing
a set of card styles that Provus makes use of.  Instructions follow for using:

How to install and configure provus node card syles
--------------------------------

* Enable the module (This will add 1 configuration file for managing the modes).
* Add the node view mode names you want in the interface as you normally would
  at /admin/structure/display-modes/view/add/node.
* Export the provus_core.displays.node_settings.yml and add/edit entries for
  the different view modes that you previously setup in the previous step.  See
  examples below for how to configure and map.
* Import this provus_core.displays.node_settings.yml configuration file back into
  your site.
* Create a twig file matching a suggestion with your display style in it.  For
  example: node--card-1.html.twig or node--article--card-1.html.twig
  (assuming card_1 was the view mode you setup)
* Use the abstracted names of field variables that are in the config file
  rather than actual field names in the twig files assuming you have mapped them
  correctly to a field.  Ex: use {{ content.image }} instead of
  {{ content.field_media_image }}
* Use this view mode of your content type in views or somewhere else.
* Viola, the rendered display is using what you configured in this one file so
  you don't have to go to lots of view mode pages for each content type.


Breakdown of the format of entries
--------------------------------
Example

displays:
  card_1:
    content:
      body:
        field: body
        options:
          label: hidden
          type: smart_trim
          weight: 0
          settings:
            trim_length: 300
            trim_type: chars
            trim_suffix: ...
            wrap_class: trimmed
            more_text: More
            more_class: more-link
            summary_handler: full
            wrap_output: false
            more_link: false
            trim_options:
              text: false
              trim_zero: false
          third_party_settings: {  }
          region: content
      image:
        field: field_media_image
        options:
          type: entity_reference_entity_view
          weight: 1
          region: content
          label: hidden
          settings:
            view_mode: default
            link: false
          third_party_settings: {  }
      date:
        field: field_publish_date
        options:
          type: datetime_default
          weight: 2
          region: content
          label: hidden
          settings:
            timezone_override: ''
            format_type: default
          third_party_settings: {  }
    overrides:
      event:
        date:
          field: field_start_date

The first level inside of 'displays' are each of your view mode machine names.
The next level down lets you configure the 'content' and any 'overrides'.  The
content will list out each component that you are passing on to the display and
are available in the twig as content.name (ex: content.body).  For each
component you will list the component variable name and then the drupal field
that the data will come from in your content type.  Then you will provide the
normal field widget options that gets exported when you save out a view mode
config.  Set up each component, the field name it will map to and the widget
options for display.

The settings under content apply for all content types in your system unless
you add an overrides section to that view mode.  If you add an overrides, then
you specify the content type name (ex: events) and then list the component name
that will take an override and then what you are overriding.  You can override
a field name if the content type uses a different field name or you can override
different settings within the options for that field.
