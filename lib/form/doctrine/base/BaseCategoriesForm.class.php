<?php

/**
 * Categories form base class.
 *
 * @method Categories getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategoriesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'name'    => new sfWidgetFormInputText(),
      'picture' => new sfWidgetFormInputText(),
      'root_id' => new sfWidgetFormInputText(),
      'lft'     => new sfWidgetFormInputText(),
      'rgt'     => new sfWidgetFormInputText(),
      'level'   => new sfWidgetFormInputText(),
      'slug'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'    => new sfValidatorString(array('max_length' => 45)),
      'picture' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'root_id' => new sfValidatorInteger(array('required' => false)),
      'lft'     => new sfValidatorInteger(array('required' => false)),
      'rgt'     => new sfValidatorInteger(array('required' => false)),
      'level'   => new sfValidatorInteger(array('required' => false)),
      'slug'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Categories', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('categories[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Categories';
  }

}
