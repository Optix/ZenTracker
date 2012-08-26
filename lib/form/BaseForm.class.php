<?php

/**
 * Base project form.
 * 
 * @package    zt2
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{

public function setup() {
  parent::setup();
  foreach ($this->getWidgetSchema()->getFields() as $name => $widget)
  {
    $classname = get_class($widget);
    if ($classname == 'sfWidgetFormInputText') {
      if (($val = $this->getValidator($name)) && ($len = $val->getOption('max_length'))) {
        $widget->setAttribute('maxlength', $len);
      }
    }
  }
}

}
