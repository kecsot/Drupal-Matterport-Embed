<?php

namespace Drupal\matterport_embed\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\Exception\MissingDataException;

/**
 *
 * @FieldType(
 *   id = "matterport_embed",
 *   label = @Translation("Matterport Embed"),
 *   category = @Translation("Other"),
 *   default_widget = "matterport_embed",
 *   default_formatter = "matterport_embed"
 * )
 */
class MatterportEmbedItem extends FieldItemBase
{

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array
  {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Model id'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array
  {
    $columns = [
      'value' => [
        'type' => 'varchar',
        'not null' => FALSE,
        'description' => 'Matterport Embed Model ids',
        'length' => 63,
      ],
    ];

    return [
      'columns' => $columns,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array
  {
    $random = new Random();
    $values['value'] = $random->word(mt_rand(1, 50));
    return $values;
  }

  /**
   * {@inheritdoc}
   * @throws MissingDataException
   */
  public function isEmpty(): bool
  {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

}
