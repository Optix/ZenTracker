<?php

/**
 * FrmTopics form base class.
 *
 * @method FrmTopics getObject() Returns the current form's model object
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFrmTopicsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'forum'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FrmForums'), 'add_empty' => false)),
      'title'        => new sfWidgetFormInputText(),
      'is_locked'    => new sfWidgetFormInputCheckbox(),
      'is_important' => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'deleted_at'   => new sfWidgetFormDateTime(),
      'slug'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'forum'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FrmForums'))),
      'title'        => new sfValidatorString(array('max_length' => 60)),
      'is_locked'    => new sfValidatorBoolean(array('required' => false)),
      'is_important' => new sfValidatorBoolean(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'deleted_at'   => new sfValidatorDateTime(array('required' => false)),
      'slug'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'FrmTopics', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('frm_topics[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FrmTopics';
  }

}
