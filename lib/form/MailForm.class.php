<?php

class MailForm extends sfForm {
  public function configure() {
  	$this->widgetSchema['title'] = new sfWidgetFormInputText();
  	$this->validatorSchema['title'] = new sfValidatorString(array(
  	  "min_length" => 1
  	));

  	$this->widgetSchema['content'] = new sfWidgetFormTextarea(array(), array("class" => "tinymce redacmsg", "style" => "height: 200px"));
    $this->validatorSchema['content'] = new sfValidatorString(array(
      "min_length" => 1,
    ));
    $this->widgetSchema->setNameFormat('mail[%s]');
  }
}