<?php

/**
 * FrmTopicsUsr form base class.
 *
 * @method FrmTopicsUsr getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFrmTopicsUsrForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'topic'     => new sfWidgetFormInputHidden(),
      'uid'       => new sfWidgetFormInputHidden(),
      'following' => new sfWidgetFormInputCheckbox(),
      'replied'   => new sfWidgetFormInputCheckbox(),
      'lastmsgid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MsgMessages'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'topic'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('topic')), 'empty_value' => $this->getObject()->get('topic'), 'required' => false)),
      'uid'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('uid')), 'empty_value' => $this->getObject()->get('uid'), 'required' => false)),
      'following' => new sfValidatorBoolean(array('required' => false)),
      'replied'   => new sfValidatorBoolean(array('required' => false)),
      'lastmsgid' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MsgMessages'))),
    ));

    $this->widgetSchema->setNameFormat('frm_topics_usr[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FrmTopicsUsr';
  }

}
