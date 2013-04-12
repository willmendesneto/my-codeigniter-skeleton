<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Router extends CI_Router {

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Validate request
	 *
	 * Attempts validate the URI request and determine the controller path.
	 *
	 * @param	array	$segments	URI segments
	 * @return	array	URI segments
	 */
	protected function _validate_request($segments)
	{
		if (count($segments) === 0)
		{
			return $segments;
		}

		$temp = str_replace('-', '_', $segments[0]);

		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$temp.'.php'))
		{
			$segments[0] = $temp;
			empty($segments[1]) OR $segments[1] = str_replace('-', '_', $segments[1]);
			return $segments;
		}

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory(array_shift($segments));
			if (count($segments) > 0)
			{
				$segments[0] = str_replace('-', '_', $segments[0]);
				empty($segments[1]) OR $segments[1] = str_replace('-', '_', $segments[1]);

				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->directory.$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$this->directory = '';
						return explode('/', $this->routes['404_override'], 2);
					}

			        if (substr(php_sapi_name(), 0, 3) == 'cli' and ENVIRONMENT === 'testing')
			        {
			        	return;
			        }
					else
					{
						show_404($this->directory.$segments[0]);
					}
				}
			}
			else
			{
				// Is the method being specified in the route?
				$segments = explode('/', $this->default_controller);
				if ( ! file_exists(APPPATH.'controllers/'.$this->directory.$segments[0].'.php'))
				{
					$this->directory = '';
				}
			}

			return $segments;
		}

		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class. We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			if (sscanf($this->routes['404_override'], '%[^/]/%s', $class, $method) !== 2)
			{
				$method = 'index';
			}

			return array($class, $method);
		}

        if (substr(php_sapi_name(), 0, 3) == 'cli' and ENVIRONMENT === 'testing')
        {
        	return;
        }
		// Nothing else to do at this point but show a 404
		show_404($segments[0]);
	}

}

/* End of file MY_Router.php */
/* Location: ./application/controllers/MY_Router.php */