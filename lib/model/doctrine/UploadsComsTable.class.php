<?php

/**
 * UploadsComsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UploadsComsTable extends MsgMessagesTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object UploadsComsTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UploadsComs');
    }
}