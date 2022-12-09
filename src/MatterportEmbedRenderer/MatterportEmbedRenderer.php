<?php

namespace Drupal\matterport_embed\MatterportEmbedRenderer;

use Drupal\Core\Url;

class MatterportEmbedRenderer extends MatterportEmbedRendererBase
{

  public function build($model_id): array
  {
    $build = parent::build($model_id);

    $url = Url::fromUri('https://my.matterport.com/show');
    $this->urlParameters['m'] = $model_id;
    $url->setOption('query', $this->urlParameters);

    $build += [
      '#theme' => "matterport_embed",
      '#width' => $this->width,
      '#height' => $this->height,
      '#url' => $url->toString(),
      '#classes' => $this->classes,
      '#wrapper_classes' => $this->wrapperClasses,
      '#wrapper_inline_styles' => $this->wrapperInlineStyles,
      '#iframe_allows' => $this->iframeAllows,
    ];

    $build['#attached']['library'] = [];
    foreach ($this->libraries as $library) {
      $build['#attached']['library'][] = $library;
    }

    return $build;
  }
}
