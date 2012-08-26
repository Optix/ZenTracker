<?php

/**
 * News form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class NewsForm extends BaseNewsForm
{
  public function configure() {
    $this->useFields(array('title', 'description'));
    $this->validatorSchema['title'] = new sfValidatorString(array(
       'min_length' => 3,
       'max_length' => 45,
       'trim' => true,
       'required' => true
    ));
    
    $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array("class" => "tinymce redacmsg", "style" => "height: 200px"));
    $this->validatorSchema['description'] = new sfValidatorString(array(
        "min_length" => 1,
    ));
  }
  
  public function updateObject($values = null) {
    $object = parent::updateObject($values);
    // Setting author
    $object->setAuthor(sfContext::getInstance()->getUser()->getAttribute("id"));

    return $object;
  }
}
