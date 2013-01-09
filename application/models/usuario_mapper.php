<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_Mapper extends CI_Model {

	public function save(Usuario $usuario){
		if(NULL == ($id = $usuario->id)){
			$this->db->insert('usuarios', $usuario);
			return $this->db->insert_id();
		}

		$this->db->update('usuarios', $usuario, array('id' => $id));
	}
}

/* End of file usuario_mapper.php */
/* Location: ./application/models/usuario_mapper.php */