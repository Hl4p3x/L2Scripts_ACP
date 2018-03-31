<?php
if (!defined('ctx')) die();
class Controller {

    public function __construct() {
    	$this->view = new View();
    	$this->view->title = "Non-implemented Controller";
    }

    public function loadModel($name)
    {
        $path = 'models/'.$name.'.model.php';

        if (file_exists($path))
        {
            require $path;
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }
    }

    public function index($data = array())
    {
        echo "Controller not implemented.";
    }
}

?>