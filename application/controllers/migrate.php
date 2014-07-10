<?php   defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('file', 'directory'));
        $this->load->library('migration');
    }

    public function all() {

        if(!$this->migration->latest()) {
            show_error($this->migration->error_string());
        }

        echo 'Migrations up!';
    }

    public function version($version = '') {
        if (!is_numeric($version)) {
            show_error('This method accept only numbers');
        }

        $migration = $this->migration->version($version);
        if(!$migration) {
            show_error($this->migration->error_string());
        }

        echo 'Migrations "'.$version.'" runs ok!';
    }

}
/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */
