<?php

namespace Drupal\matterport_embed;

use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererStyleInterface;

/**
 * Interface for matterport_embed_style plugins.
 */
interface MatterportEmbedStylePluginInterface
{
  public function execute(MatterportEmbedRendererStyleInterface $matterportEmbedRenderer);
}
