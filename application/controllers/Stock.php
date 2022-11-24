<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		check_not_login();
	}

    public function stock_in_data()
    {
        echo "stock in";
    }

    public function stcok_in_add()
    {
        
    }

}