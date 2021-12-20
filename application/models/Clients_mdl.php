<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clients_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('clients', 'clnt');
    }
}
