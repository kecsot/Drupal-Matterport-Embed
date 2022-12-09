<?php

namespace Drupal\matterport_embed\MatterportEmbedRenderer;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;

abstract class MatterportEmbedRendererBase implements RefinableCacheableDependencyInterface, MatterportEmbedRendererStyleInterface, MatterportEmbedRendererOptionSetInterface
{
  use RefinableCacheableDependencyTrait;

  protected ?int $width = null;
  protected ?int $height = null;
  protected array $classes = [];
  protected array $wrapperClasses = [];
  protected array $wrapperInlineStyles = [];
  protected array $libraries = [];
  protected array $urlParameters = [];
  protected array $iframeAllows = [];

  public function setWidth(int $width)
  {
    $this->width = $width;
  }

  public function setHeight(int $height)
  {
    $this->height = $height;
  }

  public function addClass(string $class)
  {
    $this->classes[] = $class;
  }

  public function addLibrary(string $library)
  {
    $this->libraries[] = $library;
  }

  public function addUrlParameter(string $key, string $value)
  {
    $this->urlParameters[$key] = $value;
  }

  public function addIframeAllow(string $value)
  {
    $this->iframeAllows[] = $value;
  }

  public function addWrapperInlineStyle(string $style)
  {
    $this->wrapperInlineStyles[] = $style;
  }

  public function addWrapperClass(string $class)
  {
    $this->wrapperClasses[] = $class;
  }

  public function build($model_id): array
  {
    return [
      '#cache' => [
        'contexts' => $this->cacheContexts,
        'tags' => $this->cacheTags,
        'max-age' => $this->cacheMaxAge,
      ],
    ];
  }

}
