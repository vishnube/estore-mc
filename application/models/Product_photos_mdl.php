<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_photos_mdl extends My_mdl
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTable('product_photos', 'prdpt');
    }
}
