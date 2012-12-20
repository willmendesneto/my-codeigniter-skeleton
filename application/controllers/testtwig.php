<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testtwig extends CI_Controller {

	/**
	 * Construtor da classe
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Envia para a pagina index do Controller
	 * @return void
	 */
	public function index()
	{
		try{
			$this->load->library('Twig');
			$data['title'] = "Testing Twig!!";
			$this->twig->display('twig/index.html.twig', $data);
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}