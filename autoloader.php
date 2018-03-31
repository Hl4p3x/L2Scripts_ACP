 <?php

if (!defined("ctx"))
    die();

function attempt_load($path) {
    if (file_exists($path)) {
        @include_once($path);
    }
}

function acp_app_autoload($class) {
    $class = strtolower($class);
    if (strpos($class, "_model") !== FALSE) {
        attempt_load(ROOT_PATH . "/models/" . str_replace("_model", "", $class) . ".model.php");
    } else if (strpos($class, "_controller") !== FALSE) {
        attempt_load(ROOT_PATH . "/controllers/" . str_replace("_controller", "", $class) . ".controllers.php");
    } else {
        attempt_load(ROOT_PATH . "/libs/" . $class . ".class.php");
    }
}

spl_autoload_register("acp_app_autoload");