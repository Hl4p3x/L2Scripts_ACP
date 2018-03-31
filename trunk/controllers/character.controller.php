<?php
if (!defined('ctx')) die();

class Character_Controller extends Controller {
    public function __construct() {
        parent::__construct();

        global $_CONFIG;
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $account_model = new Account_Model();
        $account_data = $account_model->getAccountData(Session::get("accountId"));

        $this->view->title = "";
        $this->view->page = "account";
        $this->view->show_nav = true;
        $this->view->body_class = "skin-dark";

        $this->view->pw_widget_url = str_replace("[USER_ID]", Session::get("accountId"), PAYMENTWALL_WIDGET_URL);
        $this->view->servers = $_CONFIG['servers'];
        $this->view->active_server = Session::get("serverId");
        $this->view->email = $account_data['email'];
        $this->view->balance = number_format($account_data['balance'], 2, ".", "");
        $this->view->uid = Session::get("accountId");

        $this->view->account_level = get_account_level($account_data['account_exp']);
        $this->view->account_exp = $account_data['account_exp'];
        $this->view->exp_percent = get_exp_percent($account_data['account_exp']);
        $this->view->bonus_percent = $_CONFIG['account_level_discount'][$this->view->account_level];
        $this->view->coin_ratio = (($this->view->bonus_percent / 100) * COINS_PER_DOLLAR) + COINS_PER_DOLLAR;
        if (!is_int($this->view->coin_ratio))
            $this->view->coin_ratio = number_format($this->view->coin_ratio, 2, ".", "");

        $server_model = new Server_Model(Session::get("serverId"));
        $this->view->game_accounts = $server_model->getAccounts(Session::get("accountId"), true);
        $this->view->max_accounts = $_CONFIG['servers'][Session::get("serverId")]['max_accounts'];

	$this->view->features = $_CONFIG['features_enabled'];
    }

    public function inventory($data = array()) {
        global $_CONFIG;

        $char_name = $data[0];
        $server_model = new Server_Model(Session::get("serverId"));
        if (count($data) > 0 && $server_model->CheckChar($char_name, Session::get("accountId"))) {
            $avatar_info = $server_model->GetAvatarInfo($char_name);
            $this->view->avatar = get_avatar($avatar_info[0], $avatar_info[1]);

            $this->view->paperdoll_left = array();
            $this->view->paperdoll_right = array();
            $this->view->inventory = array();

            $pd_left = array(
                1 => 0,
                10 => 1,
                11 => 2,
                6 => 3,
                12 => 4,
                -1 => 5,
                0 => 6,
                24 => 7,
                7 => 8
            );

            $pd_right = array(
                2 => 0,
                3 => 1,
                8 => 2,
                9 => 3,
                4 => 4,
                13 => 5,
                14 => 6,
                15 => 7,
                5 => 8
            );

            $this->view->char_name = htmlentities($char_name);
            $inventory = $server_model->GetInventory($char_name);
            foreach ($inventory['paperdoll'] as $item) {
                if (isset($pd_left[$item['loc_data']])) {
                    $this->view->paperdoll_left[$pd_left[$item['loc_data']]] = array(
                        'name' => get_item_name($item['item_id']),
                        'icon' => get_item_icon($item['item_id']),
                        'sa' => get_item_alt_name($item['item_id']),
                        'enchant' => $item['enchant_level'] != 0 ? $item['enchant_level'] : "",
                        'grade' => get_item_grade($item['item_id']),
                        'type' => $item['item_id'],
                        'id' => $item['object_id'],
                        'amount' => $item['count'],
                        'auctionable' => in_array($item['item_id'], $_CONFIG['auction_item_blocklist']) ? 'false' : 'true'
                    );
                } else if (isset($pd_right[$item['loc_data']])) {
                    $this->view->paperdoll_right[$pd_right[$item['loc_data']]] = array(
                        'name' => get_item_name($item['item_id']),
                        'icon' => get_item_icon($item['item_id']),
                        'sa' => get_item_alt_name($item['item_id']),
                        'enchant' => $item['enchant_level'] != 0 ? $item['enchant_level'] : "",
                        'grade' => get_item_grade($item['item_id']),
                        'type' => $item['item_id'],
                        'id' => $item['object_id'],
                        'amount' => $item['count'],
                        'auctionable' => in_array($item['item_id'], $_CONFIG['auction_item_blocklist']) ? 'false' : 'true'
                    );
                }
            }

            $this->view->adena = 0;
            foreach ($inventory['inventory'] as $item) {
                if ($item['item_id'] == 57) {
                    $this->view->adena = $item['count'];
                    continue;
                }

                $this->view->inventory[$item['loc_data']] = array(
                    'name' => get_item_name($item['item_id']),
                    'icon' => get_item_icon($item['item_id']),
                    'sa' => get_item_alt_name($item['item_id']),
                    'enchant' => $item['enchant_level'] != 0 ? $item['enchant_level'] : "",
                    'grade' => get_item_grade($item['item_id']),
                    'type' => $item['item_id'],
                    'id' => $item['object_id'],
                    'amount' => $item['count'],
                    'auctionable' => in_array($item['item_id'], $_CONFIG['auction_item_blocklist']) ? 'false' : 'true'
                );
            }

            $this->view->render("character/inventory");
        } else {
            $this->view->redirect("account/index");
            exit();
        }
    }
}

