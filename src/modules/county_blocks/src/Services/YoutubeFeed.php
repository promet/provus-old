<?php

namespace Drupal\county_blocks\Services;

use Drupal\Core\StringTranslation\StringTranslationTrait;
// phpcs:disable
use Google_Client;
use Google_Service_YouTube;
// phpcs:enable

/**
 * Creates YoutubeFeed.
 */
class YoutubeFeed {
  use StringTranslationTrait;

  /**
   * Fetch youtube videos from a given channel.
   *
   * @param int $count
   *   The number of videos to return.
   *
   * @return array
   *   An array of Video items.
   */
  public function getYoutubePosts($count = 3) {
    $items = [];

    try {
      $config = \Drupal::config('county_blocks.socialyoutubesettings');
      $api_key = $config->get('api_key');
      $youtube_channel_id = $config->get('youtube_channel_id');

      $client = new Google_Client();
      $client->setApplicationName('API code samples');
      $client->setDeveloperKey($api_key);

      // Define service object for making API requests.
      $service = new Google_Service_YouTube($client);

      $queryParams = [
        'channelId' => $youtube_channel_id,
        'maxResults' => $count,
        'order' => 'date',
      ];

      $results = $service->search->listSearch('snippet', $queryParams);

      foreach ($results->getItems() as $item) {
        if ($item['id']['kind'] == 'youtube#video') {
          $pub_date = strtotime($item['snippet']['publishedAt']);
          $items[$item['id']['videoId']] = [
            'title' => htmlspecialchars_decode($item['snippet']['title'], ENT_QUOTES),
            'date' => date('M d, Y g:i a', $pub_date),
            'channel' => [
              'id' => $item['snippet']['channelId'],
              'name' => $item['snippet']['channelTitle'],
            ],
          ];
          $items[$item['id']['videoId']]['iframe'] = [
            '#type' => 'inline_template',
            '#template' => '<iframe src="{{ url }}" id="player" type="text/html" width="640" height="390" frameborder="0"></iframe>',
            '#context' => [
              'url' => 'http://www.youtube.com/embed/' . $item['id']['videoId'] . '?enablejsapi=1',
            ],
          ];
          // Get channel details.
          $channel_results = $service->channels->listChannels('snippet,contentDetails,statistics', ['id' => $item['snippet']['channelId']]);
          $channels = $channel_results->getItems();
          if (!empty($channels[0])) {
            $items[$item['id']['videoId']]['channel']['subscribers'] = $this->shortScaleNumberFormat($channels[0]['statistics']['subscriberCount']);
            if (!empty($channels[0]['snippet']['thumbnails']['default']['url'])) {
              $items[$item['id']['videoId']]['channel']['image'] = $channels[0]['snippet']['thumbnails']['default']['url'];
            }
          }
          // Get video details.
          $video_results = $service->videos->listVideos('statistics', ['id' => $item['id']['videoId']]);
          $videos = $video_results->getItems();
          if (!empty($videos[0])) {
            $items[$item['id']['videoId']]['views'] = $this->shortScaleNumberFormat($videos[0]['statistics']['viewCount']);
          }
        }
      }
    }
    catch (\Exception $exception) {
      \Drupal::logger('county_blocks')
        ->error($this->t('Exception: @exception', [
          '@exception' => $exception->getMessage(),
        ]));
    }

    return $items;
  }

  /**
   * Helper function to convert number to 2k or 5M, etc.
   *
   * @param string $n
   *   The long number string.
   *
   * @return string
   *   The shorted number string.
   */
  private function shortScaleNumberFormat($n) {
    if ($n < 1000) {
      // Anything less than a thousand.
      $n_format = number_format($n);
    }
    elseif ($n < 1000000) {
      // Anything less than a million.
      $n_format = number_format($n / 1000) . 'k';
    }
    elseif ($n < 1000000000) {
      // Anything less than a billion.
      $n_format = number_format($n / 1000000, 2) . 'M';
    }
    else {
      // At least a billion.
      $n_format = number_format($n / 1000000000, 2) . 'B';
    }

    return $n_format;
  }

}
