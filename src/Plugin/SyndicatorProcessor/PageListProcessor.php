<?php

namespace Drupal\syndicator\Plugin\SyndicatorProcessor;

use Drupal\syndicator\Plugin\SyndicatorProcessorBase;

/**
 * Provides a processor that processes the body field.
 *
 * @SyndicatorProcessor(
 *   id = "page_list",
 *   description = "Processes multiple content entities.",
 * )
 */
class PageListProcessor extends SyndicatorProcessorBase {

  /**
   * {inheritdoc}
   */
  public function ingest($content) {
    $decoded = json_decode($content);
    $data = $decoded->data;
    return json_encode($data);
  }

  /**
   * {inheritdoc}
   */
  public function render($content) {
    $decoded = json_decode($content);
    $vars = array();
    foreach($decoded as $key=>$item) {
      $vars[$key]['title'] = $item->attributes->title;
      $vars[$key]['body'] = \Drupal\Core\Render\Markup::create($item->attributes->body->processed);
      $vars[$key]['url'] = $item->links->self->href;
      $vars[$key]['updated'] = $item->attributes->changed;
    }

    $renderer = \Drupal::service('renderer');
    $render_array = array(
      '#theme' => 'PageListProcessor',
      '#type' => 'syndicator',
      '#content' => $content,
      '#vars' => $vars,
    );
    $content = $renderer->render($render_array);
    return $content;
  }
}
