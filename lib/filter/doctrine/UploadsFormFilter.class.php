<?php

/**
 * Uploads filter form.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UploadsFormFilter extends BaseUploadsFormFilter
{
  public function configure()
  {
    $this->useFields(array("title", "description", "cat", "author", "minlevel", "size"));
    
    // SIZE
    $this->widgetSchema['size'] = new sfWidgetFormInput(array(
        "type" => "range",    
    ), array(
        "max" => Uploads::getMaxSize()+250, 
        "val" => Uploads::getMaxSize()+250,
        "step" => 250,
        "rel" => "tooltip", 
        "title" => "Maximum size", 
    ));
    $this->validatorSchema['size'] = new sfValidatorInteger(array("required" => false));


    // UPLOADER
    $uploaders = Uploads::getUploaders();
    $uploaders[""] = "";
    $this->widgetSchema['author'] = new sfWidgetFormChoice(array(
      "choices" => $uploaders,
      "label" => "Uploader"
    ));
	  $this->validatorSchema['author'] = new sfValidatorChoice(array(
      'choices' => array_keys($uploaders),
      'required' => false
    ));
    $this->widgetSchema->setDefault("author", "");

    // CATEGORIES
    $qcats = Doctrine_Query::create()->from("Categories");
    $this->widgetSchema['cat'] = new sfWidgetFormDoctrineChoice(array(
      'model'     => 'categories',
      "expanded" => false,
      "multiple" => false,
      "query" => $qcats,
      'method' => 'getIndentedName',
      "add_empty" => true,
      'order_by' => array('root_id, lft',''),
    ));
    $this->validatorSchema['cat'] = new sfValidatorDoctrineChoice(array(
      "model" => "Categories",
      "multiple" => false,
      "required" => false,
    ));
	  $this->widgetSchema['cat']->setLabel("Category");


    // MIN LEVEL
    $levels = array();
    foreach (Users::getLevels() as $lvl => $score) {
      if ($lvl > 0)
        $levels[$lvl] = "Level ".$lvl;
    }
    // If freeleech is allowed, adding this choice
    if (sfConfig::get('app_bt_allowfreeleech', true))
      $levels[0] = "Freeleech";
    ksort($levels);
    $this->widgetSchema['minlevel'] = new sfWidgetFormChoice(array(
      "choices" => array_merge(array(""=>""), $levels)
    ));
    $this->validatorSchema['minlevel'] = new sfValidatorChoice(array(
      "choices" => array_keys(array_merge(array(""=>""), $levels)),
      'required' => false
    ));
  }

  public function addSizeColumnQuery($query, $field, $value) {
    if ($value) {
      $query->addWhere('size <= ?', $value*1024*1024);
    }
    return $query;
  }

  public function addMinlevelColumnQuery($query, $field, $value) {
    if ($value !== FALSE) {
      $query->addWhere('minlevel <= ?', $value);
    }
    return $query;
  }
}
