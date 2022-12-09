<?php

namespace Drupal\matterport_embed;

use Drupal\Core\Form\FormStateInterface;

abstract class ConfigurableMatterportEmbedStyle extends MatterportEmbedStylePluginBase implements ConfigurableMatterportEmbedStyleInterface
{

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration)
  {
    $this->configuration = $configuration + $this->defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration(): array
  {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
  }

}
