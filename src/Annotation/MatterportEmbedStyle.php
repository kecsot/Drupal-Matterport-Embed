<?php

namespace Drupal\matterport_embed\Annotation;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Defines matterport_embed_style annotation object.
 *
 * @Annotation
 */
class MatterportEmbedStyle extends Plugin
{

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The description of the plugin.
   *
   * @var Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
