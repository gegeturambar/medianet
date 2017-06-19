<?php

/**
 * Created by PhpStorm.
 * User: jthibout
 * Date: 22/03/2017
 * Time: 10:06
 */
class Histofichiers extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setTable('t_fichiers_upload');
        $this->_setPrefixe('FI_');
    }
}