<?php

class Test_Usuario extends CIUnit_TestCase {

	private $usuarios = array();
	private $usuario;
	private $usuario_mapper;

	public function setUp(){
		parent::setUp();
		parent::tearDown();

		$this->CI->load->model(array('usuario', 'usuario_mapper'));

		$this->usuario = &$this->CI->usuario;
		$this->usuario_mapper = &$this->CI->usuario_mapper;
		for($i = 1; $i < 6; $i++){
			$this->usuarios[$i] = clone $this->usuario;
			$this->usuarios[$i]->nome = 'Tiago';
			$this->usuarios[$i]->email = 'tiago'.$i.'@gmail.com';
			$this->usuarios[$i]->senha = '123456';
		}
	}

	public function test_cria_usuario(){
		$this->assertEquals(1, $this->usuario_mapper->save($this->usuarios[1]));
		$this->assertEquals(2, $this->usuario_mapper->save($this->usuarios[2]));
		$this->assertEquals(3, $this->usuario_mapper->save($this->usuarios[3]));
	}

	/**
	 *	depends test_cria_usuario
	 **/
	public function test_salva_usuario(){
		$this->test_cria_usuario();
		$email = 'teste@teste.com';
		$id = 1;
		$this->usuarios[1]->id = $id;
		$this->usuarios[1]->email = $email;

		$this->assertTrue($this->usuario_mapper->save($this->usuarios[1]));
		$usuario = $this->usuario_mapper->find($id);

		$this->assertEquals($email, $usuario->email);

	}


	public function tearDown(){
		$this->CI->db->truncate('usuarios');
	}

}

/* End of file test_usuario.php */
/* Location: .//D/projects/my-codeigniter-skeleton/tests/models/test_usuario.php */
