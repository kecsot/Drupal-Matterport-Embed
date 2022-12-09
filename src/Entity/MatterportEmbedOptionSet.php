<?php

namespace Drupal\matterport_embed\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\matterport_embed\MatterportEmbedOptionSetInterface;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererOptionSetInterface;

/**
 *
 * @ConfigEntityType(
 *   id = "matterport_embed_option_set",
 *   label = @Translation("Matterport Embed Option Set"),
 *   label_collection = @Translation("Matterport Embed Option Sets"),
 *   label_singular = @Translation("matterport embed option set"),
 *   label_plural = @Translation("matterport embed option sets"),
 *   label_count = @PluralTranslation(
 *     singular = "@count matterport embed option set",
 *     plural = "@count matterport embed option sets",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\matterport_embed\MatterportEmbedOptionSetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\matterport_embed\Form\MatterportEmbedOptionSetForm",
 *       "edit" = "Drupal\matterport_embed\Form\MatterportEmbedOptionSetForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "matterport_embed_option_set",
 *   admin_permission = "administer matterport_embed",
 *   links = {
 *     "collection" = "/admin/structure/matterport-embed-option-set",
 *     "add-form" = "/admin/structure/matterport-embed-option-set/add",
 *     "edit-form" = "/admin/structure/matterport-embed-option-set/{matterport_embed_option_set}",
 *     "delete-form" = "/admin/structure/matterport-embed-option-set/{matterport_embed_option_set}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "settings"
 *   }
 * )
 */
class MatterportEmbedOptionSet extends ConfigEntityBase implements MatterportEmbedOptionSetInterface
{

  /**
   * The matterport embed option set ID.
   *
   * @var string
   */
  protected string $id;

  /**
   * The matterport embed option set label.
   *
   * @var string
   */
  protected string $label;

  /**
   * @var array
   */
  protected array $settings = [];

  public function getSettings(): array
  {
    return $this->settings;
  }

  public function setSettings($settings)
  {
    $this->settings = $settings;
  }

  public function execute(MatterportEmbedRendererOptionSetInterface $matterportEmbedRenderer)
  {
    $url_parameters = $this->settings['url_parameters'] ?? [];
    if (is_array($url_parameters)) {
      foreach ($url_parameters as $url_parameter) {
        $matterportEmbedRenderer->addUrlParameter(
          $url_parameter['key'],
          $url_parameter['value']
        );
      }
    }

    $iframe_allows = $this->settings['iframe_allows'] ?? [];
    if (is_array($iframe_allows)) {
      foreach ($iframe_allows as $iframe_allow) {
        $matterportEmbedRenderer->addIframeAllow($iframe_allow);
      }
    }
  }
}
