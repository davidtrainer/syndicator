<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * A processor that handles the current BTC price.
 *
 * @SyndicatorProcessor(
 *   id = "btcprice",
 *   description = "BTC Price processor.",
 * )
 */
class BtcPriceProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    \Drupal::logger('syndicator')->notice("BTC Price SyndicatorProcessor->ingest");
    return $content;
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    $decoded = json_decode($content);
    $vars = array();
    $vars['time'] = $decoded->time->updated;
    foreach($decoded->bpi as $key=>$item) {
      $vars['rates'][$key]['symbol'] = $item->symbol;
      $vars['rates'][$key]['code'] = $item->code;
      $vars['rates'][$key]['rate'] = $item->rate;
    }

    $renderer = \Drupal::service('renderer');
    $render_array = array(
      '#theme' => 'BtcPriceProcessor',
      '#type' => 'syndicator',
      '#content' => $content,
      '#vars' => $vars,
    );
    $content = $renderer->render($render_array);

    return $content;
  }
}
