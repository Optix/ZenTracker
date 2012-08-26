<?php

/**
 * TorrentsPeers filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTorrentsPeersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'peer_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'uid'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'ip'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'port'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'download'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'upload'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'remain'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'useragent'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'peer_id'    => new sfValidatorPass(array('required' => false)),
      'uid'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'ip'         => new sfValidatorPass(array('required' => false)),
      'port'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'download'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'upload'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'remain'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'useragent'  => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('torrents_peers_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TorrentsPeers';
  }

  public function getFields()
  {
    return array(
      'hash'       => 'Text',
      'pid'        => 'Text',
      'peer_id'    => 'Text',
      'uid'        => 'ForeignKey',
      'ip'         => 'Text',
      'port'       => 'Number',
      'download'   => 'Number',
      'upload'     => 'Number',
      'remain'     => 'Number',
      'useragent'  => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
