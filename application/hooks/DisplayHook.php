<?php if ( ! defined('BASEPATH')) exit('Acesso direto n&atilde;o permitido');

class DisplayHook
{
    public function captureOutput()
    {
        $this->CI =& get_instance();
        $output = $this->CI->output->get_output();

        if (PHP_SAPI != 'cli') {
            echo $output;
        }
    }
}