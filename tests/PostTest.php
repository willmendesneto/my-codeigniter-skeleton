<?php

class PostTest extends PHPUnit_Framework_TestCase {

    private $CI;

    public function setUp(){
        parent::setUp();
        parent::tearDown();
        $this->CI = &get_instance();
    }

    public function testGetsAllPosts() {
        $this->CI->load->model('post');
        $posts = $this->CI->post->getAll();
        $this->assertEquals(0, count($posts));
    }

}