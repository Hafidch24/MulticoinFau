<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}
}
