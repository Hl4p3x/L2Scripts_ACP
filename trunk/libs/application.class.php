<?php
if (!defined('ctx')) die();
class Application {

    function __construct() {
        ini_set('display_errors',1);
        ini_set('display_startup_errors',1);
        error_reporting(-1);

        $url = isset($_GET['url']) ? filter_var($_GET['url'], FILTER_SANITIZE_URL) : null;
        $url = rtrim($url, '/');
 //       $url = explode('/', $url);
        $url = explode('/', trim($url, '/'));

	$controller_name = !empty($url[0]) ? $url[0] : 'account';
            if (strtolower($controller_name) == 'home')
                    $controller_name = 'account';
        $function_name = !empty($url[1]) ? $url[1] : 'index';
        $data = array();

        $file = 'controllers/' . strtolower($controller_name) . '.controller.php';
        if (!file_exists($file)) {
            $file = 'controllers/account.controller.php';
            $function_name = $controller_name;
            $controller_name = 'account';

            // splice the array and pass the rest of array as data
            if (isset($url[1]) && $url[1] != "")
            {
                array_splice($url, 0, 1);
                $data = $url;
            }
        } else {
            // splice the array and pass the rest of array as data
            if (isset($url[2]) && $url[2] != "")
            {
                array_splice($url, 0, 2);
                $data = $url;

            }
        }
	    require $file;
        $controller_class_name = $controller_name.'_Controller';
        $controller = new $controller_class_name;
        $controller->loadModel($controller_name);

        // calling methods
        if (in_array($function_name,get_class_methods($controller))) {
                $controller->{$function_name}($data);
        } else {
                header("HTTP/1.0 404 Not Found");
        }
    }
}

?>
