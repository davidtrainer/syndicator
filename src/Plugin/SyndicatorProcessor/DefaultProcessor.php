<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * A default processor that demonstrates some of the capabilities of the
 * SyndicatorProcessor plugin type.
 *
 * @SyndicatorProcessor(
 *   id = "default",
 *   description = "Default processor.",
 * )
 */
class DefaultProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    \Drupal::logger('syndicator')->notice("Default SyndicatorProcessor->ingest");

    // By default, just return the content as a string
    return $content;

    // The JSON can be decoded into an object for picking out the interesting
    // parts or for further manipulation
    $decoded = json_decode($content);
    return $decoded->data[0]->attributes->body->processed;
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    // We could simply render the content
    // return \Drupal\Core\Render\Markup::create($content);

    // Instead we will send it through the twig template
    $renderer = \Drupal::service('renderer');
    $render_array = array(
      '#theme' => 'DefaultProcessor',
      '#type' => 'syndicator',
      '#content' => \Drupal\Core\Render\Markup::create($content),
    );
    $content = $renderer->render($render_array);
    return $content;
  }

}
