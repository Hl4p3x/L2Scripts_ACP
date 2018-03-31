<?php
if (!defined('ctx')) die();

class Auction_Controller extends Controller {
    public $item_grades = array(
	"none" => 0,
	"d" => 1,
	"c" => 2,
	"b" => 3,
	"a" => 4,
	"s" => 5,
	"s80" => 6,
	"s84" => 7,
	"r" => 8,
	"r95" => 9,
	"r99" => 10
    );

    public function __construct() {
        parent::__construct();

        global $_CONFIG;
        if ($_CONFIG['features_enabled']['auction'] !== true)
        	die();

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $account_model = new Account_Model();
        $this->account_data = $account_model->getAccountData(Session::get("accountId"));

        $this->view->title = _s("TITLE_AUCTION");
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
        
        $this->view->features = $_CONFIG['features_enabled'];
    }

    public function index($data = array()) {
        if ($this->view->auction_page == "") {
            $this->view->auction_page = "all";
        }
        $current_page = isset($_POST['page']) ? $_POST['page'] : '1';

        $this->filter['enchant'] = isset($_POST['enchant']) ? $_POST['enchant'] : '';
        $this->filter['grade'] = isset($_POST['grade']) ? $_POST['grade'] : 'all';
        $this->filter['category'] = isset($_POST['item_type']) ? $_POST['item_type'] : 'all';
        $this->filter['grade'] = isset($_POST['grade']) ? $_POST['grade'] : 'all';
        $this->filter['page'] = $current_page;

        $count = $this->model->GetItemCount($this->filter);
        $items = $this->model->GetItems($this->filter);
        $now = time();

        $this->view->auction_items = array();
        $this->view->current_page = $current_page;
        $this->view->total_pages = max(1,ceil($count/10));
        $this->view->pending_withdraw = $this->model->GetPendingMoney(Session::get("accountId"), false);

        foreach ($items as $item) {
            $this->view->auction_items[] = array(
                'auction_id' => $item['id'],
                'enchant' => $item['item_enchant'],
                'count' => $item['item_count'],
                'current_bid' => $item['current_bid'],
                'buynow_price' => $item['buynow_price'],
                'step' => $item['step'],
                'end' => ($item['end_date'] - $now),
                'name' => get_item_name($item['item_type']),
                'alt_name' => get_item_alt_name($item['item_type']),
                'icon' => get_item_icon($item['item_type']),
                'grade' => get_item_grade($item['item_type'])
            );
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $result = array(
                'result' => true,
                'data' => $this->view->render("auction/item_list", false, false, true)
            );

            echo json_encode($result);
        } else {
            $this->view->render("auction/index");
        }
    }

    public function created($data = array()) {
        $this->view->auction_page = "created";
        $this->filter['created'] = true;
        $this->filter['owner_id'] = Session::get("accountId");
        $this->index($data);
    }

    public function unsold($data = array()) {
        $this->view->auction_page = "unsold";
        $this->filter['unsold'] = true;
        $this->filter['owner_id'] = Session::get("accountId");
        $this->index($data);
    }

    public function bought($data = array()) {
        $this->view->auction_page = "bought";
        $this->filter['bought'] = true;
        $this->filter['bidder_id'] = Session::get("accountId");
        $this->index($data);
    }

    public function bids($data = array()) {
        $this->view->auction_page = "bids";
        $this->filter['bids'] = true;
        $this->filter['bidder_id'] = Session::get("accountId");
        $this->index($data);
    }

    public function create($data = array()) {
        global $_CONFIG;

        $error = "";

        $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : 0;
        $count = isset($_POST['count']) ? $_POST['count'] : 0;
        $start_price = isset($_POST['start_price']) ? $_POST['start_price'] : 0;
        $price_buy = isset($_POST['price_buy']) ? $_POST['price_buy'] : 0;
        $price_step = isset($_POST['price_step']) ? $_POST['price_step'] : 0;

        $renewal = isset($_POST['renewal']) ? $_POST['renewal'] : 'false';
        $period = isset($_POST['period']) ? $_POST['period'] : 0;

        if ($item_id == 0 || $count <= 0) {
            $error = _s("INCORRECT_ITEM");
        }

        if ($start_price <= 0 || $price_buy <= 0 || $price_step <= 0 || $price_buy <= $start_price) {
            $error = _s("INVALID_COUNT");
        }

        $period = 1; // hardcoded for now

        $server_model = new Server_Model(Session::get('serverId'));
        $item_data = $server_model->GetItemData(Session::get('accountId'), $item_id);
        if ($item_data == false || $item_data['count'] < $count) {
            $error = _s("INCORRECT_ITEM");
        }

        if ($item_data['online'] != 0) {
            $error = _s("CHAR_MUST_OFFLINE");
        }

        if (in_array($item_data['item_id'], $_CONFIG['auction_item_blocklist'])) {
            $error = _s("ITEM_NOT_SELLABLE");
        }

        if ($error == "") {
            if ($server_model->DeleteItem($item_id, $count)) {
                $data = array(
                    'owner_id' => Session::get('accountId'),
                    'character_id' => $item_data['owner_id'],
                    'item_type' => $item_data['item_id'],
                    'item_enchant' => $item_data['enchant_level'],
                    'item_grade' => $this->item_grades[get_item_grade($item_data['item_id'])],
                    'item_count' => $count,
                    'item_category' => get_item_category($item_data['item_id']),
                    'current_bid' => $start_price,
                    'bidder_id' => 0,
                    'buynow_price' => $price_buy,
                    'step' => $price_step,
					'server_id' => Session::get("serverId"),
					'received_money' => 0,
					'received_item' => 0
                );

                /*if ($_CONFIG['auction_require_approval'] == true) {
                    $data['end_date'] = 0;
                    $data['approved'] = 0;
                } else {*/
                    $data['end_date'] = time() + (86400 * $period);
                    $data['approved'] = 1;
                //}

                if (!$this->model->CreateAuction($data)) {
                    // error creating auction
                }
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("AUCTION_CREATED") : $error,
        );

        echo json_encode($result);
    }

    public function bid($data = array()) {
        $error = "";
        $balance = $this->account_data['balance'];

        if (isset($_POST['lot_id'])) {
            $lot_id = $_POST['lot_id'];

            $lot = $this->model->GetAuction($lot_id);
            if ($lot == false || $lot['end_date'] < time()) {
                $error = _s("AUCTION_ENDED");
            }

            if ($lot['owner_id'] == Session::get("accountId")) {
                $error = _s("CANT_BID_YOURSELF");
            }

            if ($lot['bidder_id'] == Session::get("accountId")) {
                $error = _s("YOU_HIGHEST_BIDDER");
            }

            $new_price = $lot['current_bid'] + $lot['step'];
            if ($new_price > $this->account_data['balance']) {
                $error = _s("NOT_ENOUGH_BID_MONEY");
            }

            if ($error == "") {
                $donate_model = new Donate_Model();
                if ($lot['bidder_id'] != 0) {
                    $donate_model->LogTransaction(_s("RETURN_AUCTION"), $lot['current_bid'], $lot['bidder_id']);
                    $donate_model->AddBalance($lot['bidder_id'], $lot['current_bid']);
                }

                $donate_model->LogTransaction(_s("LOG_BID"), -$new_price, Session::get("accountId"));
                $donate_model->DeductBalance(Session::get("accountId"), $new_price);
                $balance -= $new_price;

                $this->model->Bid($lot_id, Session::get("accountId"), $new_price);
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_BID") : $error,
            'balance' => number_format($balance, 2, ".", "")
        );

        echo json_encode($result);
    }

    public function buynow($data = array()) {
        $error = "";
        $balance = $this->account_data['balance'];
        if (isset($_POST['lot_id'])) {
            $lot_id = $_POST['lot_id'];

            $lot = $this->model->GetAuction($lot_id);
            if ($lot == false || $lot['end_date'] < time()) {
                $error = _s("AUCTION_ENDED");
            }

            if ($lot['owner_id'] == Session::get("accountId")) {
                $error = _s("CANT_BUY_OWN_LOT");
            }

            if ($lot['buynow_price'] > $this->account_data['balance']) {
                $error = _s("NOT_ENOUGH_BID_MONEY");
            }

            if ($error == "") {
                $donate_model = new Donate_Model();
                if ($lot['bidder_id'] != 0) {
                    $donate_model->LogTransaction(_s("RETURN_AUCTION"), $lot['current_bid'], $lot['bidder_id']);
                    $donate_model->AddBalance($lot['bidder_id'], $lot['current_bid']);
                }

                $donate_model->LogTransaction(_s("LOG_BUY_NOW"), -$lot['buynow_price'], Session::get("accountId"));
                $donate_model->DeductBalance(Session::get("accountId"), $lot['buynow_price']);
                $balance -= $lot['buynow_price'];

                $this->model->BuyNow($lot_id, Session::get("accountId"), $lot['buynow_price']);
            }
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_BUY") : $error,
            'balance' => number_format($balance, 2, ".", "")
        );

        echo json_encode($result);
    }

    public function getlot($data = array()) {
        $error = "";

        $lot_id = isset($_POST['lot_id']) ? $_POST['lot_id'] : 0;
        if ($lot_id > 0) {
            $donate_model = new Donate_Model();
            $auction = $this->model->GetAuction($lot_id);
            $server_model = new Server_Model($auction['server_id']);
            if ($auction['owner_id'] == Session::get('accountId')) {
                // my auction
                $char = $server_model->GetCharacterById($auction['character_id']);
                if ($char !== false && $char['online'] == 0) {
                    if ($auction['received_item'] == 0) {
                        if ($auction['end_date'] > time()) {
                            // active
                            $this->model->EndAuction($lot_id);
                            if ($auction['bidder_id'] != 0) {
                                $donate_model->LogTransaction(_s("RETURN_AUCTION"), $auction['current_bid'], $auction['bidder_id']);
                                $donate_model->AddBalance($auction['bidder_id'], $auction['current_bid']);
                            }

                            $this->model->ReceivedItem($lot_id);
                            $server_model->AddItem($char['obj_Id'], $auction['item_type'], $auction['item_count'], $auction['item_enchant']);
                        } else if ($auction['bidder_id'] == 0) {
                            $this->model->ReceivedItem($lot_id);
                            $server_model->AddItem($char['obj_Id'], $auction['item_type'], $auction['item_count'], $auction['item_enchant']);
                        } else {
                            $error = _s("CANT_BUY_THIS_LOT");
                        }
                    }
                } else {
                    $error = _s("CHAR_MUST_OFFLINE");
                }
            } else {
                if ($auction['bidder_id'] == Session::get('accountId')) {
                    // my purchase
                    if ($auction['received_item'] == 0) {
                        if ($auction['end_date'] < time()) {
                            // ended
                            $char_name = isset($_POST['char_name']) ? $_POST['char_name'] : '';
                            $char = $server_model->GetCharacter($char_name);
                            if ($char !== false && $char['online'] == 0) {
                                $this->model->ReceivedItem($lot_id);
                                $server_model->AddItem($char['obj_Id'], $auction['item_type'], $auction['item_count'], $auction['item_enchant']);
                            } else {
                                $error = _s("CHAR_MUST_OFFLINE");
                            }
                        } else {
                            $error = _s("CANT_BUY_THIS_LOT");
                        }
                    }
                }
            }
        } else {
            $error = _s("INCORRECT_LOT");
        }

        $result = array(
            'result' => $error == "" ? true : false,
            'message' => $error == "" ? _s("SUCCESS_RECEIVED") : $error
        );

        echo json_encode($result);
    }

    public function withdraw($data = array()) {
        $pending = $this->model->GetPendingMoney(Session::get("accountId"), true);
        if (intval($pending) > 0) {
            $donate_model = new Donate_Model();
            $donate_model->LogTransaction(_s("LOG_SEND_BID"), $pending, Session::get("accountId"));
            $donate_model->AddBalance(Session::get("accountId"), $pending);
        }
        $this->view->redirect("auction/index");
    }
}