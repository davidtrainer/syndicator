<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * A processor that processes cat facts.
 *
 * @SyndicatorProcessor(
 *   id = "catfact",
 *   description = "Cat Fact processor.",
 * )
 */
class CatFactProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    $decoded = json_decode($content);

    return $decoded->fact;
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    // Just output the content
    return \Drupal\Core\Render\Markup::create($content);
  }

}
