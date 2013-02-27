<?php

class Posts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->load->model('post');
        $posts = $this->post->getAll();

        $this->load->view('posts/index', array('posts' => $posts));
    }
}