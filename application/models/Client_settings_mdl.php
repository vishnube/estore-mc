<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Client_settings_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('client_settings', 'cst');
    }
}
