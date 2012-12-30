<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

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
			$this->grocery_crud->set_table('products');
			$this->grocery_crud->set_subject('Product');
			$this->grocery_crud->unset_columns('productDescription');
			$this->grocery_crud->callback_column('buyPrice',array($this,'valueToEuro'));

			$output = $this->grocery_crud->render();
			$this->load->view('templates/template_crud', $output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}

}