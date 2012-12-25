<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Films extends CI_Controller {

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

	/**
	 * Renderizando o CRUD para a tabela "film" no Banco de dados
	 *
	 * @return void
	 */
	public function index()
	{
		try{
			$this->grocery_crud->set_table('film');
			$this->grocery_crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
			$this->grocery_crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
			$this->grocery_crud->unset_columns('special_features','description','actors');

			$this->grocery_crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

			$output = $this->grocery_crud->render();
			$this->load->view('templates/template_crud', $output);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}