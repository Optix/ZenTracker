<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Notifications', 'doctrine');

/**
 * BaseNotifications
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $owner
 * @property integer $uid
 * @property boolean $readed
 * @property string $picture
 * @property string $message
 * @property string $link
 * @property string $extract
 * @property Users $Users
 * 
 * @method integer       getOwner()   Returns the current record's "owner" value
 * @method integer       getUid()     Returns the current record's "uid" value
 * @method boolean       getReaded()  Returns the current record's "readed" value
 * @method string        getPicture() Returns the current record's "picture" value
 * @method string        getMessage() Returns the current record's "message" value
 * @method string        getLink()    Returns the current record's "link" value
 * @method string        getExtract() Returns the current record's "extract" value
 * @method Users         getUsers()   Returns the current record's "Users" value
 * @method Notifications setOwner()   Sets the current record's "owner" value
 * @method Notifications setUid()     Sets the current record's "uid" value
 * @method Notifications setReaded()  Sets the current record's "readed" value
 * @method Notifications setPicture() Sets the current record's "picture" value
 * @method Notifications setMessage() Sets the current record's "message" value
 * @method Notifications setLink()    Sets the current record's "link" value
 * @method Notifications setExtract() Sets the current record's "extract" value
 * @method Notifications setUsers()   Sets the current record's "Users" value
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNotifications extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('notifications');
        $this->hasColumn('owner', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('uid', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('readed', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('picture', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('message', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '',
             ));
        $this->hasColumn('link', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('extract', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Users', array(
             'local' => 'uid',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}