<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * Provides a simple processor that processes the body field.
 *
 * @SyndicatorProcessor(
 *   id = "simple_body",
 *   description = "Simple body processor.",
 * )
 */
class SimpleBodyProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    $decoded = json_decode($content);
    return $decoded->data[0]->attributes->body->processed;
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    return \Drupal\Core\Render\Markup::create($content);
  }

}
