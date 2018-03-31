<?php
if (!defined('ctx')) die();

class LuckyWheel_Controller extends Controller {

    public function __construct() {
        parent::__construct();

        global $_CONFIG;
        if ($_CONFIG['features_enabled']['luckywheel'] !== true)
        	die();
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $this->account_model = new Account_Model();
        $this->account_data = $this->account_model->getAccountData(Session::get("accountId"));

        $this->view->title = _s("TITLE_LUCKYWHEEL");
        $this->view->page = "account";
        $this->view->show_nav = true;
        $this->view->body_class = "skin-dark";

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

        $this->view->auction_page = "";
        $this->filter = array();

        $this->view->price = $_CONFIG['luckywheel_price'];
        
        $this->view->features = $_CONFIG['features_enabled'];
    }

    public function index($data = array()) {
        global $_CONFIG;

        $this->view->prizes = array();
        $this->view->active_prizes = array();

        $prizes = $_CONFIG['luckywheel_prizes'];
        foreach ($prizes as $prize) {
            $this->view->prizes[] = array(
                'name' => get_item_name($prize['item_id']),
                'icon' => '/media/item/'.get_item_icon($prize['item_id']),
            );
        }

        shuffle($prizes);
        $prizes = array_slice($prizes, 0, 12);
        foreach ($prizes as $prize) {
            $this->view->active_prizes[] = array(
                'name' => get_item_name($prize['item_id']),
                'icon' => '/media/item/'.get_item_icon($prize['item_id']),
            );
        }

        $stats = $this->account_model->GetSpinStats(Session::get("accountId"));
        $this->view->user_spins = $stats['user_spins'];
        $this->view->total_spins = $stats['global_spins'];
        $this->view->render("luckywheel/index");
    }

    public function spin($data = array()) {
        global $_CONFIG;
        $error = "";
        $item = false;
        $balance = $this->account_data['balance'];
        if ($_CONFIG['luckywheel_price'] > $this->account_data['balance']) {
            $error = _s("NOT_ENOUGH_MONEY");
        } else {
            $char_name = isset($_POST['char_name']) ? $_POST['char_name'] : "";
            if ($char_name == "") {
                $error = _s("INCORRECT_CHAR");
            } else {
                $server_model = new Server_Model(Session::get("serverId"));
                $character = $server_model->GetCharacter($char_name);
                if ($character['online'] == 1) {
                    $error = _s("CHAR_MUST_OFFLINE");
                } 
				else {
                    $prize = $_CONFIG['luckywheel_prizes'][mt_rand(0,count($_CONFIG['luckywheel_prizes']))];
                    $donate_model = new Donate_Model();
                    $donate_model->LogTransaction(_s("LUCKY_WHEEL_LOG"), -$_CONFIG['luckywheel_price'], Session::get("accountId"));
                    $donate_model->DeductBalance(Session::get("accountId"), $_CONFIG['luckywheel_price']);
                    $balance -= $_CONFIG['luckywheel_price'];
					
                    $server_model->AddItem($character['obj_Id'], $prize['item_id'], $prize['count'], 0);

                    $item = array(
                        'item_name' => array('name' => get_item_name($prize['item_id']), 'sa' => get_item_alt_name($prize['item_id'])),
                        'count_item' => $prize['count'],
                        'item_img' => '/media/item/'.get_item_icon($prize['item_id'])
                    );

                    $this->account_model->IncrementSpinCount(Session::get("accountId"));
                    $this->account_model->IncrementExp(Session::get("accountId"), $_CONFIG['luckywheel_price'] * $_CONFIG['exp_per_dollar']);
                }
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? $item : $error,
            'balance' => number_format($balance, 2, ".", "")
        );

        echo json_encode($result);
    }
}
