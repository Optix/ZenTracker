<?php

/**
 * Uploads filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseUploadsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hash'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeers'), 'add_empty' => true)),
      'title'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'cat'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Categories'), 'add_empty' => true)),
      'description' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nfo'         => new sfWidgetFormFilterInput(),
      'author'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'url'         => new sfWidgetFormFilterInput(),
      'size'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'minlevel'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'hash'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TorrentsPeers'), 'column' => 'hash')),
      'title'       => new sfValidatorPass(array('required' => false)),
      'cat'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Categories'), 'column' => 'id')),
      'description' => new sfValidatorPass(array('required' => false)),
      'nfo'         => new sfValidatorPass(array('required' => false)),
      'author'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'url'         => new sfValidatorPass(array('required' => false)),
      'size'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'minlevel'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('uploads_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Uploads';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'hash'        => 'ForeignKey',
      'title'       => 'Text',
      'cat'         => 'ForeignKey',
      'description' => 'Text',
      'nfo'         => 'Text',
      'author'      => 'ForeignKey',
      'url'         => 'Text',
      'size'        => 'Number',
      'minlevel'    => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
      'slug'        => 'Text',
    );
  }
}
