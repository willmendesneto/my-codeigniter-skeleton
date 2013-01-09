<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Model {

	public $id;
	public $nome;
	public $email;
	public $senha;

	public function __construct()
	{
		parent::__construct();

	}

}

/* End of file usuario.php */
/* Location: ./application/models/usuario.php */