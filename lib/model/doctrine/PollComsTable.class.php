<?php

/**
 * PollComsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PollComsTable extends MsgMessagesTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object PollComsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('PollComs');
    }
}