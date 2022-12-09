<?php

namespace Drupal\matterport_embed\Form;

use Drupal\Core\Form\FormStateInterface;

class MatterportEmbedStyleAddForm extends MatterportEmbedStyleFormBase
{

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * @param $matterport_embed_style_plugin
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state, $matterport_embed_style_plugin = NULL)
  {
    $this->entity->setPlugin($matterport_embed_style_plugin);

    $definition = $this->entity->getPluginDefinition();
    $this->entity->set('label', $definition['label']);

    return parent::buildForm($form, $form_state);
  }

}
