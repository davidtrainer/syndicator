<?php

namespace Drupal\syndicator\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Syndicator processor plugins.
 */
interface SyndicatorProcessorInterface extends PluginInspectionInterface {


  // Add get/set methods for your plugin type here.

  /**
   * Provide a description of the syndicator processor.
   *
   * @return string
   *   A string description of the syndicator processor.
   */
  public function description();

  /**
   * Ingest the entirety of a response from a remote publisher, process it, and return the data to be saved.
   *
   * @param string $content
   *   Some content fetched from the remote publisher.
   * 
   * @return string
   *   The processed content.
   */
  public function ingest(string $content);

  /**
   * Render the content.
   *
   * @param string $content
   *   Some content stored by the syndicator entity.
   * 
   * @return string
   *   The rendered content.
   */
  public function render(string $content);

}
