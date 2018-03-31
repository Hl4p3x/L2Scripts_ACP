<?php
if (!defined('ctx')) die();

class Ticket_Controller extends Controller {

	public $status_text = array();

    public function __construct() {
		
        $this->status_text[0] = _s("CLOSED");
        $this->status_text[1] = _s("PENDING");
        $this->status_text[2] = _s("ANSWER_GOT");
		
        global $_CONFIG;
        if ($_CONFIG['features_enabled']['ticket'] !== true)
        	die();
        parent::__construct();

        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $account_model = new Account_Model();
        $this->account_data = $account_model->getAccountData(Session::get("accountId"));

        $this->view->title = _s("TITLE_TICKET");
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

        $server_model = new Server_Model(Session::get("serverId"));
        $this->view->game_accounts = $server_model->getAccounts(Session::get("accountId"), true);
        $this->view->max_accounts = $_CONFIG['servers'][Session::get("serverId")]['max_accounts'];
        
        $this->view->features = $_CONFIG['features_enabled'];
    }

    public function index($data = array()) {
        global $_CONFIG;

        $this->view->tickets = array();
        if ($this->account_data['admin'] == 1) {
            $ticket_list = $this->model->getAllTickets();
        } else {
            $ticket_list = $this->model->getTickets(Session::get("accountId"));
        }

        if ($ticket_list !== false) {
            foreach ($ticket_list as $ticket) {
                $this->view->tickets[] = array(
                    'id' => $ticket['id'],
                    'title' => $ticket['title'],
                    'status' => $this->status_text[$ticket['status']],
                    'server' => isset($_CONFIG['servers'][$ticket['server_id']]) ? $_CONFIG['servers'][$ticket['server_id']]['name'] : _s("NO_SERVER"),
                    'account' => $ticket['account'],
                    'create_date' => date("j/m/Y H:i", $ticket['create_date'])
                );
            }
        }

        $this->view->admin = $this->account_data['admin'];
        $this->view->render("ticket/index");
    }

    public function create($data = array()) {
        global $_CONFIG;

        $server_id = Session::get("serverId");
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $title = isset($_POST['ticket_title']) ? $_POST['ticket_title'] : "";
            $server_id = isset($_POST['server_id']) ? $_POST['server_id'] : 0;
            $content = isset($_POST['ticket_content']) ? $_POST['ticket_content'] : "";
            $account = isset($_POST['account']) ? $_POST['account'] : _s("NOTHING");
            $character = isset($_POST['character']) ? $_POST['character'] : _s("NOTHING");

            if (!isset($_CONFIG['servers'][$server_id]))
                $server_id = 0;

            if ($title != "" && $content != "") {
                $ticket_id = $_CONFIG['servers'][$server_id]['ticket_prefix'].mt_rand(100,999)."-".mt_rand(1000,9999);
                $ticket_data = array(
                    'id' => $ticket_id,
                    'ma_id' => Session::get("accountId"),
                    'title' => htmlentities(substr($title, 0, 255)),
                    'status' => 1,
                    'server_id' => $server_id,
                    'account' => $account,
                    'character' => $character,
                    'create_date' => time()
                );
                $this->model->newTicket($ticket_data);

                require ROOT_PATH."/libs/htmlpurifier/HTMLPurifier.auto.php";
                require ROOT_PATH."/libs/htmlpurifier/HTMLPurifier.func.php";

                $comment_data = array(
                    'ticket_id' => $ticket_id,
                    'content' => HTMLPurifier($content),
                    'commenter' => Session::get("email"),
                    'post_date' => time()
                );
                $this->model->newComment($comment_data);

                $this->view->redirect("ticket/view/".$ticket_id);
                exit();
            }
        }

        $server_model = new Server_Model($server_id);
        $this->view->ticket_server_id = $server_id;

        $this->view->accounts = $server_model->getAccounts(Session::get("accountId"), false);
        $this->view->selected_account = count($this->view->accounts) > 0 ? $this->view->accounts[0] : "";
        $this->view->characters = count($this->view->accounts) > 0 ? $server_model->getCharacters($this->view->accounts[0]) : array();

        $this->view->render("ticket/create");
    }

    public function view($data = array()) {
        global $_CONFIG;
        if (count($data) < 1) {
            $this->view->redirect("ticket/index");
        } else {
            $ticket = $this->model->getTicket($data[0], true);
            if ($ticket !== false) {
                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    if (Session::get("accountId") == $ticket['ma_id'] || $this->account_data['admin'] == 1) {
                        $comment_contents = isset($_POST['comment_contents']) ? $_POST['comment_contents'] : "";
                        if ($comment_contents != "") {
                            require ROOT_PATH."/libs/htmlpurifier/HTMLPurifier.auto.php";
                            require ROOT_PATH."/libs/htmlpurifier/HTMLPurifier.func.php";

                            $comment_data = array(
                                'ticket_id' => $ticket['id'],
                                'content' => HTMLPurifier($comment_contents),
                                'commenter' => $this->account_data['admin'] == 0 ? Session::get("email") : ($this->account_data['support_name'] == "" ? "Admin" : $this->account_data['support_name']),
                                'post_date' => time()
                            );
                            $this->model->newComment($comment_data, $this->account_data['admin']);

                            $this->view->redirect("ticket/view/".$ticket['id']);
                            exit();
                        }
                    }
                }

                $this->view->ticket = array(
                    'id' => $ticket['id'],
                    'title' => $ticket['title'],
                    'status' => $this->status_text[$ticket['status']],
                    'server' => isset($_CONFIG['servers'][$ticket['server_id']]) ? $_CONFIG['servers'][$ticket['server_id']]['name'] : _s("NO_SERVER"),
                    'account' => $ticket['account'],
                    'character' => $ticket['character'],
                    'create_date' => date("j/m/Y H:i", $ticket['create_date']),
                    'comments' => $ticket['comments']
                );
                $this->view->admin = $this->account_data['admin'];
                $this->view->render("ticket/view");
            } else {
                $this->view->redirect("ticket/index");
            }
        }
    }

    public function ajaxGetAccChar($data = array()) {
        global $_CONFIG;

        $login = isset($_POST['login']) ? $_POST['login'] : "";
        $server_id = isset($_POST['server_id']) ? $_POST['server_id'] : 0;

        // default to empty
        $this->view->accounts = array();
        $this->view->characters = array();

        if (isset($_CONFIG['servers'][$server_id])) {
            $server_model = new Server_Model($server_id);
            if ($login != "" && $server_model->MACheck(Session::get('accountId'), $login)) {
                $this->view->accounts = $server_model->getAccounts(Session::get("accountId"), false);
                $this->view->selected_account = $login;
                $this->view->characters = $server_model->getCharacters($login);
            } else {
                $this->view->accounts = $server_model->getAccounts(Session::get("accountId"), false);
                $this->view->selected_account = $this->view->accounts[0];
                $this->view->characters = $server_model->getCharacters($this->view->accounts[0]);
            }
        }

        $return_data = array(
            'acc' => $this->view->render('ticket/ajax/account_select', false, false, true),
            'chars' => $this->view->render('ticket/ajax/character_select', false, false, true)
        );

        echo json_encode($return_data);
    }

    public function close($data = array()) {
        if ($this->account_data['admin'] == 1 && count($data) > 0) {
            $ticket = $this->model->closeTicket($data[0]);
        }
        $this->view->redirect("ticket/index");
    }

    public function closed($data = array()) {
        global $_CONFIG;

        $this->view->tickets = array();
        if ($this->account_data['admin'] == 0) {
            $this->view->redirect("ticket/index");
        } else {
            $ticket_list = $this->model->getAllTickets(0);

            if ($ticket_list !== false) {
                foreach ($ticket_list as $ticket) {
                    $this->view->tickets[] = array(
                        'id' => $ticket['id'],
                        'title' => $ticket['title'],
                        'status' => $this->status_text[$ticket['status']],
                        'server' => isset($_CONFIG['servers'][$ticket['server_id']]) ? $_CONFIG['servers'][$ticket['server_id']]['name'] : _s("NO_SERVER"),
                        'account' => $ticket['account'],
                        'create_date' => date("j/m/Y H:i", $ticket['create_date'])
                    );
                }
            }

            $this->view->admin = 1;
            $this->view->render("ticket/closed");
        }
    }
}