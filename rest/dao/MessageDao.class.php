<?php
require_once 'BaseDao.class.php';
class MessageDao extends BaseDao
{
    public $table="messages";
    public function __construct()
    {
        parent:: __construct($this->table);
    }

    
}

?>