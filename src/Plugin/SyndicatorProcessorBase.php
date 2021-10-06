<?php

namespace Drupal\syndicator\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Syndicator processor plugins.
 */
abstract class SyndicatorProcessorBase extends PluginBase implements SyndicatorProcessorInterface {


  // Add common methods and abstract methods for your plugin type here.

  /**
   * Retrieve the @description property from the annotation and return it.
   *
   * @return string
   */
  public function description() {
    return $this->pluginDefinition['description'];
  }

}
