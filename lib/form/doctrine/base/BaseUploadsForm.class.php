<?php

/**
 * Uploads form base class.
 *
 * @method Uploads getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUploadsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'hash'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeers'), 'add_empty' => true)),
      'title'       => new sfWidgetFormInputText(),
      'cat'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Categories'), 'add_empty' => false)),
      'description' => new sfWidgetFormTextarea(),
      'nfo'         => new sfWidgetFormTextarea(),
      'author'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'url'         => new sfWidgetFormInputText(),
      'size'        => new sfWidgetFormInputText(),
      'minlevel'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'slug'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hash'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TorrentsPeers'), 'required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 100)),
      'cat'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Categories'))),
      'description' => new sfValidatorString(),
      'nfo'         => new sfValidatorString(array('required' => false)),
      'author'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'url'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'size'        => new sfValidatorInteger(),
      'minlevel'    => new sfValidatorInteger(),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'slug'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Uploads', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('uploads[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Uploads';
  }

}
