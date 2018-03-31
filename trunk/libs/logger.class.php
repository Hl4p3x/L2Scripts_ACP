<?php
if (!defined('ctx')) die();
class Logger {
    
    public $log_file = "";
    
    public function __construct() {
        $this->log_file = fopen(ROOT_PATH . "private/logs/" . date("d-m-Y") ."-log.txt", "a");
    }
    
    public function Write($str) {
        if (stristr($str, "\n") !== FALSE) {
            foreach (explode("\n", $str) as $line)
                    fwrite($this->log_file, date("d-m-Y_H:i:s\t") . $line . "\r\n");
        } else {
            fwrite($this->log_file, date("d-m-Y_H:i:s\t") . $str . "\r\n");
        }
    }
    
    public function __destruct() {
        fclose($this->log_file);
    }
}