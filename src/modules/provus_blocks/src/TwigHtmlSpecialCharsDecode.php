<?php

namespace Drupal\provus_blocks;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Twig functions.
 */
class TwigHtmlSpecialCharsDecode extends AbstractExtension {

  public function getFilters() {
    return [
      new TwigFilter('htmlspecialchars_decode', [$this, 'filter']),
    ];
  }

  public function filter($text) {
    return htmlspecialchars_decode($text);
  }

}
