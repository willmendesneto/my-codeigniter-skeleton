<?php if ( ! defined('BASEPATH')) exit('Acesso direto n&atilde;o permitido');

class MY_Model extends CI_Model{
	//	Variavel com o valor da tabela
	private $table;

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Obtem o numero registros da tabela no BD
	 *
	 * @return Int
	 */
	public function countRows(){
		return $this->db->count_all_results($this->table);
	}

	/**
	 * Atualiza um registro
	 *
	 * @param string $table
	 * @param array $where
	 * @param array $session
	 * @return boolean
	 */
	public function update($table = NULL, $where = NULL, $session){
		//	Verificando se existe informacoes sobre a TABELA
		$table = (is_null($table)) ? $this->table : $table;
		//	CONDICIONAIS
        if ( !is_null($where)){
          	foreach($where as $key =>$value){
          		if(!is_null($value) ){
					$this->db->where($key, $value);
				}
          	}
        }else{
        	//	Forcando ao erro para que nao HAJA IMPRECISAO NO BD
        	$this->db->where('id', '|');
        }
		$this->db->update($table, $session);
		// Forca para que seja retornado um valor logico
		return (bool) $this->db->affected_rows();
	}

	/**
	 * Deleta um registro
	 *
	 * @param type $table
	 * @param type $where
	 * @return type
	 */
	public function delete($table = NULL, $where = NULL){
		//	Verificando se existe informacoes sobre a TABELA
		$table = (is_null($table)) ? $this->table : $table;
		//	CONDICIONAIS
        if ( !is_null($where)){
          	foreach($where as $key =>$value){
          		if(!is_null($value) ){
					$this->db->where($key, $value);
				}
          	}
        }else{
        	//	Forcando ao erro para que nao HAJA IMPRECISAO NO BD
        	$this->db->where('id', '|');
        }
		$this->db->delete($table);
		// Forca para que seja retornado um valor logico
		return (bool) $this->db->affected_rows();
	}

	/**
	 * Insere um registro
	 *
	 * @param type $table
	 * @param type $data
	 * @return type
	 */
	public function insert($table = NULL, $data){
		//	Verificando se existe informacoes sobre a TABELA
		$table = (is_null($table)) ? $this->table : $table;
        $sql = $this->db->insert($table, $data);
        return $this->db->insert_id();
	}

	/**
	 * Retorna um objeto com os valores da consulta
	 *
	 * Obtem uma faixa de registros ( limitados pela paginacao )
	 *
	 * @param type $de
	 * @param type $quantidade
	 * @param type $campos
	 * @param type $where
	 * @param type $ordenation
	 * @param type $table
	 * @return type
	 */
	public function getAllLimit( $de = NULL, $quantidade = NULL, $campos = NULL, $where = NULL, $ordenation = NULL, $table = NULL){
		//	Verificando se existe informacoes sobre os CAMPOS
		$campos = (is_null($campos)) ? '*' : $campos;
		//	Verificando se existe informacoes sobre a TABELA
		$table = (is_null($table)) ? $this->table : $table;
		# Quais campos devem ser retornado
		$this->db->select($campos);
		//	CONDICIONAIS
        if ( !is_null($where)){
          	foreach($where as $key =>$value){
	          	if ( !is_null($value)){
	    	    	$this->db->where($key, $value);
	          	}
            }
        }
       	//	ORDENACAO
       	if(!is_null($ordenation)){
        	if(is_array($ordenation)){
	          	foreach($ordenation as $key =>$value){
	          		if ( !is_null($value)){
		        		$this->db->order_by($key);
	          		}
	          	}
        	}else{
        		$this->db->order_by($ordenation);
        	}
        }
        //	LIMITE
        if( (is_numeric($de)) and (is_numeric($quantidade)) ){
        	$this->db->limit($de, $quantidade);
		}
		# Executa a consulta
		$sql = $this->db->get($table);
       	return ($sql->num_rows() > 0) ? $sql->result() : FALSE;
	}

}