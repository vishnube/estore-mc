<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Member_categories_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('member_categories', 'mbrcat');
    }
}
