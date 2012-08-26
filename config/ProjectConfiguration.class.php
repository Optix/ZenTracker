<?php
if (file_exists('../lib/symfony/lib/autoload/sfCoreAutoload.class.php'))
  require_once '../lib/symfony/lib/autoload/sfCoreAutoload.class.php';
else
  require_once 'lib/symfony/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfThumbnailPlugin');
    $this->enablePlugins('sfImageTransformPlugin');
    $this->enablePlugins('sfFormExtraPlugin');
    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }
}