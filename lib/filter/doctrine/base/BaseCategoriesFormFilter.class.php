<?php

/**
 * Categories filter form base class.
 *
 * @package    zt2
 * @subpackage filter
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCategoriesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'picture' => new sfWidgetFormFilterInput(),
      'root_id' => new sfWidgetFormFilterInput(),
      'lft'     => new sfWidgetFormFilterInput(),
      'rgt'     => new sfWidgetFormFilterInput(),
      'level'   => new sfWidgetFormFilterInput(),
      'slug'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'    => new sfValidatorPass(array('required' => false)),
      'picture' => new sfValidatorPass(array('required' => false)),
      'root_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('categories_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Categories';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'name'    => 'Text',
      'picture' => 'Text',
      'root_id' => 'Number',
      'lft'     => 'Number',
      'rgt'     => 'Number',
      'level'   => 'Number',
      'slug'    => 'Text',
    );
  }
}
