<?php
if (!defined('ctx')) die();
class Captcha {
    public static function ValidateCaptcha() {
        $url = "https://www.google.com/recaptcha/api/siteverify";

        $post = "secret=" . CAPTCHA_PRIVATE_KEY;
        $post .= "&response=" . $_POST['g-recaptcha-response'];
        $post .= "&remoteip=" . $_SERVER['REMOTE_ADDR'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);

        curl_close($ch);

        $captchaResponse = json_decode($result);
        if ($captchaResponse->success == 'true')
            return true;
        return false;
    }
}