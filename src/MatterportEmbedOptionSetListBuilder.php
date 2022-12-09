<?php

namespace Drupal\matterport_embed;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class MatterportEmbedOptionSetListBuilder extends ConfigEntityListBuilder
{

  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    assert($entity instanceof MatterportEmbedOptionSetInterface);

    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

}
