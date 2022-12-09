<?php

namespace Drupal\matterport_embed\Plugin\MatterportEmbedStyle;

use Drupal\Core\Form\FormStateInterface;
use Drupal\matterport_embed\ConfigurableMatterportEmbedStyle;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererStyleInterface;

/**
 * Plugin implementation of the matterport_embed_style.
 *
 * @MatterportEmbedStyle(
 *   id = "square_style",
 *   label = @Translation("Square style"),
 *   description = @Translation("Define a square. Width and Height is equal.")
 * )
 */
class SquareStyle extends ConfigurableMatterportEmbedStyle
{

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [
      'size' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form['size'] = [
      '#type' => 'number',
      '#title' => t('Size'),
      '#default_value' => $this->configuration['size'] ?? '',
      '#field_suffix' => ' ' . t('pixels'),
      '#required' => TRUE,
      '#min' => 1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    $this->configuration['size'] = $form_state->getValue('size');
  }

  public function execute(MatterportEmbedRendererStyleInterface $matterportEmbedRenderer)
  {
    $matterportEmbedRenderer->addClass("square-style");
    $matterportEmbedRenderer->setHeight($this->configuration['size']);
    $matterportEmbedRenderer->setWidth($this->configuration['size']);
  }
}
