<?php

namespace Drupal\syndicator\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Syndicator processor item annotation object.
 *
 * @see \Drupal\syndicator\Plugin\SyndicatorProcessorManager
 * @see plugin_api
 *
 * @Annotation
 */
class SyndicatorProcessor extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
