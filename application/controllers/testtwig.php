<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//use Twig\Twig\Twig\Twig as Twig;

class Testtwig extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

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