<?php
if (!defined('ctx')) die();
// pw_widget_url

class Account_Controller extends Controller {

    private $account_data = false;

    function __construct() {
        parent::__construct();
        $this->view->title = "";
        $this->view->page = "account";
        $this->view->show_nav = false;
    }

    private function prepareSidebar() {
        global $_CONFIG;
        if (Session::get("loggedIn")) {
            $this->account_data = $this->model->getAccountData(Session::get("accountId"));

            $this->view->pw_widget_url = str_replace("[USER_ID]", Session::get("accountId"), PAYMENTWALL_WIDGET_URL);
            $this->view->servers = $_CONFIG['servers'];
            $this->view->active_server = Session::get("serverId");
            $this->view->email = $this->account_data['email'];
            $this->view->balance = number_format($this->account_data['balance'], 2, ".", "");
            $this->view->uid = Session::get("accountId");

            $server_model = new Server_Model(Session::get("serverId"));
            $this->view->game_accounts = $server_model->getAccounts(Session::get("accountId"), true);
            $this->view->max_accounts = $_CONFIG['servers'][Session::get("serverId")]['max_accounts'];

            $this->view->account_level = get_account_level($this->account_data['account_exp']);
            $this->view->account_exp = $this->account_data['account_exp'];
            $this->view->exp_percent = get_exp_percent($this->account_data['account_exp']);
            $this->view->bonus_percent = $_CONFIG['account_level_discount'][$this->view->account_level];
            $this->view->coin_ratio = (($this->view->bonus_percent / 100) * COINS_PER_DOLLAR) + COINS_PER_DOLLAR;
            if (!is_int($this->view->coin_ratio))
                $this->view->coin_ratio = number_format($this->view->coin_ratio, 2, ".", "");
                
    	    $this->view->features = $_CONFIG['features_enabled'];
        }
    }

    function index($data = array()) {
        global $_CONFIG;

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            $this->view->redirect("account/login");
            exit();
        }


        // user is logged-in beyond this point
        $this->prepareSidebar();

        $this->view->show_nav = true;
        $this->view->body_class = "skin-dark";
        $this->view->title = _s("TITLE_INDEX");

        $last_login = $this->model->getLastLogin(Session::get("accountId"));
        $this->view->lastip = $last_login['ip'];

        $this->view->lasttime = date("j/m/Y H:i", $last_login['time']);
        
        $auction_model = new Auction_Model();
        $items = $auction_model->GetWidgetItems();
        $this->view->auction_items = array();
        foreach ($items as $item) {
            $this->view->auction_items[] = array(
                'item_name' => get_item_name($item['item_type']),
                'item_altname' => get_item_alt_name($item['item_type']),
                'item_grade' => get_item_grade($item['item_type']),
                'item_icon' => get_item_icon($item['item_type']),
                'item_enchant' => $item['item_enchant'] > 0 ? $item['item_enchant'] : '',
                'current_bid' => $item['current_bid'],
            );
        }

        $this->view->render("account/index");
    }

    public function stat($data = array()) {
        global $_CONFIG;

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $this->prepareSidebar();

        $server_model = new Server_Model(Session::get("serverId"));
        $this->view->stats = $server_model->getStats(Session::get("accountId"));

        $this->view->body_class = "skin-dark";
        $this->view->show_nav = true;
        $this->view->title = _s("TITLE_STATISTICS");
        $this->view->render('account/stats');
    }

    function login($data = array()) {
        global $_CONFIG;

        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // first do captcha!
            if (!Captcha::ValidateCaptcha()) {
                $error = _s("INVALID_CAPTCHA");
            } else {
                $login_data = array(
                    'master_name'  => isset($_POST['login']) ? $_POST['login'] : '',
                    'master_password' => isset($_POST['password']) ? $_POST['password'] : '',
                );

                $server_id = isset($_POST['server_id']) ? $_POST['server_id'] : 0;

                // validate!
                if ($login_data['master_name'] == "") {
                    $error = _s("INVALID_USERNAME");
                }

                if ($login_data['master_password'] == "") {
                    $error = _s("INVALID_PASSWORD");
                }

                $email = false;
                if (valid_email($login_data['master_name'])) {
                    $email = true;
                }
                if ($email) {
                    if (!$this->model->CheckEmail($login_data['master_name'])) {
                        $error = _s("INVALID_USER_PASS");
                    }
                } else {
                    if (!ctype_alnum($login_data['master_name'])) {
                        $error = _s("INVALID_USERNAME");
                    } else if (!$this->model->CheckAccount($login_data['master_name'])) {
                        $error = _s("INVALID_USER_PASS");
                    }
                }

                if ($error == "") {
                    $result = $this->model->AttemptLogin($login_data, $email);
                    if ($result !== false) {
                        $account_data = $this->model->getAccountData($result);
                        Session::set("loggedIn", true);
                        Session::set("accountId", $result);
                        Session::set("userAgent", $_SERVER['HTTP_USER_AGENT']);
                        Session::set("serverId", $server_id);
                        Session::set("email", $account_data['email']);
                        if (isset($_POST['remember'])) {
                            $params = session_get_cookie_params();
                            setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                        }
                        $this->view->redirect("account/index");
                        exit();
                    } else {
                        $error = $this->model->error;
                    }
                }
            }
        }
		
        $this->view->servers = $_CONFIG['servers'];
        $this->view->active_server = 0;
        $this->view->error = $error;
        $this->view->show_nav = false;
        $this->view->body_class = "login";
        $this->view->title = _s("TITLE_LOGIN");
        $this->view->render("account/login");
    }

    function register($data = array()) {
        global $_CONFIG;
        $this->view->title = _s("TITLE_REGISTER");
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // first do captcha!
            if (!Captcha::ValidateCaptcha()) {
                $error = _s("INVALID_CAPTCHA");
            } else {
                $server_id = isset($_POST['server_id']) ? $_POST['server_id'] : 0;
                $register_data = array(
                    'master_name'  => isset($_POST['login']) ? $_POST['login'] : '',
                    'master_password' => isset($_POST['password']) ? $_POST['password'] : '',
                    'email'           => isset($_POST['email']) ? $_POST['email'] : '',
                );

                $password2 = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

                // validate!
                if ($register_data['master_name'] == "" || !ctype_alnum($register_data['master_name'])) {
                    $error = _s("INVALID_USERNAME");
                }
                
                if (strlen($register_data['master_name']) < 4 || strlen($register_data['master_name']) > 16) {
                    $error = _s("INVALID_USERNAME");
                }
                
                if (strlen($register_data['master_password']) < 6 || strlen($register_data['master_password']) > 16) {
                    $error = _s("PASS_SIX_DIGIT");
                }

                if ($register_data['master_password'] == "" || $register_data['master_password'] != $password2) {
                    $error = _s("INVALID_PASSWORD");
                }

                if ($register_data['email'] == "" || valid_email($register_data['email']) === false) {
                    $error = _s("INVALID_MAIL");
                }

                if ($this->model->CheckEmail($register_data['email'])) {
                    $error = _s("MAIL_TAKEN");
                }

                if ($this->model->CheckAccount($register_data['master_name'])) {
                    $error = _s("ACCOUNT_TAKEN");
                }

                $server_model = new Server_Model($server_id);
                if (!$server_model->CheckGameAccount($register_data['master_name'])) {
                    $error = _s("ACCOUNT_TAKEN");
                }

                if ($error == "") {
                    // valid, create acc
                    //$register_data['master_password'] = password_hash($register_data['master_password'], PASSWORD_BCRYPT);
                    $plain_password = $register_data['master_password'];
                    $register_data['master_password'] = hash(HASH_ALGO, $plain_password);
                    $uid = $this->model->CreateAccount($register_data);

                    //$game_password = pass_encode($plain_password, PASSWORD_HASH_ALGORITHM);
                    //$server_model->CreateAccount($uid, $register_data['master_name'], $game_password);

                    $this->view->login = $register_data['master_name'];
                    $this->view->pass = $plain_password;
                    
                    $mailer = new AccountMailer();
                    $mailer->SendMail($_POST['email'], $_CONFIG['mail_register_subject'], $this->view->render('emails/registered', false, false, true));

                    $tempId = $this->model->InsertTempAccount($register_data['master_name'], $plain_password);
                    
                    echo json_encode(array(
                        'result' => true,
                        'message' => _s("SUCCESS_ACOUNT"),
                        'successId' => $tempId
                    ));
                    exit();
                }
            }
            echo json_encode(array(
                'result' => false,
                'message' => $error
            ));
            exit();
        }
        
        $this->view->servers = $_CONFIG['servers'];
        $this->view->error = $error;
        $this->view->show_nav = false;
        $this->view->body_class = "login";
        $this->view->render("account/register");
    }
    
    function regsuccess($data = array()) {
        if (count($data) > 0) {
            $temp_account = $this->model->GetTempAccount($data[0]);
            if ($temp_account !== false) {
                $this->view->login = $temp_account['login'];
                $this->view->pass = $temp_account['password'];
                $this->view->email = $this->model->GetEmailFromLogin($temp_account['login']);
                
                $data = $this->view->render('account/registersuccesstxt', false, false, true);
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="login.txt"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . strlen($data));
                echo $data;
            }
        }
    }

    function logout(){
        Session::destroy();
        $this->view->redirect("account/login");
    }

    function recovery($data = Array()){
        global $_CONFIG;
        $error = false;
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if(isset($_POST['email'])){
                $newpass = random_token(12);
                $this->view->newpass = $newpass;
                $this->model->resetPassword($newpass, $_POST['email']);
                
                $mailer = new AccountMailer();
                $mailer->SendMail($_POST['email'], $_CONFIG['mail_recover_subject'], $this->view->render('emails/recovery', false, false, true));

                $this->view->body_class = "login";
                $this->view->message = _s("INST_SENT");
                $this->view->title = _s("RECOVER_ACCOUNT");
                $this->view->render('account/recover');
                die();
            }
        }

        $this->view->body_class = "login";
        $this->view->message = "";
        $this->view->title = _s("RECOVER_ACCOUNT");
        $this->view->render('account/recover');
    }
    
    function langru() {
        Session::set("lang", "ru");
        $this->view->redirect("account/index");
    }
    
    function langen() {
        Session::set("lang", "en");
        $this->view->redirect("account/index");
    }
}