<?php if ( ! defined('BASEPATH')) exit('Acesso direto n&atilde;o permitido');

class MY_Exceptions extends CI_Exceptions {

    /**
     * Codeigniter Input Class
     *
     * @package default
     */
    protected $_IN;

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct(){
        $this->_IN =& load_class('Input', 'core');
    }

    /**
     * General Error Page
     *
     * Takes an error message as input (either as a string or an array)
     * and displays it using the specified template.
     *
     * @param   string      $heading    Page heading
     * @param   string|string[] $message    Error message
     * @param   string      $template   Template name
     * @param   int     $status_code    (default: 500)
     *
     * @return  string  Error page output
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        if($this->_IN->is_cli_request()){
            $message = str_replace(array('<p>', '</p>'), '', $message);
            echo <<<EOT
\n{$heading}\n
Message: {$message}\n
EOT;
            return;

        }

        parent::show_error($heading, $message, $template, $status_code);
    }

    /**
     * Native PHP error handler, verifying CLI ENVIRONMENT
     *
     * @param   int $severity   Error level
     * @param   string  $message    Error message
     * @param   string  $filepath   File path
     * @param   int $line       Line number
     * @return  string  Error page output
     */
    public function show_php_error($severity, $message, $filepath, $line)
    {
        $severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
        $filepath = str_replace('\\', '/', $filepath);

        //  Verification CLI Requests (for unit test)
        if($this->_IN->is_cli_request())
        {
            echo <<<EOT
\nA PHP Error was encountered\n
Severity: {$severity}\n
Message: {$message}\n
Filename: {$filepath}\n
Line Number: {$line}\n
EOT;
            return;
        }

        parent::show_php_error($severity, $message, $filepath, $line);
    }

}

/* End of file MY_Exceptions.php */
/* Location: ./application/core/MY_Exceptions.php */