<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('FrmTopics', 'doctrine');

/**
 * BaseFrmTopics
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $forum
 * @property string $title
 * @property boolean $is_locked
 * @property boolean $is_important
 * @property FrmForums $FrmForums
 * @property Doctrine_Collection $FrmTopicsUsr
 * @property Doctrine_Collection $FrmMessages
 * 
 * @method integer             getForum()        Returns the current record's "forum" value
 * @method string              getTitle()        Returns the current record's "title" value
 * @method boolean             getIsLocked()     Returns the current record's "is_locked" value
 * @method boolean             getIsImportant()  Returns the current record's "is_important" value
 * @method FrmForums           getFrmForums()    Returns the current record's "FrmForums" value
 * @method Doctrine_Collection getFrmTopicsUsr() Returns the current record's "FrmTopicsUsr" collection
 * @method Doctrine_Collection getFrmMessages()  Returns the current record's "FrmMessages" collection
 * @method FrmTopics           setForum()        Sets the current record's "forum" value
 * @method FrmTopics           setTitle()        Sets the current record's "title" value
 * @method FrmTopics           setIsLocked()     Sets the current record's "is_locked" value
 * @method FrmTopics           setIsImportant()  Sets the current record's "is_important" value
 * @method FrmTopics           setFrmForums()    Sets the current record's "FrmForums" value
 * @method FrmTopics           setFrmTopicsUsr() Sets the current record's "FrmTopicsUsr" collection
 * @method FrmTopics           setFrmMessages()  Sets the current record's "FrmMessages" collection
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFrmTopics extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('frm_topics');
        $this->hasColumn('forum', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 60, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 60,
             ));
        $this->hasColumn('is_locked', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('is_important', 'boolean', null, array(
             'type' => 'boolean',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('FrmForums', array(
             'local' => 'forum',
             'foreign' => 'id'));

        $this->hasMany('FrmTopicsUsr', array(
             'local' => 'id',
             'foreign' => 'topic'));

        $this->hasMany('FrmMessages', array(
             'local' => 'id',
             'foreign' => 'tid'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'unique' => true,
             'fields' => 
             array(
              0 => 'title',
             ),
             'canUpdate' => true,
             ));
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
        $this->actAs($sluggable0);
    }
}