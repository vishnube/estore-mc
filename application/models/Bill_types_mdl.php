<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bill_types_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('bill_types', 'btp');
    }
}
