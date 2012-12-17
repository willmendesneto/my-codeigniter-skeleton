<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GroceryCrud extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('grocery_CRUD');
	}


	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function _example_output($output = null)
	{
		$this->load->view('grocery-crud/example', $output);
	}

}