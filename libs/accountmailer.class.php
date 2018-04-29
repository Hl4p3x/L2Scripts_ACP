<?php
if (!defined('ctx')) die();
include ROOT_PATH.'/libs/phpmailer/PHPMailerAutoload.php';

class AccountMailer extends PHPMailer {
    
    function __construct() {
        parent::__construct();
        
        if (PHPMAILER_SMTP_SERVER == '')
        	return;
        
        $this->CharSet = "UTF-8";
        $this->isSMTP();
        $this->Host = PHPMAILER_SMTP_SERVER;
        $this->Port = PHPMAILER_SMTP_PORT;
        $this->SMTPSecure = PHPMAILER_SMTP_SECURE;
        $this->SMTPAuth = true;
        $this->Username = PHPMAILER_SMTP_USER;
        $this->Password = PHPMAILER_SMTP_PASS;
        
        $this->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	);
        
        $this->setFrom(PHPMAILER_FROM_MAIL, PHPMAILER_FROM_NAME);
        
        $this->isHTML(true);
    }
    
    function SendMail($sendto, $subject, $body) {
        if (PHPMAILER_SMTP_SERVER == '')
                return;
        	
        $this->addAddress($sendto);
        $this->Subject = $subject;
        $this->Body = $body;
        
        return $this->send();
    }
}