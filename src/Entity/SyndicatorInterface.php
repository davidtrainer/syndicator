<?php

namespace Drupal\syndicator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Syndicator entities.
 */
interface SyndicatorInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.

  public function getHost();

  public function getPath();

  public function getProcessor();

  public function getTtl();

  public function getLastUpdated();

  public function setLastUpdated();

  public function getContent();

  public function fetchContent();

}
