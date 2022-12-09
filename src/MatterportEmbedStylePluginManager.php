<?php

namespace Drupal\matterport_embed;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Traversable;

/**
 * MatterportEmbedStyle plugin manager.
 */
class MatterportEmbedStylePluginManager extends DefaultPluginManager
{

  /**
   * Constructs MatterportEmbedStylePluginManager object.
   *
   * @param Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler)
  {
    parent::__construct(
      'Plugin/MatterportEmbedStyle',
      $namespaces,
      $module_handler,
      'Drupal\matterport_embed\MatterportEmbedStylePluginInterface',
      'Drupal\matterport_embed\Annotation\MatterportEmbedStyle'
    );
    $this->alterInfo('matterport_embed_style_info');
    $this->setCacheBackend($cache_backend, 'matterport_embed_style_plugins');
  }

}
