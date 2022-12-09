<?php

namespace Drupal\matterport_embed\Entity;

use Drupal;
use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererStyleInterface;
use Drupal\matterport_embed\MatterportEmbedStyleInterface;

/**
 * @ConfigEntityType(
 *   id = "matterport_embed_style",
 *   label = @Translation("Matterport Embed Style"),
 *   label_collection = @Translation("Matterport Embed Styles"),
 *   label_singular = @Translation("Matterport Embed Style"),
 *   label_plural = @Translation("Matterport Embed Styles"),
 *   label_count = @PluralTranslation(
 *     singular = "@count matterport embed style",
 *     plural = "@count matterport embed styles",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\matterport_embed\MatterportEmbedStyleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\matterport_embed\Form\MatterportEmbedStyleAddForm",
 *       "edit" = "Drupal\matterport_embed\Form\MatterportEmbedStyleEditForm",
 *       "delete" = "Drupal\matterport_embed\Form\MatterportEmbedStyleDeleteForm"
 *     }
 *   },
 *   config_prefix = "matterport_embed_style",
 *   admin_permission = "administer matterport_embed",
 *   links = {
 *     "collection" = "/admin/structure/matterport-embed-style",
 *     "add-form" = "/admin/structure/matterport-embed-style/add",
 *     "edit-form" = "/admin/structure/matterport-embed-style/{matterport_embed_style}",
 *     "delete-form" = "/admin/structure/matterport-embed-style/{matterport_embed_style}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "plugin",
 *     "configuration",
 *   }
 * )
 */
class MatterportEmbedStyle extends ConfigEntityBase implements MatterportEmbedStyleInterface, EntityWithPluginCollectionInterface
{

  /**
   * The matterport embed style ID.
   */
  protected string $id;

  /**
   * The matterport embed style label.
   */
  protected string $label;

  protected $plugin;

  /**
   * @var array
   */
  protected $configuration = [];

  /**
   * The plugin collection that stores plugins.
   *
   * @var DefaultSingleLazyPluginCollection
   */
  protected $pluginCollection;

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections()
  {
    return ['configuration' => $this->getPluginCollection()];
  }

  protected function getPluginCollection()
  {
    if (!$this->pluginCollection) {
      $this->pluginCollection = new DefaultSingleLazyPluginCollection(Drupal::service('plugin.manager.matterport_embed_style'), $this->plugin, $this->configuration);
    }
    return $this->pluginCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginDefinition()
  {
    return $this->getPlugin()->getPluginDefinition();
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin()
  {
    return $this->getPluginCollection()->get($this->plugin);
  }

  /**
   * {@inheritdoc}
   */
  public function setPlugin($plugin_id)
  {
    $this->plugin = $plugin_id;
    $this->getPluginCollection()->addInstanceId($plugin_id);
  }

  public function execute(MatterportEmbedRendererStyleInterface $matterportEmbedRenderer)
  {
    $this->getPlugin()->execute($matterportEmbedRenderer);
  }
}
