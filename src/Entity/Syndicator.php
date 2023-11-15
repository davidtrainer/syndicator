<?php

namespace Drupal\syndicator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\syndicator\SyndicatorProcessorBase;

/**
 * Defines the Syndicator entity.
 *
 * @ConfigEntityType(
 *   id = "syndicator",
 *   label = @Translation("Syndicator"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\syndicator\SyndicatorListBuilder",
 *     "form" = {
 *       "add" = "Drupal\syndicator\Form\SyndicatorForm",
 *       "edit" = "Drupal\syndicator\Form\SyndicatorForm",
 *       "delete" = "Drupal\syndicator\Form\SyndicatorDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\syndicator\SyndicatorHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "syndicator",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "host" = "host",
 *     "path" = "path",
 *     "processor" = "processor",
 *     "ttl" = "ttl",
 *     "lastupdated" = "lastupdated",
 *     "content" = "content"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "host" = "host",
 *     "path" = "path",
 *     "processor" = "processor",
 *     "ttl" = "ttl",
 *     "lastupdated" = "lastupdated",
 *     "content" = "content",
 *     "weight"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/syndicator/{syndicator}",
 *     "add-form" = "/admin/structure/syndicator/add",
 *     "edit-form" = "/admin/structure/syndicator/{syndicator}/edit",
 *     "delete-form" = "/admin/structure/syndicator/{syndicator}/delete",
 *     "collection" = "/admin/structure/syndicator"
 *   }
 * )
 */
class Syndicator extends ConfigEntityBase implements SyndicatorInterface {

  /**
   * The Syndicator ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Syndicator label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Syndicator remote host.
   *
   * @var string
   */
  protected $host;

  /**
   * The Syndicator path.
   *
   * @var string
   */
  protected $path;

  /**
   * The Syndicator TTL.
   *
   * @var string
   */
  protected $ttl;

  /**
   * The time the syndicator was last updated.
   *
   * @var datetime
   */
  protected $lastupdated;

  /**
   * The Syndicated content.
   *
   * @var string
   */
  protected $content;

  /**
   * Get the hostname of the publisher.
   */
  public function getHost() {
    return $this->get('host');
  }

  /**
   * Get the path.
   */
  public function getPath() {
    return $this->get('path');
  }

  /**
   * Get the Processor.
   */
  public function getProcessor() {
    $processorname = $this->get('processor');
    if(empty($processorname)) {
      $processorname = 'default';
    }
    return \Drupal::service('plugin.manager.syndicator_processor')->createInstance($processorname);
  }

  /**
   * Get the name of the Processor.
   */
  public function getProcessorName() {
    return $this->get('processor');
  }

  /**
   * Get the TTL.
   */
  public function getTtl() {
    return $this->get('ttl');
  }

  /**
   * Get the time the content was last updated.
   */
  public function getLastUpdated() {
    return $this->get('lastupdated');
  }

  /**
   * Set the last updated time to the current time.
   */
  public function setLastUpdated() {
      // Set the new value
      $this->set('lastupdated', time());
      return $this;
  }

  /**
   * Get the currently stored content.
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * Fetch content from the publisher.
   */
  public function fetchContent() {
    $log = "Fetching data for " . $this->get('label') . ": <pre>";
    $url = $this->getHost() . $this->getPath();
    try {
      $response = \Drupal::httpClient()->request('GET', $url, [
        'auth' => [
          'user', // This is a POC for demo purposes
          'pass' // This is a POC for demo purposes
        ]
      ]);
      $data = $response->getBody()->getContents();

      $log .= "\nFetched this data: " . print_r($data, TRUE);

      // Just checking if this is valid JSON
      $decoded = json_decode($data);
      if (!$decoded) {
        throw new \Exception('Invalid data returned from API');
      }

    } catch (\Exception $e) {
      $log .= "Exception: " . $e . "</pre>";
      \Drupal::logger('syndicator')->error($log);
      return "";
    }

    // Get the SyndicationProcessor configured for this syndicator, and ingest the data.
    $processor = $this->getProcessor();

    $log .= "\nProcessor: " . print_r($processor, TRUE) . "</pre>";

    \Drupal::logger('syndicator')->notice($log . "</pre>");

    return $processor->ingest($data);
  }

  /**
   * Update the stored content with new content.
   */
  public function updateContent($content = '') {
    if(!$content) {
      $content = $this->fetchContent();
    }
    $log = "Saving content for " . $this->label() . ": <pre>" . $content . "</pre>";
    \Drupal::logger('syndicator')->notice($log);

    $this->set('content', $content);
    $this->set('lastupdated', \Drupal::time()->getRequestTime());
    return $this;
  }
}
