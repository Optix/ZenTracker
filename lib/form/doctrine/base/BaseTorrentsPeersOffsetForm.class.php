<?php

/**
 * TorrentsPeersOffset form base class.
 *
 * @method TorrentsPeersOffset getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTorrentsPeersOffsetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hash'     => new sfWidgetFormInputHidden(),
      'pid'      => new sfWidgetFormInputHidden(),
      'download' => new sfWidgetFormInputText(),
      'upload'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'hash'     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('hash')), 'empty_value' => $this->getObject()->get('hash'), 'required' => false)),
      'pid'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('pid')), 'empty_value' => $this->getObject()->get('pid'), 'required' => false)),
      'download' => new sfValidatorInteger(),
      'upload'   => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('torrents_peers_offset[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TorrentsPeersOffset';
  }

}
