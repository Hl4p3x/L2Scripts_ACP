<?php
if (!defined('ctx')) die();

class Error_Controller extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index($data = array()) {
        $this->view->render("error/404");
    }
}