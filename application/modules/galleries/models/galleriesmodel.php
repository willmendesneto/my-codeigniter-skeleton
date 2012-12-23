<?php

class GalleriesModel extends CI_Model
{

	/**
	 * Nome da tabela
	 */
	private $table = 'tbGalerias';

	// Obtêm os itens de menu da area administrativa
    public function getMenu(){
    	$this->db->select('link, menu, icone')->from('tbMenu')->where('idMenuCateg', 2);
        $sql = $this->db->get();
        if($sql->num_rows > 0){
    	    return $sql->result();
        }else{
        	return FALSE;
        }
    }

	// Obtêm os itens de menu da area administrativa
    public function getPhotos($id){
    	$this->db->select('idFoto, foto, legenda, dataCadastro, ordenacao')->from('tbFotos')->where('idGaleria', $id);
    	$this->db->order_by('ordenacao');
    	$sql = $this->db->get();
        if($sql->num_rows > 0){
    	    return $sql->result();
        }else{
        	return FALSE;
        }
    }

	function count_rows()
	{
		return $this->db->count_all_results($this->table);
	}

	// Obtêm uma faixa de registros ( limitados pela paginação )

	function get_all($de = 0, $quantidade = 10)
	{
		# Quais campos devem ser retornado
		$this->db->select('idGaleria, titulo, chamada');
		# Limite

		$this->db->limit($de, $quantidade);

		# Executa a consulta

		return $this->db->get($this->table);
	}
	// Obtêm todos os campos de um registro em particular

	function get_by_id($idGaleria)
	{
		# Where id do contato
		$this->db->where('md5(idGaleria)', md5($idGaleria));

		# Executa a consulta
		$resultado = $this->db->get($this->table);

		if( $resultado->num_rows==0 )
		{
			return FALSE;
		}
		else
		{
			return $resultado;
		}
	}

	// Atualiza um contato
	function update($idGaleria, $session)
	{
		$this->db->where('md5(idGaleria)', md5($idGaleria))->update($this->table, $session);

		# Força para que seja retornado um valor lógico
		return (bool) $this->db->affected_rows();
	}

	// Atualiza um contato
	function updatePhoto($idFoto, $session)
	{
		$this->db->where('md5(idFoto)', md5($idFoto))->update('tbFotos', $session);

		# Força para que seja retornado um valor lógico
		return (bool) $this->db->affected_rows();
	}
	// Deleta um contato

	function delete($idGaleria)
	{
		// Deletando todas as fotos vinculadas a galeria na tabela TBFOTOS
		$this->db->where('md5(idGaleria)', md5($idGaleria));
		$return = $this->db->delete('tbFotos');
		// Agora deletamos a tabela TBGALERIAS
		if((bool)$return == TRUE ){
		// Deletando a galeria
			$this->db->where('md5(idGaleria)', md5($idGaleria));
			$this->db->delete($this->table);
		}else{
			return FALSE;
		}
		# Força para que seja retornado um valor lógico

		return (bool) $this->db->affected_rows();
	}

	// Deleta uma foto
	function deletePhoto($idPhoto)
	{
		// Deletando todas as fotos vinculadas a galeria na tabela TBFOTOS
		$this->db->where('md5(idFoto)', md5($idPhoto));
		$return = $this->db->delete('tbFotos');
		# Força para que seja retornado um valor lógico

		return (bool) $this->db->affected_rows();
	}
	// Insere um contato
	public function Insert($table, $dados)
	{
            $sql = $this->db->insert($table, $dados);
            return $this->db->insert_id();
	}
}