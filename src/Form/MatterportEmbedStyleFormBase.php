<?php

namespace Drupal\matterport_embed\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\matterport_embed\ConfigurableMatterportEmbedStyle;
use Drupal\matterport_embed\MatterportEmbedStyleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class MatterportEmbedStyleFormBase extends EntityForm
{
  protected EntityStorageInterface $storage;

  /**
   * @var MatterportEmbedStyleInterface
   */
  protected $entity;

  /**
   * @param EntityStorageInterface $storage
   */
  public function __construct(EntityStorageInterface $storage)
  {
    $this->storage = $storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')->getStorage('matterport_embed_style')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state)
  {
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => !$this->entity->isNew() ? $this->entity->label() : '',
      '#maxlength' => '255',
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#disabled' => !$this->entity->isNew(),
      '#maxlength' => 64,
      '#machine_name' => [
        'exists' => [$this, 'exists'],
      ],
    ];
    $form['plugin'] = [
      '#type' => 'value',
      '#value' => $this->entity->get('plugin'),
    ];

    if ($plugin = $this->getPlugin()) {
      $form += $plugin->buildConfigurationForm($form, $form_state);
    }

    return parent::form($form, $form_state);
  }

  /**
   * @return ConfigurableMatterportEmbedStyle|null
   */
  protected function getPlugin(): ?ConfigurableMatterportEmbedStyle
  {
    if ($this->entity->getPlugin() instanceof PluginFormInterface) {
      return $this->entity->getPlugin();
    }
    return NULL;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function exists($id): bool
  {
    $entity = $this->storage->load($id);
    return !empty($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
    if ($plugin = $this->getPlugin()) {
      $plugin->validateConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);
    if ($plugin = $this->getPlugin()) {
      $plugin->submitConfigurationForm($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $this->entity->save();
    $this->messenger()->addStatus($this->t('The style has been successfully saved.'));

    $form_state->setRedirect('entity.matterport_embed_style.collection');
  }

}
