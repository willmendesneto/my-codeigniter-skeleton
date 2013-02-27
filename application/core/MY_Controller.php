<?php if ( ! defined('BASEPATH')) exit('Acesso direto n&atilde;o permitido');

class MY_Controller extends CI_Controller{

	/**
	 * Class Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Return the vinculated select options value
	 *
	 * @param int $id
	 * @return JsonObject
	 */
	public function getOptionsValue($id) {
		$listItems = $this->AjaxModel->comboCidadePorEstado($id);

		if( empty ( $listItems ) ){
	    	$this->output->set_content_type('application/json')
	    					->set_output('[{ "id": "", "name": "Nenhum item encontrado" }]');
	    	return;
		}

		$arrayAnos = array();
		foreach ($listItems as $item) {
			$arrayAnos[] = '{"id": '.$item->cod_cidade_cid.', "name": "'.utf8_encode($item->des_nome_cid).'"}';
		}

		//	Creating array in JSON format
	    $this->output->set_content_type('application/json')
			    		->set_output('[ '.implode(",", $arrayAnos).']');
		return;
	}

	/**
	 * Setting JSON Headers
	 *
	 * @return void
	 */
	public function setAjaxHeader(){
	    $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
	    $this->output->set_header('Expires: '.gmdate('D, d M Y H:i:s', time()).' GMT');
	    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0");
	    $this->output->set_header("Pragma: no-cache");
	}

}