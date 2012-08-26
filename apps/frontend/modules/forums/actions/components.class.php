<?php

/**
 * forums components.
 *
 * @package    zt2
 * @subpackage forums
 * @author     Optix
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class forumsComponents extends sfComponents {
  
  public function executeLastreplies() {
    $this->msg = Doctrine::getTable("FrmTopics")->getLastestUpdatedTopics();
  }

  public function executeManage() {
  	$this->cat = new FrmCatsForm();
  	$this->frm = new FrmForumsForm();
  }
  
  public function executeNewtopic() {
	  $this->form = new FrmTopicsForm();
    $this->form->setDefault('forum', $this->forum->getId());
  }
  public function executeEdittopic($arg) {
    $this->form = new FrmTopicsForm($this->obj);
  }

}
