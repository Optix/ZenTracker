<?php

/**
 * MsgMessages form base class.
 *
 * @method MsgMessages getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMsgMessagesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'author'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'content'    => new sfWidgetFormTextarea(),
      'module'     => new sfWidgetFormInputText(),
      'tid'        => new sfWidgetFormInputText(),
      'pmid'       => new sfWidgetFormInputText(),
      'shtid'      => new sfWidgetFormInputText(),
      'pollid'     => new sfWidgetFormInputText(),
      'upid'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'deleted_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'author'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'content'    => new sfValidatorString(),
      'module'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tid'        => new sfValidatorInteger(array('required' => false)),
      'pmid'       => new sfValidatorInteger(array('required' => false)),
      'shtid'      => new sfValidatorInteger(array('required' => false)),
      'pollid'     => new sfValidatorInteger(array('required' => false)),
      'upid'       => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'deleted_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('msg_messages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MsgMessages';
  }

}
