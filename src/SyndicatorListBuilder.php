<?php

namespace Drupal\syndicator;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Syndicator entities.
 */
class SyndicatorListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Syndicator');
    $header['url'] = $this->t('URL');
    $header['processor'] = $this->t('Processor');
    $header['ttl'] = $this->t('TTL');
    $header['last_updated'] = $this->t('Last Updated');
    $header['content'] = $this->t('Content');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['request'] = $entity->getHost() . $entity->getPath();
    $row['processor'] = $entity->getProcessorName();
    $row['ttl'] = $entity->getTtl();
    $row['last_updated'] = \Drupal::service('date.formatter')->format($entity->getLastUpdated(), 'short');
    $row['content'] = substr($entity->getContent(), 0, 20);
    return $row + parent::buildRow($entity);
  }

}
