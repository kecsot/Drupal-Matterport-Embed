<?php

namespace Drupal\matterport_embed\Plugin\Field\FieldFormatter;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\matterport_embed\Entity\MatterportEmbedOptionSet;
use Drupal\matterport_embed\Entity\MatterportEmbedStyle;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererBase;
use Drupal\matterport_embed\Plugin\Field\FieldType\MatterportEmbedItem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @FieldFormatter(
 *   id = "matterport_embed",
 *   label = @Translation("Matterport Embed Formatter"),
 *   field_types = {
 *     "matterport_embed"
 *   }
 * )
 */
class MatterportEmbedFormatter extends FormatterBase implements ContainerFactoryPluginInterface
{
  protected EntityStorageInterface $styleStorage;
  protected EntityStorageInterface $optionSetStorage;
  protected MatterportEmbedRendererBase $matterportEmbed;

  /**
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings, $label, $view_mode,
    array $third_party_settings,
    EntityTypeManagerInterface $entityTypeManager,
    MatterportEmbedRendererBase $matterportEmbed)
  {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->optionSetStorage = $entityTypeManager->getStorage('matterport_embed_option_set');
    $this->styleStorage = $entityTypeManager->getStorage('matterport_embed_style');
    $this->matterportEmbed = $matterportEmbed;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
      $container->get('matterport_embed.renderer'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array
  {
    return [
        'style' => null,
        'option_set' => null,
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state): array
  {
    $manage_styles_link = Link::createFromRoute(t("Manage Styles"), 'entity.matterport_embed_style.collection', [], ['attributes' => ['target' => '_blank']]);
    $elements['style'] = [
      '#type' => 'select',
      '#options' => $this->getStyleOptions(),
      '#title' => $this->t('Style'),
      '#default_value' => $this->getSetting('style'),
      '#description' => $manage_styles_link,
    ];

    $manage_option_sets_link = Link::createFromRoute(t("Manage Option Sets"), 'entity.matterport_embed_option_set.collection', [], ['attributes' => ['target' => '_blank']]);
    $elements['option_set'] = [
      '#type' => 'select',
      '#options' => $this->getOptionSetOptions(),
      '#title' => $this->t('Option set'),
      '#default_value' => $this->getSetting('option_set'),
      '#description' => $manage_option_sets_link
    ];

    return $elements;
  }

  /**
   * Returns with possible options
   *
   * @return array
   */
  private function getStyleOptions(): array
  {
    $styles = $this->styleStorage->loadByProperties();
    foreach ($styles as $key => $style) {
      $result[$key] = $style->label();
    }
    return $result ?? [];
  }

  /**
   * Returns with possible options
   *
   * @return array
   */
  private function getOptionSetOptions(): array
  {
    $option_sets = $this->optionSetStorage->loadByProperties();

    foreach ($option_sets as $key => $option_set) {
      $result[$key] = $option_set->label();
    }
    return $result ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(): array
  {
    return [
      $this->t('Style: @name', ['@name' => $this->getCurrentStyleName()]),
      $this->t('Option set: @name', ['@name' => $this->getCurrentOptionSetName()])
    ];
  }

  /**
   * Returns with current style name
   *
   * @return string
   */
  private function getCurrentStyleName(): string
  {
    if ($id = $this->getSetting('style')) {
      if ($entity = $this->styleStorage->load($id)) {
        return $entity->label();
      }
    }
    return t('- Not set -');
  }

  /**
   * Returns with current option set name
   *
   * @return string
   */
  private function getCurrentOptionSetName(): string
  {
    if ($id = $this->getSetting('option_set')) {
      if ($entity = $this->optionSetStorage->load($id)) {
        return $entity->label();
      }
    }
    return t('- Not set -');
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array
  {
    $element = [];

    if ($style_key = $this->getSetting('style')) {
      if ($style = MatterportEmbedStyle::load($style_key)) {
        $style->execute($this->matterportEmbed);
        $this->matterportEmbed->addCacheableDependency($style);
      }
    }

    if ($option_set_key = $this->getSetting('option_set')) {
      if ($option_set = MatterportEmbedOptionSet::load($option_set_key)) {
        $option_set->execute($this->matterportEmbed);
        $this->matterportEmbed->addCacheableDependency($option_set);
      }
    }

    /**
     * @var MatterportEmbedItem[] $items
     */
    foreach ($items as $delta => $item) {
      $model_id = $item->getValue()['value'];

      $build = $this->matterportEmbed->build($model_id);
      $element[$delta] = $build;
    }

    return $element;
  }

  public function calculateDependencies(): array
  {
    $dependencies = parent::calculateDependencies();

    if ($style_key = $this->getSetting('style')) {
      if ($style = MatterportEmbedStyle::load($style_key)) {
        $dependencies[$style->getConfigDependencyKey()][] = $style->getConfigDependencyName();
      }
    }

    if ($option_set_key = $this->getSetting('option_set')) {
      if ($option_set = MatterportEmbedOptionSet::load($option_set_key)) {
        $dependencies[$option_set->getConfigDependencyKey()][] = $option_set->getConfigDependencyName();
      }
    }

    return $dependencies;
  }
}
