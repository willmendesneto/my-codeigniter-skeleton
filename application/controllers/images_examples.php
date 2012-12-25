<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images_examples extends CI_Controller {

	/**
	 * Construtor da classe
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('image_CRUD');
	}

	public function _example_output($output = null)
	{
		$this->load->view('templates/template', $output);
	}

	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function example1()
	{

		$this->image_crud->set_primary_key_field('id');
		$this->image_crud->set_url_field('url');
		$this->image_crud->set_table('example_1')
			->set_image_path('assets/uploads');

		$output = $this->image_crud->render();

		$this->_example_output($output);
	}

	public function example2()
	{

		$this->image_crud->set_primary_key_field('id');
		$this->image_crud->set_url_field('url');
		$this->image_crud->set_table('example_2')
		->set_ordering_field('priority')
		->set_image_path('assets/uploads');

		$output = $this->image_crud->render();

		$this->_example_output($output);
	}

	public function example3()
	{

		$this->image_crud->set_primary_key_field('id');
		$this->image_crud->set_url_field('url');
		$this->image_crud->set_table('example_3')
		->set_relation_field('category_id')
		->set_ordering_field('priority')
		->set_image_path('assets/uploads');

		$output = $this->image_crud->render();

		$this->_example_output($output);
	}

	public function example4()
	{

		$this->image_crud->set_primary_key_field('id');
		$this->image_crud->set_url_field('url');
		$this->image_crud->set_title_field('title');
		$this->image_crud->set_table('example_4')
		->set_ordering_field('priority')
		->set_image_path('assets/uploads');

		$output = $this->image_crud->render();

		$this->_example_output($output);
	}

	public function simple_photo_gallery()
	{

		$this->image_crud->unset_upload();
		$this->image_crud->unset_delete();

		$this->image_crud->set_primary_key_field('id');
		$this->image_crud->set_url_field('url');
		$this->image_crud->set_table('example_4')
		->set_image_path('assets/uploads');

		$output = $this->image_crud->render();

		$this->_example_output($output);
	}
}