<?php

class MY_Exceptions extends CI_Exceptions {

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
        set_status_header($status_code);

        $message = '<p>'.implode('</p><p>', is_array($message) ? $message : array($message)).'</p>';

        if (substr(php_sapi_name(), 0, 3) == 'cli')
        {
            $message = str_replace(array('<p>', '</p>'), '', $message);
            echo <<<EOT
\n{$heading}\n
Message: {$message}\n
EOT;
            return;
        }

        parent::show_error($heading, $message, $template = 'error_general', $status_code = 500);
    }
    /**
     * Native PHP error handler
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

        //  CLI Verification (ENVIRONMENT == 'testing')
        if (substr(php_sapi_name(), 0, 3) == 'cli')
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