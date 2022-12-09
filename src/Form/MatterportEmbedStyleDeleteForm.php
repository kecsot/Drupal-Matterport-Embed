<?php

namespace Drupal\matterport_embed\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Url;

class MatterportEmbedStyleDeleteForm extends EntityDeleteForm
{

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url
  {
    return new Url('entity.matterport_embed_style.collection');
  }

}
