<?php

namespace Drupal\matterport_embed\MatterportEmbedRenderer;

interface MatterportEmbedRendererOptionSetInterface
{
  public function addUrlParameter(string $key, string $value);

  public function addIframeAllow(string $value);
}
