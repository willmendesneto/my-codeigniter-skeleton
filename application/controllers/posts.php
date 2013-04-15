<?php   defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->grocery_crud->set_theme('twitter-bootstrap');
    }

    /**
     * Run the view using Grocery CRUD
     * @return type
     */
    public function index()
    {
        try{

            $this->grocery_crud->set_table('posts')
                                ->set_subject('Posts')
                                ->columns('id', 'title', 'create_date');

            $output = $this->grocery_crud->render();
            $this->load->view('posts/example', $output);

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }

    /**
     * Run the view using blade library
     * @return type
     */
    public function blade()
    {
        $this->blade->render('home.index');
    }

}