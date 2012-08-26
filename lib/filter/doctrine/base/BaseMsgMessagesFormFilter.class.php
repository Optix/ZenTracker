<?php

/**
 * MsgMessages filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMsgMessagesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'author'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'content'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'module'     => new sfWidgetFormFilterInput(),
      'tid'        => new sfWidgetFormFilterInput(),
      'pmid'       => new sfWidgetFormFilterInput(),
      'shtid'      => new sfWidgetFormFilterInput(),
      'pollid'     => new sfWidgetFormFilterInput(),
      'upid'       => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'deleted_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'author'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'content'    => new sfValidatorPass(array('required' => false)),
      'module'     => new sfValidatorPass(array('required' => false)),
      'tid'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pmid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shtid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pollid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'upid'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'deleted_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('msg_messages_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MsgMessages';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'author'     => 'ForeignKey',
      'content'    => 'Text',
      'module'     => 'Text',
      'tid'        => 'Number',
      'pmid'       => 'Number',
      'shtid'      => 'Number',
      'pollid'     => 'Number',
      'upid'       => 'Number',
      'created_at' => 'Date',
      'updated_at' => 'Date',
      'deleted_at' => 'Date',
    );
  }
}
