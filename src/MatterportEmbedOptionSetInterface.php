<?php

namespace Drupal\matterport_embed;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\matterport_embed\MatterportEmbedRenderer\MatterportEmbedRendererOptionSetInterface;

interface MatterportEmbedOptionSetInterface extends ConfigEntityInterface
{
  public function setSettings($settings);

  public function getSettings(): array;

  public function execute(MatterportEmbedRendererOptionSetInterface $matterportEmbedRenderer);
}
