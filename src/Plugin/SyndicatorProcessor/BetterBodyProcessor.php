<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * Provides a processor that processes the body field.
 *
 * @SyndicatorProcessor(
 *   id = "better_body",
 *   description = "Better body processor.",
 * )
 */
class BetterBodyProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    $decoded = json_decode($content);
    $mycontent = $decoded->data[0]->attributes->body->processed;
    $mycontent = strip_tags($mycontent, ['p', 'a', 'br']);
    return $mycontent;
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    return \Drupal\Core\Render\Markup::create($content);
  }

}