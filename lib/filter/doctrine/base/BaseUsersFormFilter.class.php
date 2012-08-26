<?php

/**
 * Users filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUsersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parents'), 'add_empty' => true)),
      'username'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'passexpires' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'random'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'avatar'      => new sfWidgetFormFilterInput(),
      'lastvisit'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'pid'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeersOffset'), 'add_empty' => true)),
      'role'        => new sfWidgetFormFilterInput(),
      'active'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'reason'      => new sfWidgetFormFilterInput(),
      'ban_expire'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'description' => new sfWidgetFormFilterInput(),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'parent'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parents'), 'column' => 'id')),
      'username'    => new sfValidatorPass(array('required' => false)),
      'password'    => new sfValidatorPass(array('required' => false)),
      'passexpires' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'random'      => new sfValidatorPass(array('required' => false)),
      'email'       => new sfValidatorPass(array('required' => false)),
      'avatar'      => new sfValidatorPass(array('required' => false)),
      'lastvisit'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'pid'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TorrentsPeersOffset'), 'column' => 'hash')),
      'role'        => new sfValidatorPass(array('required' => false)),
      'active'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'reason'      => new sfValidatorPass(array('required' => false)),
      'ban_expire'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'description' => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Users';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'parent'      => 'ForeignKey',
      'username'    => 'Text',
      'password'    => 'Text',
      'passexpires' => 'Date',
      'random'      => 'Text',
      'email'       => 'Text',
      'avatar'      => 'Text',
      'lastvisit'   => 'Date',
      'pid'         => 'ForeignKey',
      'role'        => 'Text',
      'active'      => 'Boolean',
      'reason'      => 'Text',
      'ban_expire'  => 'Date',
      'description' => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
      'slug'        => 'Text',
    );
  }
}
