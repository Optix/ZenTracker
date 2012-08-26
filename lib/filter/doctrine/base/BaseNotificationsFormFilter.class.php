<?php

/**
 * Notifications filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseNotificationsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'owner'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'uid'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'readed'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'picture'    => new sfWidgetFormFilterInput(),
      'message'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link'       => new sfWidgetFormFilterInput(),
      'extract'    => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'owner'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'uid'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'readed'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'picture'    => new sfValidatorPass(array('required' => false)),
      'message'    => new sfValidatorPass(array('required' => false)),
      'link'       => new sfValidatorPass(array('required' => false)),
      'extract'    => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('notifications_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Notifications';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'owner'      => 'Number',
      'uid'        => 'ForeignKey',
      'readed'     => 'Boolean',
      'picture'    => 'Text',
      'message'    => 'Text',
      'link'       => 'Text',
      'extract'    => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
