<?php

namespace Drupal\Tests\matterport_embed\Functional;

use Drupal\Tests\BrowserTestBase;

class MatterportEmbedUninstallTest extends BrowserTestBase
{

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = ['matterport_embed'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  public function testUninstall()
  {
    \Drupal::service('module_installer')->uninstall(['matterport_embed']);
  }

}
