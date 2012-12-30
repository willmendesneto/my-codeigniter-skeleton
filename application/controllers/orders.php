<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

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
			$this->grocery_crud->set_relation('customerNumber','customers','{contactLastName} {contactFirstName}');
			$this->grocery_crud->display_as('customerNumber','Customer');
			$this->grocery_crud->set_table('orders');
			$this->grocery_crud->set_subject('Order');
			$this->grocery_crud->unset_add();
			$this->grocery_crud->unset_delete();

			$output = $this->grocery_crud->render();
			$this->load->view('templates/template_crud', $output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}