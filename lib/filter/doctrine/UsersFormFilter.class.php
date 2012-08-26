<?php

/**
 * Users filter form.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsersFormFilter extends BaseUsersFormFilter
{
  public function configure() {
  	$fields = array("username", "role", "created_at");

    $this->widgetSchema['created_at'] = new sfWidgetFormDateRange(array(
      'from_date' => new sfWidgetFormDate(array(), array("class" => "input-mini")),
      'to_date'   => new sfWidgetFormDate(array(), array("class" => "input-mini")),
    ));
    $this->validatorSchema['created_at'] = new sfValidatorDateRange(array(
      'required' => false,
      'from_date' => new sfValidatorDate(array('required' => false)),
      'to_date' => new sfValidatorDate(array('required' => false)),
    ));

  	$this->widgetSchema['role'] = new sfWidgetFormChoice(array(
    	  "choices" => array_merge(array("" => ""), Users::getRoles(), array("val" => "In validation"))
    	));
    	$this->validatorSchema['role'] = new sfValidatorChoice(array(
    	  "choices" => array_keys(array_merge(array("" => ""), Users::getRoles(), array("val" => "In validation"))),
    	  'required' => false
    	));

  	if (sfContext::getInstance()->getUser()->hasCredential("adm"))
  	  $fields[] = "email";
  	
  	$this->useFields($fields);
  }

  public function addRoleColumnQuery($query, $field, $value) {
    if ($value)
      $query->addWhere('role = ?', $value);
    return $query;
  }
}
