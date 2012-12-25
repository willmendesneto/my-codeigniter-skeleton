<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {

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

	/*
	* CUSTOM DEPENDENT DROPDOWN
	*/
	public function index()
	{
			//GROCERY CRUD SETUP
			$this->grocery_crud->set_table('customers');
			$this->grocery_crud->columns('customerName','contactLastName','phone','countryID','stateID','cityID');
			$this->grocery_crud->display_as('salesRepEmployeeNumber','From Employeer')
				 ->display_as('customerName','Name')
				 ->display_as('cityID','City/Town')
				 ->display_as('stateID','State/Province')
				 ->display_as('countryID','Country')
				 ->display_as('contactLastName','Last Name');
			$this->grocery_crud->set_subject('Customer');
			$this->grocery_crud->set_relation('salesRepEmployeeNumber','employees','{lastName} {firstName}');
			$this->grocery_crud->set_relation('countryID','country','country_title');
			$this->grocery_crud->set_relation('stateID','state','state_title');
			$this->grocery_crud->set_relation('cityID','city','city_title');
			$this->grocery_crud->fields('customerName','contactLastName','phone','countryID','stateID','cityID');
			$this->grocery_crud->required_fields('countryID','stateID','cityID');

			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
			$this->grocery_crud->callback_add_field('stateID', array($this, 'empty_state_dropdown_select'));
			$this->grocery_crud->callback_edit_field('stateID', array($this, 'empty_state_dropdown_select'));
			$this->grocery_crud->callback_add_field('cityID', array($this, 'empty_city_dropdown_select'));
			$this->grocery_crud->callback_edit_field('cityID', array($this, 'empty_city_dropdown_select'));

			$output = $this->grocery_crud->render();

			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
			    //GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
			    'dd_state' =>  $this->grocery_crud->getState(),
			    //SETUP YOUR DROPDOWNS
			    //Parent field item always listed first in array, in this case countryID
			    //Child field items need to follow in order, e.g stateID then cityID
			    'dd_dropdowns' => array('countryID','stateID','cityID'),
			    //SETUP URL POST FOR EACH CHILD
			    //List in order as per above
			    'dd_url' => array('', site_url().'/grocerycrud/get_states/', site_url().'/grocerycrud/get_cities/'),
			    //LOADER THAT GETS DISPLAYED NEXT TO THE PARENT DROPDOWN WHILE THE CHILD LOADS
			    'dd_ajax_loader' => base_url().'ajax-loader.gif'
			);
			$output->dropdown_setup = $dd_data;

			$this->_example_output($output);
	}

	//CALLBACK FUNCTIONS
	public function empty_state_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="stateID" class="chosen-select" data-placeholder="Select State/Province" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);

		//LOAD GCRUD AND GET THE STATE
		$state = $this->grocery_crud->getState();

		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {
			//GET THE STORED STATE ID
			$this->db->select('countryID, stateID')
					 ->from('customers')
					 ->where('customerNumber', $listingID);
			$db = $this->db->get();
			$row = $db->row(0);
			$countryID = $row->countryID;
			$stateID = $row->stateID;

			//GET THE STATES PER COUNTRY ID
			$this->db->select('*')
					 ->from('state')
					 ->where('countryID', $countryID);
			$db = $this->db->get();

			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->state_id == $stateID) {
					$empty_select .= '<option value="'.$row->state_id.'" selected="selected">'.$row->state_title.'</option>';
				} else {
					$empty_select .= '<option value="'.$row->state_id.'">'.$row->state_title.'</option>';
				}
			endforeach;

			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		}
	}
	public function empty_city_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="cityID" class="chosen-select" data-placeholder="Select City/Town" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);

		//LOAD GCRUD AND GET THE STATE
		$state = $this->grocery_crud->getState();

		//CHECK FOR A URI VALUE AND MAKE SURE ITS ON THE EDIT STATE
		if(isset($listingID) && $state == "edit") {
			//GET THE STORED STATE ID
			$this->db->select('stateID, cityID')
					 ->from('customers')
					 ->where('customerNumber', $listingID);
			$db = $this->db->get();
			$row = $db->row(0);
			$stateID = $row->stateID;
			$cityID = $row->cityID;

			//GET THE CITIES PER STATE ID
			$this->db->select('*')
					 ->from('city')
					 ->where('stateID', $stateID);
			$db = $this->db->get();

			//APPEND THE OPTION FIELDS WITH VALUES FROM THE STATES PER THE COUNTRY ID
			foreach($db->result() as $row):
				if($row->city_id == $cityID) {
					$empty_select .= '<option value="'.$row->city_id.'" selected="selected">'.$row->city_title.'</option>';
				} else {
					$empty_select .= '<option value="'.$row->city_id.'">'.$row->city_title.'</option>';
				}
			endforeach;

			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		} else {
			//RETURN SELECTION COMBO
			return $empty_select.$empty_select_closed;
		}
	}

	//GET JSON OF STATES
	public function get_states()
	{
		$countryID = $this->uri->segment(3);

		$this->db->select("*")
				 ->from('state')
				 ->where('countryID', $countryID);
		$db = $this->db->get();

		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->state_id, "property" => $row->state_title);
		endforeach;

		echo json_encode($array);
		exit;
	}

	//GET JSON OF CITIES
	public function get_cities()
	{
		$stateID = $this->uri->segment(3);

		$this->db->select("*")
				 ->from('city')
				 ->where('stateID', $stateID);
		$db = $this->db->get();

		$array = array();
		foreach($db->result() as $row):
			$array[] = array("value" => $row->city_id, "property" => $row->city_title);
		endforeach;

		echo json_encode($array);
		exit;
	}

	public function _example_output($output = null)
	{
		$this->load->view('templates/template', $output);
	}

}