<?php

/**
 * Categories form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategoriesForm extends BaseCategoriesForm
{
  public function configure()
  {
  	$this->useFields(array("name", "picture"));

  	$this->widgetSchema['parent_id'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'categories',
      'add_empty' => '~ (root)',
      'order_by' => array('root_id',''),
      'method' => 'getIndentedName'
    ));
    $this->validatorSchema['parent_id'] = new sfValidatorDoctrineChoice(array(
      'required' => false,
      'model' => 'categories'
      ));
    $this->setDefault('parent_id', $this->object->getParentId());
    $this->widgetSchema->setLabel('parent_id', 'Child of');
  }

  public function updateParentIdColumn($parentId)
  {    
    $this->parentId = $parentId;
    // further action is handled in the save() method
  }  

  protected function doSave($con = null) {
    parent::doSave($con);

    $node = $this->object->getNode();

    if ($this->parentId != $this->object->getRootId() || !$node->isValidNode()){
      if (empty($this->parentId)) {
        //save as a root
        if ($node->isValidNode()) {
          $node->makeRoot($this->object['id']);
          $this->object->save($con);
        }
        else
          $this->object->getTable()->getTree()->createRoot($this->object); //calls $this->object->save internally
      }
      else {
        //form validation ensures an existing ID for $this->parentId
        $parent = $this->object->getTable()->find($this->parentId);
        $method = ($node->isValidNode() ? 'move' : 'insert') . 'AsFirstChildOf';
        $node->$method($parent); //calls $this->object->save internally
      }
    }
  }
}
