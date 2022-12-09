<?php

namespace Drupal\matterport_embed\Plugin\MatterportEmbedStyle;

use Drupal\Core\Form\FormStateInterface;
use Drupal\matterport_embed\ConfigurableMatterportEmbedStyle;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererStyleInterface;

/**
 * Plugin implementation of the matterport_embed_style.
 *
 * @MatterportEmbedStyle(
 *   id = "responsive_style",
 *   label = @Translation("Responsive style"),
 * )
 */
class ResponsiveStyle extends ConfigurableMatterportEmbedStyle
{
  const MINIMUM_VALUE = 1;

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [
      'x_ratio' => NULL,
      'y_ratio' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form['x_ratio'] = [
      '#type' => 'number',
      '#title' => t('X Ratio'),
      '#default_value' => $this->configuration['x_ratio'] ?? '',
      '#required' => TRUE,
      '#min' => self::MINIMUM_VALUE,
    ];

    $form['y_ratio'] = [
      '#type' => 'number',
      '#title' => t('Y Ratio'),
      '#default_value' => $this->configuration['y_ratio'] ?? '',
      '#required' => TRUE,
      '#min' => self::MINIMUM_VALUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    $this->configuration['width'] = $form_state->getValue('width');
    $this->configuration['x_ratio'] = $form_state->getValue('x_ratio');
    $this->configuration['y_ratio'] = $form_state->getValue('y_ratio');
  }

  public function execute(MatterportEmbedRendererStyleInterface $matterportEmbedRenderer)
  {
    $matterportEmbedRenderer->addClass("responsive-style");
    $matterportEmbedRenderer->addLibrary("matterport_embed/responsive-style");

    // Calculate aspect ratio as inline style
    $y_ratio = $this->configuration['y_ratio'];
    $x_ratio = $this->configuration['x_ratio'];
    $x_ratio_safe = max($x_ratio, self::MINIMUM_VALUE);
    $y_ratio_safe = max($y_ratio, self::MINIMUM_VALUE);
    $matterportEmbedRenderer->addWrapperInlineStyle("aspect-ratio: $x_ratio_safe / $y_ratio_safe");
  }
}
