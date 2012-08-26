<?php

/**
 * FrmForums filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFrmForumsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cat'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FrmCats'), 'add_empty' => true)),
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'   => new sfWidgetFormFilterInput(),
      'minroleread'   => new sfWidgetFormFilterInput(),
      'minlevelread'  => new sfWidgetFormFilterInput(),
      'minrolewrite'  => new sfWidgetFormFilterInput(),
      'minlevelwrite' => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'slug'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cat'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('FrmCats'), 'column' => 'id')),
      'name'          => new sfValidatorPass(array('required' => false)),
      'description'   => new sfValidatorPass(array('required' => false)),
      'minroleread'   => new sfValidatorPass(array('required' => false)),
      'minlevelread'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'minrolewrite'  => new sfValidatorPass(array('required' => false)),
      'minlevelwrite' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'slug'          => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('frm_forums_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FrmForums';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'cat'           => 'ForeignKey',
      'name'          => 'Text',
      'description'   => 'Text',
      'minroleread'   => 'Text',
      'minlevelread'  => 'Number',
      'minrolewrite'  => 'Text',
      'minlevelwrite' => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'slug'          => 'Text',
    );
  }
}
