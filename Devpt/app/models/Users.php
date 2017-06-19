<?php

/**
 * Created by PhpStorm.
 * User: jthibout
 * Date: 22/03/2017
 * Time: 10:06
 */
class Users extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setTable('user');
        $this->_setPrefixe('');
    }

    public function fetchOneByMail($mail){
        $sql = 'select * from ' . $this->_table;
        $sql .= ' where '.$this->_prefixe.'mail = ?';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($mail));

        return $statement->fetch(PDO::FETCH_OBJ);
    }
}