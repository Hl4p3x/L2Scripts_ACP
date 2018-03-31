<?php
if (!defined('ctx')) die();

class Vote_Controller extends Controller {

    public function __construct() {
        parent::__construct();
        global $_CONFIG;
        if ($_CONFIG['features_enabled']['vote'] !== true)
        	die();
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }
        $this->server_model = new Server_Model(Session::get("serverId"));
        $this->account_model = new Account_Model();

    }

    private function setupSidebar() {
        global $_CONFIG;

        $this->account_data = $this->account_model->getAccountData(Session::get("accountId"));

        $this->view->title = _s("TITLE_VOTE");
        $this->view->page = "ticket";
        $this->view->show_nav = true;
        $this->view->body_class = "skin-dark";


        $this->view->pw_widget_url = str_replace("[USER_ID]", Session::get("accountId"), PAYMENTWALL_WIDGET_URL);
        $this->view->servers = $_CONFIG['servers'];
        $this->view->active_server = Session::get("serverId");
        $this->view->email = $this->account_data['email'];
        $this->view->balance = number_format($this->account_data['balance'], 2, ".", "");
        $this->view->uid = Session::get("accountId");

        $this->view->account_level = get_account_level($this->account_data['account_exp']);
        $this->view->account_exp = $this->account_data['account_exp'];
        $this->view->exp_percent = get_exp_percent($this->account_data['account_exp']);
        $this->view->bonus_percent = $_CONFIG['account_level_discount'][$this->view->account_level];
        $this->view->coin_ratio = (($this->view->bonus_percent / 100) * COINS_PER_DOLLAR) + COINS_PER_DOLLAR;
        if (!is_int($this->view->coin_ratio))
            $this->view->coin_ratio = number_format($this->view->coin_ratio, 2, ".", "");


        $this->view->game_accounts = $this->server_model->getAccounts(Session::get("accountId"), true);
        $this->view->max_accounts = $_CONFIG['servers'][Session::get("serverId")]['max_accounts'];
        
        $this->view->features = $_CONFIG['features_enabled'];
    }

    public function index($data = array()) {
        global $_CONFIG;
        $this->setupSidebar();
        if (count($data) == 0 || (isset($data[0]) && !$this->server_model->CheckChar($data[0], Session::get("accountId")))) {
            $this->view->render("vote/character_select");
        } else {
            $this->view->character = htmlentities($data[0]);
            $last_votes = $this->model->GetLastVotes(Session::get("accountId"), $_SERVER['REMOTE_ADDR']);

            foreach ($_CONFIG['vote_sites'] as $site => $data) {
                $this->view->vote_sites[$site] = array(
                    'name' => $data['display_name'],
                    'last_vote' => isset($last_votes[$site]) ? $last_votes[$site]['vote_time'] : 0,
                    'vote_delay' => $data['vote_delay'],
                    'vote_button' => $data['vote_button'],
                    'rewarded' => $last_votes[$site]['rewarded'] == true ? 1 : 0
                );
            }
            $this->view->render("vote/vote_list");
        }
    }

    public function checkVote($data = array()) {
        global $_CONFIG;

        $return_data = array(
            'result' => false,
            'rewarded' => false,
            'message' => ''
        );

        if (count($data) == 0 || !isset($_CONFIG['vote_sites'][$data[0]])) {
            $return_data['message'] = _s("INCOR_ID_WEBSITE");
        } else {
            $site_id = $data[0];
            if ((time() - Session::get("last_check_" . $site_id)) > 300) {
                $site_data = $_CONFIG['vote_sites'][$site_id];
                if ($site_data['type'] == 'apicall') {
                    $last_vote = $this->model->GetLastVotes(Session::get("accountId"), $_SERVER['REMOTE_ADDR'], $site_id);
                    if ((time() - $last_vote[$site_id]['vote_time']) > ($site_data['vote_delay']*3600)) {
                        $url = str_replace("[IP]", $_SERVER['REMOTE_ADDR'], $site_data['api_url']);
                        $result = json_decode(file_get_contents($url));

                        $api_handler = new $site_data['api_handler'];
                        $vote_result = $api_handler->process($result);

                        if ($vote_result['voted'] == true) {
                            $vote_id = $this->model->SetLastVote(Session::get("accountId"), $_SERVER['REMOTE_ADDR'], $site_id, $vote_result['vote_time']);
                            $return_data['result'] = true;

                            // reward
                            if (isset($data[1])) {
                                $char = $data[1];

                                if ($this->server_model->CheckChar($char, Session::get("accountId"))) {
                                    $character = $this->server_model->GetCharacter($char);
                                    if ($character !== false && $character['online'] == 0) {
                                        $this->server_model->AddItem($character['obj_Id'], $_CONFIG['servers'][Session::get("serverId")]['coin_id'], $_CONFIG['vote_sites'][$site_id]['coins_per_vote'], 0);
                                        $this->model->SetVoteRewarded($vote_id);
                                        $return_data['rewarded'] = true;
                                    } else {
                                        $return_data['message'] = _s("SUCCESS_BUT_OFFLINE");
                                    }
                                } else {
                                    $return_data['message'] = _s("SUCCESS_BUT_TIME");
                                }
                            } else {
                                $return_data['message'] = _s("SUCCESS_BUT_TIME");
                            }
                        } else {
                            $return_data['message'] = _s("SUCCESS_BUT_TIME");
                        }
                    } else {
                        if ($last_vote[$site_id]['rewarded'] == true) {
                            $return_data['message'] = _s("ALREADY_GOT_REWARD");
                        } else {
                            // reward
                            if (isset($data[1])) {
                                $char = $data[1];

                                if ($this->server_model->CheckChar($char, Session::get("accountId"))) {
                                    $character = $this->server_model->GetCharacter($char);
                                    if ($character !== false && $character['online'] == 0) {
                                        $this->server_model->AddItem($character['obj_Id'], $_CONFIG['servers'][Session::get("serverId")]['coin_id'], $_CONFIG['vote_sites'][$site_id]['coins_per_vote'], 0);
                                        $this->model->SetVoteRewarded($last_vote[$site_id]['vote_id']);
                                        $return_data['result'] = true;
                                        $return_data['rewarded'] = true;
                                    } else {
                                        $return_data['message'] = _s("SUCCESS_BUT_OFFLINE");
                                    }
                                } else {
                                    $return_data['message'] = _s("SUCCESS_BUT_TIME");
                                }
                            } else {
                                $return_data['message'] = _s("SUCCESS_BUT_TIME");
                            }
                        }
                    }
                }
            } else {
                $return_data['message'] = _s("DELAY_FIVE_MINS");
            }

            Session::set("last_check_" . $site_id, time());
        }

        echo json_encode($return_data);
    }
}