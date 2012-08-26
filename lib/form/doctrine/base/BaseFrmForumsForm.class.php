<?php

/**
 * FrmForums form base class.
 *
 * @method FrmForums getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFrmForumsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'cat'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FrmCats'), 'add_empty' => false)),
      'name'          => new sfWidgetFormInputText(),
      'description'   => new sfWidgetFormInputText(),
      'minroleread'   => new sfWidgetFormInputText(),
      'minlevelread'  => new sfWidgetFormInputText(),
      'minrolewrite'  => new sfWidgetFormInputText(),
      'minlevelwrite' => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
      'slug'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'cat'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FrmCats'))),
      'name'          => new sfValidatorString(array('max_length' => 60)),
      'description'   => new sfValidatorString(array('max_length' => 140, 'required' => false)),
      'minroleread'   => new sfValidatorString(array('max_length' => 3, 'required' => false)),
      'minlevelread'  => new sfValidatorInteger(array('required' => false)),
      'minrolewrite'  => new sfValidatorString(array('max_length' => 3, 'required' => false)),
      'minlevelwrite' => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
      'slug'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'FrmForums', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('frm_forums[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FrmForums';
  }

}
