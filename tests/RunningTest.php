<?php

class RunningTest extends PHPUnit_Framework_TestCase
{

    protected $_CI;

    public function setUp()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->database('testing');
    }

    public function testGetsAllPosts()
    {
        $this->assertEquals(0, '0');
    }

}