<?php

/**
 * PmParticipants filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePmParticipantsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'readed'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'deleted' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'readed'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'deleted' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('pm_participants_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PmParticipants';
  }

  public function getFields()
  {
    return array(
      'mpid'    => 'Number',
      'mpmid'   => 'Number',
      'readed'  => 'Boolean',
      'deleted' => 'Boolean',
    );
  }
}
