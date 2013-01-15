<?php

/**
 * Implementação de um gerador de código para o Framework Codeigniter baseada no Laravel Generator
 *
 */
class CI_Code_Generate
{

    /*
     * Set these paths to the location of your assets.
     */
    public static $assets_dir = 'assets/';
    public static $css_dir = 'css/';
    public static $sass_dir  = 'css/sass/';
    public static $less_dir  = 'css/less/';

    public static $js_dir  = 'js/';
    public static $coffee_dir  = 'js/coffee/';


    /*
     * The content for the generate file
     */
    public static $content;


    /**
     * As a convenience, fetch popular assets for user
     * php artisan generate:assets jquery.js <---
     */
    public static $external_assets = array(
        // JavaScripts
        'jquery.js' => 'http://code.jquery.com/jquery.js',
        'backbone.js' => 'http://backbonejs.org/backbone.js',
        'underscore.js' => 'http://underscorejs.org/underscore.js',
        'handlebars.js' => 'http://cloud.github.com/downloads/wycats/handlebars.js/handlebars-1.0.rc.1.js',

        // CSS
        'normalize.css' => 'https://raw.github.com/necolas/normalize.css/master/normalize.css'
    );

    public function run(array $arguments){

        echo <<<EOT
\n=============  CI Code Generator - Wilson Mendes Neto  =============\n

Please waiting...We're working!

\n=====================END====================\n
EOT;
        if ( ! isset($arguments[0]))
            throw new Exception("Please choice a option.\n" . $this->help());

        list($task, $method) = $this->parse($arguments[0]);
        // Once the bundle has been resolved, we'll make sure we could actually
        // find that task, and then verify that the method exists on the task
        // so we can successfully call it without a problem.
        if (is_null($task))
            throw new Exception("Sorry, I can't find that task.");

        if (is_callable(array($this, $method)))
            $this->$method(array_slice($arguments, 1));
        else
            throw new Exception("Sorry, I can't find that method!");
    }


    /**
     * Parse the task name to extract the bundle, task, and method.
     *
     * @param  string  $task
     * @return array
     */
    protected static function parse($task)
    {

        // Extract the task method from the task string. Methods are called
        // on tasks by separating the task and method with a single colon.
        // If no task is specified, "run" is used as the default.
        if (strpos($task, ':') !== FALSE)
            list($task, $method) = explode(':', $task);
        else
            $method = 'help';

        return array($task, $method);
    }


    /**
     * Time Savers
     *
     */
    public function c($args)   { return $this->controller($args); }
    public function m($args)   { return $this->model($args); }
    public function mig($args) { return $this->migration($args); }
    public function v($args)   { return $this->view($args); }
    public function a($args)   { return $this->assets($args); }
    public function t($args)   { return $this->test($args); }
    public function r($args)   { return $this->resource($args); }

    /**
     * Simply echos out some help info.
     *
     */
    public function help()
    {
        echo <<<EOT
\n=============GENERATOR COMMANDS=============\n
generate:controller [name] [methods]
generate:model [name]
generate:view [name]
generate:test [name] [methods]
generate:assets [asset]
\n=====================END====================\n
EOT;
    }


    /**
     * Generate a controller file with optional actions.
     *
     * USAGE:
     *
     * php artisan generate:controller Admin
     * php artisan generate:controller Admin index edit
     * php artisan generate:controller Admin index index:post restful
     *
     * @param  $args array
     * @return string
     */
    public function controller($args)
    {
        if ( empty($args) ) {
            echo "Error: Please supply a class name, and your desired methods.\n";
            return;
        }

        // Name of the class and file
        $class_name = ucwords(array_shift($args));

        // Where will this file be stored?
        $file_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. 'controllers'. DIRECTORY_SEPARATOR . "$class_name.php";

        // Add methods/actions to class.
        $this->add_after('{', $content);

        // Prettify
        $this->prettify();

        // Create the file
        $this->write_to_file($file_path);
    }



    /**
     * Crazy sloppy prettify. TODO - Cleanup
     *
     * @param  $content string
     * @return string
     */
    protected function prettify()
    {
        $content = self::$content;

        $content = str_replace('<?php ', "<?php\n\n", $content);
        $content = str_replace('{}', "\n{\n\n}", $content);
        $content = str_replace('public', "\n\n\tpublic", $content);
        $content = str_replace("() \n{\n\n}", "()\n\t{\n\n\t}", $content);
        $content = str_replace('}}', "}\n\n}", $content);

        // Migration-Specific
        $content = preg_replace('/ ?Schema::/', "\n\t\tSchema::", $content);
        $content = preg_replace('/\$table(?!\))/', "\n\t\t\t\$table", $content);
        $content = str_replace('});}', "\n\t\t});\n\t}", $content);
        $content = str_replace(');}', ");\n\t}", $content);
        $content = str_replace("() {", "()\n\t{", $content);

        self::$content = $content;
    }


    public function add_after($where, $to_add, $content)
    {
        // return preg_replace('/' . $where . '/', $where . $to_add, $content, 1);
        return str_replace($where, $where . $to_add, $content);
    }


    /**
     * Write the contents to the specified file
     *
     * @param  $file_path string
     * @param $content string
     * @param $type string [model|controller|migration]
     * @return void
     */
    protected function write_to_file($file_path,  $success = '')
    {
        $success = $success ?: "Create: $file_path.\n";

        if ( file_exists($file_path) ) {
            // we don't want to overwrite it
            echo "Warning: File already exists at $file_path\n";
            return;
        }

        // As a precaution, let's see if we need to make the folder.
        mkdir(dirname($file_path));

        if ( file_put_contents($file_path, self::$content) !== false ) {
            echo $success;
        } else {
            echo "Whoops - something...erghh...went wrong!\n";
        }
    }

    /**
     * Generate a model file + boilerplate. (To be expanded.)
     *
     * USAGE
     *
     * php artisan generate:model User
     *
     * @param  $args array
     * @return string
     */
    public function model($args)
    {
        // Name of the class and file
        $class_name = is_array($args) ? ucwords($args[0]) : ucwords($args);

        $file_path = $this->path('models') . strtolower("$class_name.php");

        // Begin building up the file's content
        Template::new_class($class_name, 'Eloquent' );
        $this->prettify();

        // Create the file
        $this->write_to_file($file_path);
    }

    /**
     * Create any number of views
     *
     * USAGE:
     *
     * php artisan generate:view home show
     * php artisan generate:view home.index home.show
     *
     * @param $args array
     * @return void
     */
    public function view($paths)
    {
        if ( empty($paths) ) {
            echo "Warning: no views were specified. Add some!\n";
            return;
        }

        foreach( $paths as $path ) {
            $file_path = $this->path('views') . str_replace('.', '/', $path) . '.blade.php';
            self::$content = "This is the $file_path view";
            $this->write_to_file($file_path);
        }
    }


    /**
     * Create assets in the public directory
     *
     * USAGE:
     * php artisan generate:assets style1.css some_module.js
     *
     * @param  $assets array
     * @return void
     */
    public function asset($assets) { return $this->assets($assets); }
    public function assets($assets)
    {
        if( empty($assets) ) {
            echo "Please specify the assets that you would like to create.";
            return;
        }

        foreach( $assets as $asset ) {
            // What type of file? CSS, JS?
            $ext = File::extension($asset);

            if( !$ext ) {
                // Hmm - not sure what to do.
                echo "Warning: Could not determine file type. Please specify an extension.";
                continue;
            }

            // Set the path, dependent upon the file type.
            switch ($ext) {
                case 'js':
                    $path = self::$js_dir . $asset;
                    break;

                case 'coffee':
                    $path = self::$coffee_dir . $asset;
                    break;

                case 'scss':
                case 'sass':
                    $path = self::$sass_dir . $asset;
                    break;

                case 'less':
                    $path = self::$less_dir . $asset;
                    break;

                case 'css':
                default:
                    $path = self::$css_dir . $asset;
                    break;
            }

            if ( $this->is_external_asset($asset) ) {
                $this->fetch($asset);
            } else { self::$content = ''; }

            $this->write_to_file(self::$assets_dir . $path, '');
        }
    }


    /**
     * Create PHPUnit test classes with optional methods
     *
     * USAGE:
     *
     * php artisan generate:test membership
     * php artisan generate:test membership can_disable_user can_reset_user_password
     *
     * @param $args array
     * @return void
     */
    public function test($args)
    {
        if ( empty($args) ) {
            echo "Please specify a name for your test class.\n";
            return;
        }

        $class_name = ucwords(array_shift($args));

        $file_path = $this->path('tests');
        if ( isset($this->should_include_tests) ) {
            $file_path .= 'controllers/';
        }
        $file_path .= strtolower("{$class_name}.test.php");

        // Begin building up the file's content
        Template::new_class($class_name . '_Test', 'PHPUnit_Framework_TestCase');

        // add the functions
        $tests = '';
        foreach($args as $test) {
            // Don't worry about tests for non-get methods for now.
            if ( strpos($test, ':') !== false ) continue;
            if ( $test === 'restful' ) continue;

            // make lower case
            $func = Template::func("test_{$test}");

            // Only if we're generating a resource.
            if ( isset($this->should_include_tests) ) {
                $func = Template::test($class_name, $test);
            }

            $tests .= $func;
        }

        // add funcs to class
        Content::add_after('{', $tests);

        // Create the file
        $this->write_to_file($file_path, $this->prettify());
    }


    /**
     * Determines whether the asset that the user wants is
     * contained with the external assets array
     *
     * @param $assets string
     * @return boolean
     */
    protected function is_external_asset($asset)
    {
        return array_key_exists(strtolower($asset), static::$external_assets);
    }


    /**
     * Fetch external asset
     *
     * @param $url string
     * @return string
     */
    protected function fetch($url)
    {
       self::$content = file_get_contents(static::$external_assets[$url]);
       return self::$content;
    }


    /**
     * Prepares the $name of the class
     * Admin/panel => Admin_Panel
     *
     * @param $class_name string
     */
    protected function prettify_class_name($class_name)
    {
        return preg_replace_callback('/\/([a-zA-Z])/', function($m) {
            return "_" . strtoupper($m[1]);
        }, $class_name);
    }

}