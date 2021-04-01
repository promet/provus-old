<?php

namespace Drupal\search_api_glossary\Plugin\facets\processor;

use Drupal\facets\FacetInterface;
use Drupal\facets\Processor\BuildProcessorInterface;
use Drupal\facets\Processor\ProcessorPluginBase;
use Drupal\facets\Result\Result;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Pager\RequestPagerInterface;

/**
 * Provides a processor to show All items in Glossary AZ.
 *
 * @FacetsProcessor(
 *   id = "glossaryaz_all_items_processor",
 *   label = @Translation("All items in Glossary AZ"),
 *   description = @Translation("Option to show All items in Glossary AZ. Make sure URL Handler runs BEFORE this processor (see: Advanced settings > Build Stage)"),
 *   stages = {
 *     "build" = 10
 *   }
 * )
 */
class GlossaryAZAllItemsProcessor extends ProcessorPluginBase implements BuildProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function build(FacetInterface $facet, array $results) {
    // Process the results count.
    $show_all_item_count = 0;
    // TODO revise this logic based on progress with
    // All items count is not correct when narrowing the results.
    // https://www.drupal.org/project/facets/issues/2692027
    // see https://git.drupalcode.org/project/facets/commit/21343a6
    foreach ($results as $result) {
      $show_all_item_count += $result->getCount();
    }

    $show_all_item = new Result($facet, t('All')->getUntranslatedString(), t('All'), $show_all_item_count);

    // Deal with the ALL Items path.
    // See QueryString::buildUrls.
    $path = Request::create($facet->getFacetSource()->getPath());
    $url = Url::createFromRequest($path);

    // First get the current list of get parameters without pager.
    $get_params = new ParameterBag(RequestPagerInterface->getQueryParameters());

    // See UrlProcessorPluginBase::__construct.
    $facet_source_config = $facet->getFacetSourceConfig();
    $filterKey = $facet_source_config->getFilterKey() ?: 'f';

    // See QueryString::buildUrls.
    $filter_params = $get_params->get($filterKey, [], TRUE);

    // Remove the filter string from the parameters.
    foreach ($filter_params as $key => $filter_param) {
      unset($filter_params[$key]);
    }

    $get_params->set($filterKey, array_values($filter_params));
    $url->setOption('query', $get_params->all());

    // Set the path.
    $show_all_item->setUrl($url);

    // If no other facets are selected, default to ALL.
    if (empty($facet->getActiveItems())) {
      $show_all_item->setActiveState(TRUE);
    }

    // All done.
    $results[] = $show_all_item;

    return $results;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsFacet(FacetInterface $facet) {
    // Check if
    // 1) The correct widget is chosen for the facet
    // 2) If the glossary processor is enabled in Search API index.
    $widget = $facet->getWidget()['type'];
    $search_processors = $facet->getFacetSource()->getIndex()->getProcessors();

    if ($widget == 'glossaryaz' && array_key_exists('glossary', $search_processors)) {
      // Glossary processor is enabled.
      return TRUE;
    }

    return FALSE;
  }

}
