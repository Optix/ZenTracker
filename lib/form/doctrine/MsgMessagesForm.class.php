<?php

/**
 * MsgMessages form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MsgMessagesForm extends BaseMsgMessagesForm
{
  public function configure() {
    $this->widgetSchema['content'] = new sfWidgetFormTextarea(array(), array("class" => "tinymce redacmsg", "style" => "height: 200px"));
    $this->validatorSchema['content'] = new sfValidatorString(array(
        "min_length" => 1,
    ));
    
    /*$this->widgetSchema['modkey'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['modkey'] = new sfValidatorString(array('required' => false));*/
  }
  
  public function updateObject($values = null) {
    $object = parent::updateObject($values);    
    
    // If it's a new comment
    if ($this->isNew()) {
      // Storing current author
      $object->setAuthor(sfContext::getInstance()->getUser()->getAttribute("id"));
    }
    
    return $object;
  }
}
