<?php

namespace Drupal\matterport_embed\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\matterport_embed\MatterportEmbedStylePluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MatterportEmbedStyleCreateHeaderForm extends FormBase
{

  /**
   * @var MatterportEmbedStylePluginManager
   */
  protected $manager;

  /**
   * @param MatterportEmbedStylePluginManager $manager
   */
  public function __construct(MatterportEmbedStylePluginManager $manager)
  {
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('plugin.manager.matterport_embed_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'matterport_embed_style_admin_manage';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $styles = [];
    foreach ($this->manager->getDefinitions() as $id => $definition) {
      if (is_subclass_of($definition['class'], '\Drupal\Core\Plugin\PluginFormInterface')) {
        $styles[$id] = $definition['label'];
      }
    }
    asort($styles);
    $form['parent'] = [
      '#type' => 'details',
      '#title' => $this->t('Create new Matterport Embed style'),
      '#attributes' => ['class' => ['container-inline']],
      '#open' => TRUE,
    ];
    $form['parent']['style'] = [
      '#type' => 'select',
      '#title' => $this->t('Style'),
      '#title_display' => 'invisible',
      '#options' => $styles,
      '#empty_option' => $this->t('- Select -'),
    ];
    $form['parent']['styles'] = [
      '#type' => 'actions',
    ];
    $form['parent']['styles']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    if ($form_state->getValue('style')) {
      $form_state->setRedirect(
        'entity.matterport_embed_style.add_form',
        ['matterport_embed_style_plugin' => $form_state->getValue('style')]
      );
    }
  }

}
