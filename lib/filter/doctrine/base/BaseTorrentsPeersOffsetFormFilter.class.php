<?php

/**
 * TorrentsPeersOffset filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTorrentsPeersOffsetFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'download' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'upload'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'download' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'upload'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('torrents_peers_offset_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TorrentsPeersOffset';
  }

  public function getFields()
  {
    return array(
      'hash'     => 'Text',
      'pid'      => 'Text',
      'download' => 'Number',
      'upload'   => 'Number',
    );
  }
}
