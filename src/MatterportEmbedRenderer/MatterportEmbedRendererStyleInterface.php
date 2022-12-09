<?php

namespace Drupal\matterport_embed\MatterportEmbedRenderer;

interface MatterportEmbedRendererStyleInterface
{
  public function setWidth(int $width);

  public function setHeight(int $height);

  public function addClass(string $class);

  public function addLibrary(string $library);

  public function addWrapperClass(string $class);

  public function addWrapperInlineStyle(string $style);

}
