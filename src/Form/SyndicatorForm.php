<?php

namespace Drupal\syndicator\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SyndicatorForm.
 */
class SyndicatorForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $syndicator = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $syndicator->label(),
      '#description' => $this->t("Label for the Syndicator."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $syndicator->id(),
      '#machine_name' => [
        'exists' => '\Drupal\syndicator\Entity\Syndicator::load',
      ],
      '#disabled' => !$syndicator->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    $form['host'] = [
      '#type' => 'textfield',
      '#default_value' => 'http://test.demo.dt8.xyz/',
      '#required' => TRUE,
      '#description' => $this->t("Publisher hostname."),
    ];

    $form['path'] = [
      '#type' => 'textfield',
      '#default_value' => $syndicator->getPath(),
      '#required' => TRUE,
      "#maxlength" => 1024,
      '#description' => $this->t("Path on the publisher."),
    ];

    $form['processor'] = [
      '#type' => 'select',
      '#default_value' => $syndicator->getProcessorName(),
      '#options' => array(),
      '#required' => FALSE,
      '#description' => $this->t("Select a processor for content from this syndicator."),
    ];

    $form['ttl'] = [
      '#type' => 'textfield',
      '#default_value' => $syndicator->getTtl(),
      '#description' => $this->t("Time to live, in minutes."),
    ];

    $form['stored_content'] = [
      '#type' => 'textarea',
      '#disabled' => TRUE,
      '#value' => $syndicator->getContent(),
      '#description' => $this->t("Currently stored content."),
    ];

    $form['last_updated'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => "Last updated: " .
        $syndicator->isNew() ? '' : \Drupal::service('date.formatter')->format($syndicator->getLastUpdated(), 'long'),
    ];

    $form['content'] = [
      '#type' => 'textarea',
      '#disabled' => FALSE,
      '#value' => $syndicator->fetchContent(),
      '#description' => $this->t("Content fresh from the publisher."),
    ];

    // Get the plugin type
    $type = \Drupal::service('plugin.manager.syndicator_processor');
    // Get a list of available plugins of that type
    $plugin_definitions = $type->getDefinitions();
    // Use that to populate the select list
    foreach ($plugin_definitions as $processor_plugin) {
      $form['processor']['#options'][$processor_plugin['id']] =
      $processor_plugin['id'] . " - " . $processor_plugin['description'];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $syndicator = $this->entity;
    //ksm($form_state->getValues());
    $content = $form_state->getValues()['content'];
    $syndicator = $syndicator->updateContent($content);
    //ksm($syndicator);
    $status = $syndicator->save();
    //ksm($status);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Syndicator.', [
          '%label' => $syndicator->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Syndicator.', [
          '%label' => $syndicator->label(),
        ]));
    }
    $form_state->setRedirectUrl($syndicator->toUrl('collection'));
  }

}
