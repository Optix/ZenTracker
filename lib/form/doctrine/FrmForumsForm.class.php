<?php

/**
 * FrmForums form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FrmForumsForm extends BaseFrmForumsForm
{
  public function configure()
  {
  	$levels = array("" => "");
  	foreach (Users::getLevels() as $lvl => $score) {
  		if ($lvl > 0)
  			$levels[$lvl] = "Level ".$lvl;
  	}

  	$this->useFields(array("cat", "name", "description", "minroleread", "minlevelread", "minrolewrite", "minlevelwrite"));
	
  	$this->widgetSchema['minlevelread'] = $this->widgetSchema['minlevelwrite'] = new sfWidgetFormChoice(array(
  		"choices" => $levels
  	));
  	$this->widgetSchema['minroleread'] = $this->widgetSchema['minrolewrite'] = new sfWidgetFormChoice(array(
  		"choices" => array_merge(array("" => ""), Users::getRoles())
  	));
  }
}
