<?php

/**
 * FrmTopicsUsr filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFrmTopicsUsrFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'following' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'replied'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'lastmsgid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MsgMessages'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'following' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'replied'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'lastmsgid' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MsgMessages'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('frm_topics_usr_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FrmTopicsUsr';
  }

  public function getFields()
  {
    return array(
      'topic'     => 'Number',
      'uid'       => 'Number',
      'following' => 'Boolean',
      'replied'   => 'Boolean',
      'lastmsgid' => 'ForeignKey',
    );
  }
}
