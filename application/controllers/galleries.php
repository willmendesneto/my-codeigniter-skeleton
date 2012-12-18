<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Galleries extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		try{
    $crud = new grocery_CRUD();

    $crud->set_theme('twitter-bootstrap');
    $crud->set_table('offices');
    $crud->set_subject('Office');
    $crud->required_fields('city');
    $crud->columns('city','country','phone');
    $output = $crud->render();

    $this->_example_output($output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function just_a_test($primary_key , $row)
	{
	    return site_url('demo/action/action_photos').'?country='.$row->country;
	}

	public function _example_output($output = null)
	{
		$this->load->view('templates/template', $output);
	}

}