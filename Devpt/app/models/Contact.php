<?php

/**
 * Created by PhpStorm.
 * User: jthibout
 * Date: 22/03/2017
 * Time: 10:06
 */
class Contact extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setTable('t_motifs_contact_hors_connection');
        $this->_setPrefixe('MCHC_');
    }

    public function searchBy($motif, $field){
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' where `'.$field.'` =  ? ';

        $statement = $this->_dbh->prepare($sql);
        $statement->execute(array($motif));

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}