<?php

namespace Drupal\syndicator\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Syndicator processor plugin manager.
 */
class SyndicatorProcessorManager extends DefaultPluginManager {


  /**
   * Constructs a new SyndicatorProcessorManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/SyndicatorProcessor', $namespaces, $module_handler, 'Drupal\syndicator\Plugin\SyndicatorProcessorInterface', 'Drupal\syndicator\Annotation\SyndicatorProcessor');

    $this->alterInfo('syndicator_syndicator_processor_info');
    $this->setCacheBackend($cache_backend, 'syndicator_syndicator_processor_plugins');
  }

}
