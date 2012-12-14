<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GroceryCrud extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		/* Standard Libraries */
		$this->load->database();
		$this->load->helper('url');
		/* ------------------ */

		$this->load->library('grocery_CRUD');
	}

	function offices()
	{
		$output = $this->grocery_crud->render();

		$this->_example_output($output);
	}

	function offices_management()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('offices');
			$crud->set_subject('Office');
			$crud->required_fields('city');
			$crud->columns('city','country','phone','addressLine1','postalCode');

			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function employees_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('employees');
			$crud->set_relation('officeCode','offices','city');
			$crud->display_as('officeCode','Office City');
			$crud->set_subject('Employee');

			$crud->required_fields('lastName');

			$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
	}


	function orders_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_relation('customerNumber','customers','{contactLastName} {contactFirstName}');
			$crud->display_as('customerNumber','Customer');
			$crud->set_table('orders');
			$crud->set_subject('Order');
			$crud->unset_add();
			$crud->unset_delete();

			$output = $crud->render();

			$this->_example_output($output);
	}

	function products_management()
	{
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('products');
			$crud->set_subject('Product');
			$crud->unset_columns('productDescription');
			$crud->callback_column('buyPrice',array($this,'valueToEuro'));

			$output = $crud->render();

			$this->_example_output($output);
	}

	function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}

	function film_management()
	{
		$crud = new grocery_CRUD();

		$crud->set_theme('twitter-bootstrap');
		$crud->set_table('film');
		$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
		$crud->set_relation_n_n('category', 'film_category', 'category', 'film_id', 'category_id', 'name');
		$crud->unset_columns('special_features','description','actors');

		$crud->fields('title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features');

		$output = $crud->render();

		$this->_example_output($output);
	}

	function _example_output($output = null)
	{
		$this->load->view('grocery-crud/example', $output);
	}

	function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	/*
	* CUSTOM DEPENDENT DROPDOWN
	*/
	function customers_management()
	{
			//GROCERY CRUD SETUP
			$crud = new grocery_CRUD();

			$crud->set_theme('twitter-bootstrap');
			$crud->set_table('customers');
			$crud->columns('customerName','contactLastName','phone','countryID','stateID','cityID');
			$crud->display_as('salesRepEmployeeNumber','From Employeer')
				 ->display_as('customerName','Name')
				 ->display_as('cityID','City/Town')
				 ->display_as('stateID','State/Province')
				 ->display_as('countryID','Country')
				 ->display_as('contactLastName','Last Name');
			$crud->set_subject('Customer');
			$crud->set_relation('salesRepEmployeeNumber','employees','{lastName} {firstName}');
			$crud->set_relation('countryID','country','country_title');
			$crud->set_relation('stateID','state','state_title');
			$crud->set_relation('cityID','city','city_title');
			$crud->fields('customerName','contactLastName','phone','countryID','stateID','cityID');
			$crud->required_fields('countryID','stateID','cityID');

			//IF YOU HAVE A LARGE AMOUNT OF DATA, ENABLE THE CALLBACKS BELOW - FOR EXAMPLE ONE USER HAD 36000 CITIES AND SLOWERD UP THE LOADING PROCESS. THESE CALLBACKS WILL LOAD EMPTY SELECT FIELDS THEN POPULATE THEM AFTERWARDS
			$crud->callback_add_field('stateID', array($this, 'empty_state_dropdown_select'));
			$crud->callback_edit_field('stateID', array($this, 'empty_state_dropdown_select'));
			$crud->callback_add_field('cityID', array($this, 'empty_city_dropdown_select'));
			$crud->callback_edit_field('cityID', array($this, 'empty_city_dropdown_select'));

			$output = $crud->render();

			//DEPENDENT DROPDOWN SETUP
			$dd_data = array(
			    //GET THE STATE OF THE CURRENT PAGE - E.G LIST | ADD
			    'dd_state' =>  $crud->getState(),
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
	function empty_state_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="stateID" class="chosen-select" data-placeholder="Select State/Province" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);

		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();

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
	function empty_city_dropdown_select()
	{
		//CREATE THE EMPTY SELECT STRING
		$empty_select = '<select name="cityID" class="chosen-select" data-placeholder="Select City/Town" style="width: 300px; display: none;">';
		$empty_select_closed = '</select>';
		//GET THE ID OF THE LISTING USING URI
		$listingID = $this->uri->segment(4);

		//LOAD GCRUD AND GET THE STATE
		$crud = new grocery_CRUD();
		$state = $crud->getState();

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
	function get_states()
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
	function get_cities()
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
}