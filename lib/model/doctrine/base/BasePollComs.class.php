<?php

/**
 * BasePollComs
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property Polls $Polls
 * 
 * @method Polls    getPolls() Returns the current record's "Polls" value
 * @method PollComs setPolls() Sets the current record's "Polls" value
 * 
 * @package    zt2
 * @subpackage model
 * @author     Optix
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePollComs extends MsgMessages
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Polls', array(
             'local' => 'pollid',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}