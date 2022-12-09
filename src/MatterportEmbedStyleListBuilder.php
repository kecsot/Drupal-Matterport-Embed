<?php

namespace Drupal\matterport_embed;

use Drupal;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

class MatterportEmbedStyleListBuilder extends ConfigEntityListBuilder
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
    /** @var MatterportEmbedStyleInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array
  {
    $build['admin_manage_form'] = Drupal::formBuilder()->getForm('Drupal\matterport_embed\Form\MatterportEmbedStyleCreateHeaderForm');
    $build['table'] = parent::render();
    return $build;
  }

}
