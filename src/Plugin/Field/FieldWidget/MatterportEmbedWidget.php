<?php

namespace Drupal\matterport_embed\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 * @FieldWidget(
 *   id = "matterport_embed",
 *   label = @Translation("Matterport Embed"),
 *   field_types = {"matterport_embed"},
 * )
 */
class MatterportEmbedWidget extends WidgetBase
{

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array
  {
    $element['value'] = $element + [
        '#type' => 'textfield',
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      ];

    return $element;
  }

}
