<?php

/**
 * UploadsHits form base class.
 *
 * @method UploadsHits getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUploadsHitsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'upid'       => new sfWidgetFormInputHidden(),
      'uid'        => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'upid'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('upid')), 'empty_value' => $this->getObject()->get('upid'), 'required' => false)),
      'uid'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('uid')), 'empty_value' => $this->getObject()->get('uid'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('uploads_hits[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UploadsHits';
  }

}
