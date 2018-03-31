<?php
if (!defined('ctx')) die();
class View {

    function __construct() {

    }

    public function render($name, $header = true, $footer = true, $return = false)
    {
        if ($return)
            ob_start();

        if ($header)
            require 'views/header.php';

    	require 'views/'.$name.'.php';

        if ($footer)
            require 'views/footer.php';

        if ($return)
            return ob_get_clean();
    }

    public function redirect($location)
    {
        if (stristr($location, "http://") === FALSE && stristr($location, "https://") === FALSE) {
            if (USING_CLEAN_URLS === FALSE)
                $location = "index.php?url=".$location;
            header('location: '.URL.$location);
        } else {
            header('location: ' .$location);
        }
    }
}
?>
