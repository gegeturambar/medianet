<?php

/**
 * Created by PhpStorm.
 * User: jthibout
 * Date: 22/03/2017
 * Time: 10:06
 */
class Histomail extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setTable('t_histo_emails');
        $this->_setPrefixe('HEM_');
    }
}