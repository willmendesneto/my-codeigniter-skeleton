<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends CI_Controller {

	/**
	 * Construtor da classe
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->grocery_crud->set_theme('twitter-bootstrap');
	}

	public function index()
	{
		try{
			$this->grocery_crud->set_table('employees');
			$this->grocery_crud->set_relation('officeCode','offices','city');
			$this->grocery_crud->display_as('officeCode','Office City');
			$this->grocery_crud->set_subject('Employee');

			$this->grocery_crud->required_fields('lastName');
			$this->grocery_crud->set_field_upload('file_url','assets/uploads/files');

			$output = $this->grocery_crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function _example_output($output = null)
	{
		$this->load->view('templates/template', $output);
	}

}