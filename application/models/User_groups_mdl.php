<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_groups_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('user_groups', 'ugrp');
    }
}
