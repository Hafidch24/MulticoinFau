<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Maintenance extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if($this->data['settings']['status'] != 'off') {
			return redirect(site_url());
		}	    
		$this->data['page'] = 'Maintenance';

		$this->render("maintenance", $this->data);
	}

}
