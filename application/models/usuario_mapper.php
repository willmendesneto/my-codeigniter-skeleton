<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_Mapper extends CI_Model {

	public function save(Usuario $usuario){
		if(NULL == ($id = $usuario->id)){
			$this->db->insert('usuarios', $usuario);
			return $this->db->insert_id();
		}

		return $this->db->update('usuarios', $usuario, array('id' => $id));
	}

	public function find($id){
		return $this->db->get_where('usuarios', array('id' => $id))->row();
	}
}

/* End of file usuario_mapper.php */
/* Location: ./application/models/usuario_mapper.php */