<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offices extends CI_Controller {

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
			$this->grocery_crud->set_table('offices');
			$this->grocery_crud->set_subject('Office');
			$this->grocery_crud->required_fields('city');
			$this->grocery_crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $this->grocery_crud->render();
			$this->load->view('templates/template_crud', $output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}