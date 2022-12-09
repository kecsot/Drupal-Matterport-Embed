<?php

namespace Drupal\matterport_embed\Plugin\MatterportEmbedStyle;

use Drupal\Core\Form\FormStateInterface;
use Drupal\matterport_embed\ConfigurableMatterportEmbedStyle;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererStyleInterface;

/**
 * Plugin implementation of the matterport_embed_style.
 *
 * @MatterportEmbedStyle(
 *   id = "rectangle_style",
 *   label = @Translation("Rectangle style"),
 *   description = @Translation("Possible to specify width and height separetly")
 * )
 */
class RectangleStyle extends ConfigurableMatterportEmbedStyle
{

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [
      'width' => NULL,
      'height' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form['width'] = [
      '#type' => 'number',
      '#title' => t('Width'),
      '#default_value' => $this->configuration['width'] ?? '',
      '#field_suffix' => ' ' . t('pixels'),
      '#required' => TRUE,
      '#min' => 1,
    ];

    $form['height'] = [
      '#type' => 'number',
      '#title' => t('Height'),
      '#default_value' => $this->configuration['height'] ?? '',
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
    $this->configuration['width'] = $form_state->getValue('width');
    $this->configuration['height'] = $form_state->getValue('height');
  }

  public function execute(MatterportEmbedRendererStyleInterface $matterportEmbedRenderer)
  {
    $matterportEmbedRenderer->addClass("rectangle-style");
    $matterportEmbedRenderer->setHeight($this->configuration['height']);
    $matterportEmbedRenderer->setWidth($this->configuration['width']);
  }
}
