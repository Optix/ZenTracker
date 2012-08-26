<?php

/**
 * TorrentsPeers form base class.
 *
 * @method TorrentsPeers getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTorrentsPeersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hash'       => new sfWidgetFormInputHidden(),
      'pid'        => new sfWidgetFormInputHidden(),
      'peer_id'    => new sfWidgetFormInputText(),
      'uid'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'ip'         => new sfWidgetFormInputText(),
      'port'       => new sfWidgetFormInputText(),
      'download'   => new sfWidgetFormInputText(),
      'upload'     => new sfWidgetFormInputText(),
      'remain'     => new sfWidgetFormInputText(),
      'useragent'  => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'hash'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('hash')), 'empty_value' => $this->getObject()->get('hash'), 'required' => false)),
      'pid'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('pid')), 'empty_value' => $this->getObject()->get('pid'), 'required' => false)),
      'peer_id'    => new sfValidatorString(array('max_length' => 40)),
      'uid'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'ip'         => new sfValidatorString(array('max_length' => 45)),
      'port'       => new sfValidatorInteger(),
      'download'   => new sfValidatorInteger(),
      'upload'     => new sfValidatorInteger(),
      'remain'     => new sfValidatorInteger(),
      'useragent'  => new sfValidatorString(array('max_length' => 45)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('torrents_peers[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TorrentsPeers';
  }

}
