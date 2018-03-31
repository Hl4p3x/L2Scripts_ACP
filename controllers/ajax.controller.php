<?php
if (!defined('ctx')) die();

class Ajax_Controller extends Controller {

    private $account_data = false;

    function __construct() {
        parent::__construct();
    }

    public function CreateGameAccount($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $server_model = new Server_Model(Session::get("serverId"));
            $game_accounts = $server_model->getAccounts(Session::get("accountId"), true);
            if (count($game_accounts) < $_CONFIG['servers'][Session::get("serverId")]['max_accounts']) {
                $account = isset($_POST['login']) ? $_POST['login'] : "";
                $password = isset($_POST['password']) ? $_POST['password'] : "";
                $password2 = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : "";

                if ($account == "") {
                    $error = _s("ENTER_LOGIN");
                }
                
                if (strlen($account) < 4 || strlen($account) > 16) {
                    $error = _s("INVALID_USERNAME");
                }

                if ($password == "" || strlen($password) < 6 || strlen($password) > 16) {
                    $error = _s("PASS_SIX_DIGIT");
                }

                if ($password != $password2) {
                    $error = _s("PASS_MISMATCH");
                }

                if ($error == "") {
                    $password = pass_encode($password, PASSWORD_HASH_ALGORITHM);
                    if (!$server_model->createAccount(Session::get('accountId'), $account, $password)) {
                        $error = $server_model->error;
                    }
                }
            } else {
                $error = _s("MAX_GAME_ACCS");
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_GAME_ACCOUNT") : $error
        );

        echo json_encode($result);
    }

    public function ChangePasswordMA($data = array()) {
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_LOGGED");
        } else {
            $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : "";
            $new_password = isset($_POST['password']) ? $_POST['password'] : "";
            $new_password2 = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : "";

            if ($new_password != $new_password2) {
                $error = _s("PASS_MISMATCH");
            }

            if (strlen($new_password) < 6 || strlen($new_password) > 16) {
                $error = _s("PASS_SIX_DIGIT");
            }

            if ($error == "") {
                $account_model = new Account_Model();
                $new_password = hash(HASH_ALGO, $new_password);
                if (!$account_model->ChangePasswordMA(Session::get('accountId'), $old_password, $new_password)) {
                    $error = $account_model->error;
                }
            }
        }
        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_CHANGE_PASS") : $error
        );

        echo json_encode($result);
    }

    public function ChangePasswordGA($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $login = isset($_POST['login']) ? $_POST['login'] : "";
            $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : "";
            $new_password = isset($_POST['password']) ? $_POST['password'] : "";
            $new_password2 = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : "";

            if ($new_password != $new_password2) {
                $error = _s("PASS_MISMATCH");
            }

            if (strlen($new_password) < 6 || strlen($new_password) > 16) {
                $error = _s("PASS_SIX_DIGIT");
            }

            if ($error == "") {
                $new_password = pass_encode($new_password, PASSWORD_HASH_ALGORITHM);
                $old_password = pass_encode($old_password, PASSWORD_HASH_ALGORITHM);
                $server_model = new Server_Model(Session::get("serverId"));
                if ($server_model->MACheck(Session::get('accountId'), $login)) {
                    if (!$server_model->ChangePasswordGA($login, $old_password, $new_password)) {
                        $error = $server_model->error;
                    }
                } else {
                    $error = _s("NOT_YOUR_ACC");
                }
            }
        }
        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_CHANGE_PASS") : $error
        );

        echo json_encode($result);
    }

    public function RecoverPasswordGA($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $login = $_POST['login'];
            $new_password = random_token(12);

            $server_model = new Server_Model(Session::get("serverId"));
            if ($server_model->MACheck(Session::get('accountId'), $login)) {
                if ($server_model->SetPasswordGA($login, pass_encode($new_password, PASSWORD_HASH_ALGORITHM))) {
                    $this->view->game_account = $login;
                    $this->view->new_pass = $new_password;

                    $account_model = new Account_Model();
                    $account_data = $account_model->getAccountData(Session::get("accountId"));

                    
                    $mailer = new AccountMailer();
                    $mailer->SendMail($account_data['email'], $_CONFIG['mail_recover_subject'], $this->view->render('emails/recover_ga', false, false, true));
                } else {
                    $error = $server_model->error;
                }
            } else {
                $error = _s("NOT_YOUR_ACC");
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("PASS_SENT_EMAIL") : $error
        );

        echo json_encode($result);
    }

    public function globalcoins($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $amount = isset($_POST['euro']) ? intval($_POST['euro']) : 0;
            $char = isset($_POST['char']) ? $_POST['char'] : '';
            if ($amount <= 0) {
                $error = _s("INCOR_COUNT");
            } else {
                $account_model = new Account_Model();
                $account_data = $account_model->getAccountData(Session::get("accountId"));

                if ($account_data['balance'] < 0 || $amount > $account_data['balance']) {
                    $error = _s("INVALID_COUNT");
                } else {
                    $server_model = new Server_Model(Session::get("serverId"));
                    if ($server_model->CheckChar($char, Session::get("accountId"))) {
                        $character = $server_model->GetCharacter($char);
                        if ($character !== false/* && $character['online'] == 0*/) {
							if ($character['online'] == 0){
								$bonus_percent = $_CONFIG['account_level_discount'][get_account_level($account_data['account_exp'])];
								$bonus = (($bonus_percent / 100) * COINS_PER_DOLLAR) + COINS_PER_DOLLAR;
								$coins = intval(floor($amount*$bonus));
								$donate_model = new Donate_Model();
								$donate_model->LogTransaction(_s("COINS_TRANSFER"), -$amount, Session::get("accountId"), $character['char_name'], Session::get("serverId"));
								$donate_model->DeductBalance(Session::get("accountId"), $amount);
								$server_model->AddItem($character['obj_Id'], $_CONFIG['servers'][Session::get("serverId")]['coin_id'], $coins, 0);
							}
							else {
								$error = _s("CHAR_MUST_OFFLINE");
							}
                            $account_model->IncrementExp(Session::get("accountId"), $amount * $_CONFIG['exp_per_dollar']);
                        }
						else {
                            $error = _s("INCORRECT_CHAR");
                        }
                    } 
					else {
                        $error = _s("INCORRECT_CHAR");
                    }
                }
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("COINS_TRANSFER_SUCCESS") : $error
        );

        echo json_encode($result);
    }

    public function changeCharName($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $char_name = isset($_POST['char_name']) ? $_POST['char_name'] : '';
            $new_char_name = isset($_POST['new_char_name']) ? $_POST['new_char_name'] : '';

            if ($char_name == '' || $new_char_name == '' || !ctype_alnum($new_char_name) || strlen($new_char_name)>15) {
                $error = _s("INCORRENT_CHA_NAME");
            } else {
                $account_model = new Account_Model();
                $account_data = $account_model->getAccountData(Session::get("accountId"));

                if (5 > $account_data['balance']) {
                    $error = _s("NOT_ENOUGH_MONEY");
                } else {
                    $server_model = new Server_Model(Session::get("serverId"));
                    if ($server_model->CheckChar($char_name, Session::get("accountId"))) {
                        $character = $server_model->GetCharacter($char_name);
                        $new_character = $server_model->GetCharacter($new_char_name);
                        if ($new_character === false) {
                            if ($character !== false && $character['online'] == 0) {
                                $donate_model = new Donate_Model();
                                $donate_model->LogTransaction(_s("CHANGE_CHA_NAME"), -5, Session::get("accountId"), $character['char_name'].'/'.$new_char_name, Session::get("serverId"));
                                $donate_model->DeductBalance(Session::get("accountId"), 5);

                                $server_model->ChangeName($char_name, $new_char_name);
                                $account_model->IncrementExp(Session::get("accountId"), 5 * $_CONFIG['exp_per_dollar']);
                            } else {
                                $error = _s("CHAR_MUST_OFFLINE");
                            }
                        } else {
                            $error = _s("CHA_SAME_NICK_EXISTS");
                        }
                    } else {
                        $error = _s("INCORRECT_CHAR");
                    }
                }
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("NAME_SUCCESS_CHANGE") : $error
        );

        echo json_encode($result);
    }

    public function changeCharGender($data = array()) {
        global $_CONFIG;
        $error = "";

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            $error = _s("NOT_ENTERED");
        } else {
            $char_name = isset($_POST['char_name']) ? $_POST['char_name'] : '';

            if ($char_name == '') {
                $error = _s("INCORRECT_CHAR");
            } else {
                $account_model = new Account_Model();
                $account_data = $account_model->getAccountData(Session::get("accountId"));

                if (5 > $account_data['balance']) {
                    $error = _s("NOT_ENOUGH_MONEY");
                } else {
                    $server_model = new Server_Model(Session::get("serverId"));
                    if ($server_model->CheckChar($char_name, Session::get("accountId"))) {
                        $character = $server_model->GetCharacter($char_name);
                        if ($character !== false && $character['online'] == 0) {
                            $donate_model = new Donate_Model();
                            $donate_model->LogTransaction(_s("CHANGE_CHA_SEX"), -5, Session::get("accountId"), $character['char_name'], Session::get("serverId"));
                            $donate_model->DeductBalance(Session::get("accountId"), 5);

                            if ($character['sex'] == 0)
                                $server_model->ChangeGender($char_name, 1);
                            else
                                $server_model->ChangeGender($char_name, 0);

                            $account_model->IncrementExp(Session::get("accountId"), 5 * $_CONFIG['exp_per_dollar']);
                        } else {
                            $error = _s("CHAR_MUST_OFFLINE");
                        }
                    } else {
                        $error = _s("INCORRECT_CHAR");
                    }
                }
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SEX_SUCCESS_CHANGED") : $error
        );

        echo json_encode($result);
    }
}