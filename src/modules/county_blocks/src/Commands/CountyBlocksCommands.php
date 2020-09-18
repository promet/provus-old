<?php

namespace Drupal\county_blocks\Commands;

use Drupal\county_blocks\CountyBlocksHelper;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 */
class CountyBlocksCommands extends DrushCommands {

  /**
   * Fetches the alert feed.
   *
   * @command county_blocks:fetch-feed
   * @options feedback The level of desired feedback level, (0, 1, 2).
   * @aliases oc-fetch
   * @usage county_blocks:fetch-feed --feedback=1
   *   Display A data dump of the current alerts.
   */
  public function fetchFeed($options = ['feedback' => 0]) {
    CountyBlocksHelper::fetchAlerts();

    if (!empty($options['feedback'])) {
      $this->output()->writeln(print_r(CountyBlocksHelper::getData('alerts'), TRUE));
    }
  }

}
