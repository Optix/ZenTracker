<?php

/**
 * PmTopics form.
 *
 * @package    zt2
 * @subpackage form
 * @author     Optix
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PmTopicsForm extends BasePmTopicsForm {
  public function configure() {
	// We don't need all this stuff
	unset($this->widgetSchema['created_at'], $this->widgetSchema['updated_at'], $this->widgetSchema['deleted_at'], $this->widgetSchema['slug']);
	unset($this->validatorSchema['created_at'], $this->validatorSchema['updated_at'], $this->validatorSchema['deleted_at'], $this->validatorSchema['slug']);
	// Calling message form when creating new topic
	if ($this->isNew()) {
	  $this->embedForm('msg', new PmMessagesForm());
	  $this->widgetSchema['recipients'] = new sfWidgetFormInputText();
	  $this->validatorSchema['recipients'] = new sfValidatorString();
	  $this->widgetSchema->setLabel('recipients', 'Recipients');
	  $this->getWidgetSchema()->moveField('recipients', sfWidgetFormSchema::AFTER, 'title');
	}
	
	// We require an title
	$this->widgetSchema['title'] = new sfWidgetFormInputText(array(), array("required" => "required"));
  }
}
