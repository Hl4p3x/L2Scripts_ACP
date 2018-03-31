<?php

if (!defined('ctx'))
    die();

class Donate_Controller extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->title = "";
        $this->view->page = "account";
        $this->view->show_nav = true;
        $this->view->body_class = "skin-dark";
    }
    
    function g2a() {
    	global $_CONFIG;
    	
    	// check login
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }
        
    	$uid = Session::get("accountId");
    	$amount = $_POST['amount'];

	require_once ROOT_PATH."/libs/g2a/G2APay.class.php";
	$g2a = new G2APay\G2APay(G2A_API_HASH, G2A_API_SECRET, URL."/account/index", URL."/account/index", DONATE_CURRENCY);
	$g2a->setOrderId($uid);
	$g2a->AddItem(new G2APay\PayItem(0,0,"Donation",$amount,1,URL."/account/index"));
	$res = $g2a->CreateQuote();
	if ($res !== false) {
		$this->view->token = $res;
		$this->view->render("donate/g2a", false, false, false);
	} else {
		$this->view->render("donate/g2a_error", false, false, false);
	}
    }
    
    function g2a_handler() {
    	global $_CONFIG;
    	
    	require_once ROOT_PATH."/libs/g2a/G2APay.class.php";
	$g2a = new G2APay\G2APay(G2A_API_HASH, G2A_API_SECRET, URL."/account/index", URL."/account/index", DONATE_CURRENCY);
	$g2a_ipn = new G2APay\IPNHandler($g2a);

	if ($g2a_ipn !== false && is_array($g2a_ipn->postdata) && isset($g2a_ipn->postdata['transactionId'])) {
		$txn_id = $g2a_ipn->postdata['transactionId'];
		$uid = $g2a_ipn->postdata['userOrderId'];
		$amount = $g2a_ipn->postdata['amount'];;
		
		if ($g2a_ipn->Check() !== false) {
			if ($g2a_ipn->status == "complete" && $this->model->CheckVendorTransaction($txn_id, "g2a")) {
				$this->model->PaymentReceived($txn_id, $amount, "g2a", $uid);
	                	$account_model = new Account_Model();
	                	$account_model->IncrementExp($uid, $amount * $_CONFIG['exp_per_donate']);
			}
		}
	}
    }
    
    function paysera_handler() {
        global $_CONFIG;
        require_once ROOT_PATH."/libs/paysera/WebToPay.php";
        try {
            $response = WebToPay::checkResponse($_GET, array(
                'projectid'     => PAYSERA_PROJECTID,
                'sign_password' => PAYSERA_PROJECTPW,
            ));

            if ($response['test'] !== '0') {
                throw new Exception('Testing, real payment was not made');
            }
            if ($response['type'] !== 'macro') {
                throw new Exception('Only macro payment callbacks are accepted');
            }

            $orderId = $response['orderid'];
            $amount = $response['amount'];
            $currency = $response['currency'];
            
            if ($currency != PAYSERA_CURRENCY) {
                throw new Exception('Invalid currency.');
            }
            
            if (strstr($orderId, "_") === FALSE) {
                throw new Exception('Invalid orderId: ' + $orderId);
            }
            
            $orderId = explode("_", $orderId);
            $txn_id = $orderId[0];
            $uid = $orderId[1];
            
            if ($this->model->CheckVendorTransaction($txn_id, "paysera")) {
                $this->model->PaymentReceived($txn_id, $amount, "paysera", $uid);
                $account_model = new Account_Model();
                $account_model->IncrementExp($uid, $amount * $_CONFIG['exp_per_donate']);
            }
            echo 'OK';
        } catch (Exception $e) {
            echo get_class($e) . ': ' . $e->getMessage();
        }
    }
    
    function paysera() {
        global $_CONFIG;
        // check login
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }
        
        require_once ROOT_PATH."/libs/paysera/WebToPay.php";
        // setup transaction
        $order_id = random_token(20) . '_' . Session::get('accountId');
        $amount = $_POST['sum'];
        if ($amount > 0) {
            try {
                $request = WebToPay::redirectToPayment(array(
                    'projectid'     => PAYSERA_PROJECTID,
                    'sign_password' => PAYSERA_PROJECTPW,
                    'orderid'       => $order_id,
                    'amount'        => $amount,
                    'currency'      => PAYSERA_CURRENCY,
                    'country'       => PAYSERA_COUNTRY,
                    'accepturl'     => URL.'/account/home',
                    'cancelurl'     => URL.'/account/home',
                    'callbackurl'   => URL.'/donate/paysera_handler',
                    'test'          => 0,
                ));
            } catch (WebToPayException $e) {
                echo "Error processing payment for paysera, please contact an administrator.<br>\r\n";
                echo $e->getMessage();
            }
        }
    }
    
    function paypal_handler() {
        global $_CONFIG;
        require_once ROOT_PATH."/libs/paypal/PaypalIPN.php";
        $paypal = new PaypalIPN();
        
        $data['payment_status'] = $_POST['payment_status'];
        $data['txn_id'] = $_POST['txn_id'];
        $data['receiver_email'] = $_POST['receiver_email'];
        $data['payer_email'] = $_POST['payer_email'];
        $data['payment_amount'] = $_POST['mc_gross'];
        $data['payment_currency'] = $_POST['mc_currency'];
        $data['account'] = $_POST['custom']; // acc name sent by original post
        // Check that receiver_email is your Primary PayPal email
        if ($data['receiver_email'] != PAYPAL_RECEIVER_EMAIL) {
            // dont process payments to the non-donation email
            exit();
        }

        if ($data['payment_currency'] != PAYPAL_CURRENCY) {
            // dont process payments in other currencies
            exit();
        }
        
        if ($paypal->verifyIPN()) {
            if ($data['payment_status'] == "Completed") {
                if (!$this->model->CheckVendorTransaction($data['txn_id'], "paypal"))
                    return; // payment has already been handled
                
                $this->model->PaymentReceived($data['txn_id'], $data['payment_amount'], "paypal", $data['account']);
                $account_model = new Account_Model();
                $account_model->IncrementExp($data['account'], $data['payment_amount'] * $_CONFIG['exp_per_donate']);
            }
        }
        
        header("HTTP/1.1 200 OK");
    }
    
    public function pagseguro($data = array()) {
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }
        
        $amount = $_POST['sum'];
        if (empty($amount) || !is_numeric($amount)) {
            $this->view->redirect("account/index");
        } else {
            require_once ROOT_PATH."/libs/PagSeguroLibrary/PagSeguroLibrary.php";

            $paymentRequest = new PagSeguroPaymentRequest();
            $paymentRequest->addParameter('shippingAddressRequired', 'false');
            $paymentRequest->setCurrency(PAGSEGURO_CURRENCY);
            $paymentRequest->addItem('0001', 'Coins', 1, $amount);
            
            $paymentRequest->setReference(Session::get('accountId'));
            
            $paymentRequest->setRedirectUrl(URL."/account/index");
            $paymentRequest->addParameter('notificationURL', URL.'/donate/pagseguro_handler');
             
            $credentials = new PagSeguroAccountCredentials(PAGSEGURO_LOGIN, PAGSEGURO_AUTHCODE);
            $this->view->url = $paymentRequest->register($credentials);
            $this->view->render("donate/pagseguro", false, false, false);
        }
    }
    
    public function pagseguro_handler($data = array()) {
        $code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ? trim($_POST['notificationCode']) : null);
        $type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ? trim($_POST['notificationType']) : null);

        if ($code && $type) {
            $notificationType = new PagSeguroNotificationType($type);
            $strType = $notificationType->getTypeFromValue();
            if ($strType == TRANSACTION) {
                $credentials = new PagSeguroAccountCredentials(PAGSEGURO_LOGIN, PAGSEGURO_AUTHCODE);
                $transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);

                $transactionCode = $transaction->getCode();
                $account = $transaction->getReference();
                $amount = $transaction->getGrossAmount();
                
                // only process paid/completed transactions
                if ($transaction->getStatus()->getTypeFromValue() != "PAID")
                    return;
                
                if (!$this->model->CheckVendorTransaction($transactionCOde, "pagseguro"))
                    return; // payment has already been handled
                
                $this->model->PaymentReceived($transactionCode, $amount, "nextpay", $account);
                $account_model = new Account_Model();
                $account_model->IncrementExp($account, $amount * $_CONFIG['exp_per_donate']);
            }
        }
    }

    public function nextpay_handler($data = array()) {
        global $_CONFIG;
        $request = $_REQUEST;

        $orderId = intval($request["order_id"]);
        $sellerProductId = $request["seller_product_id"];
        $productCount = intval($request["product_count"]);
        $orderHash = $request["hash"];
        $amount = $request["profit"];
        $currency = intval($request["volute"]);
        $customer = $request["character"];

        $hash = "$orderId$sellerProductId$productCount$amount$currency" . NEXTPAY_SECRETKEY;
        $hash = sha1($hash);

        if ($orderHash != $hash) {
            // error log?
            return;
        } else if ($amount > 0) {
            if (!$this->model->CheckVendorTransaction($orderId, "nextpay"))
                return; // payment has already been handled
            $this->model->PaymentReceived($orderId, $amount, "nextpay", $customer);
            $account_model = new Account_Model();
            $account_model->IncrementExp($customer, $amount * $_CONFIG['exp_per_donate']);
        }
    }

    public function robokassa_handler($data = array()) {
        global $_CONFIG;
        // Shp_account
        $request = $_REQUEST;

        $out_summ = $request['OutSum'];
        $inv_id = $request['inv_id'];
        $crc = $request['SignatureValue'];

        $my_crc = strtoupper(md5("$out_summ:$inv_id:" . ROBOKASSA_SECUREPASS2));

        if (strtoupper($crc) != $my_crc) {
            // error log?
            return;
        } else {
            if (!$this->model->CheckVendorTransaction($inv_id, "robokassa"))
                return; // payment has already been handled
            $uid = $request['Shp_account'];

            $this->model->PaymentReceived($inv_id, $out_summt, "robokassa", $uid);
            $account_model = new Account_Model();
            $account_model->IncrementExp($uid, $out_summ * $_CONFIG['exp_per_donate']);
        }
    }

    public function unitpay_handler($data = array()) {
        global $_CONFIG;
        $request = $_GET;
        $response = array();
        if (empty($request['method']) || empty($request['params']) || !is_array($request['params'])) {
            $response['error'] = array('message' => 'invalid params.');
        } else {
            $params = $request['params'];
            if (($request['method'] != 'pay' && $request['method'] != 'check') || !$this->model->CheckVendorTransaction($params['unitpayId'], "unitpay.ru")) {
                $response['error'] = array('message' => 'non-check or already processed.');
            } else {
                if ($request['method'] == 'check') {
                    $uid = $params['account'];
                    if (!empty($uid) && $uid > 0 && is_numeric($uid)) {
                        $response['result'] = array('message' => 'success');
                    } else {
                        $response['error'] = array('message' => 'invalid account.');
                    }
                } else {
                    $sent_sig = $params['signature'];
                    $method = $request['method'];
                    
                    ksort($params);
                    unset($params['sign']);
                    unset($params['signature']);
                    array_push($params, UNITPAY_SECRET_KEY);
                    array_unshift($params, $method);

                    $real_sig = hash('sha256', join('{up}', $params));

                    if ($sent_sig != $real_sig) {
                        $response['error'] = array('message' => 'invalid signature.');
                    } else {
                        // payment valid
                        $amount = $params['orderSum'];
                        $uid = $params['account'];
                        $this->model->PaymentReceived($params['unitpayId'], $amount, "unitpay.ru", $uid);
                        $account_model = new Account_Model();
                        $account_model->IncrementExp($uid, $amount * $_CONFIG['exp_per_donate']);

                        $response['result'] = array('message' => 'success');
                    }
                }
            }
        }
        echo json_encode($response);
    }

    public function pw_pingback($data = array()) {
        global $_CONFIG;
        require_once(ROOT_PATH . '/libs/paymentwall/paymentwall.php');
        Paymentwall_Base::setApiType(Paymentwall_Base::API_GOODS);
        Paymentwall_Base::setAppKey(PAYMENTWALL_PUBLIC_KEY);
        Paymentwall_Base::setSecretKey(PAYMENTWALL_SECRET_KEY);

        $pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
        if ($pingback->validate()) {
            if ($pingback->isDeliverable()) {
                // success
                $amount = intval(str_replace("donation_", "", $pingback->getProductId()));
                $uid = $pingback->getUserId();
                $this->model->PaymentReceived($pingback->getPingbackUniqueId(), $amount, "paymentwall", $uid);
                $account_model = new Account_Model();
                $account_model->IncrementExp($uid, $amount * $_CONFIG['exp_per_donate']);
            } else if ($pingback->isCancelable()) {
                // chargeback ?
            }
            echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
        } else {
            echo $pingback->getErrorSummary();
        }
    }
    
    public function pw_widget($data = array()) {
        if (!isset($_POST['amount']) || !is_numeric($_POST['amount'])) {
            $this->view->redirect("account/index");
            exit();
        } else if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }
        
    	global $_CONFIG;
        require_once(ROOT_PATH . '/libs/paymentwall/paymentwall.php');
        Paymentwall_Base::setApiType(Paymentwall_Base::API_GOODS);
        Paymentwall_Base::setAppKey(PAYMENTWALL_PUBLIC_KEY);
        Paymentwall_Base::setSecretKey(PAYMENTWALL_SECRET_KEY);
        
        $widget = new Paymentwall_Widget(
            Session::get("accountId"),
            "p1_2",
            array(
                new Paymentwall_Product(
                    "donation_" + $_POST['amount'],
                    $_POST['amount'],
                    DONATE_CURRENCY,
                    "Donation ",
                    Paymentwall_Product::TYPE_FIXED
                )
            )
        );
        
        echo $widget->getHtmlCode();
    }

    public function log($data = array()) {
        global $_CONFIG;
        if ($_CONFIG['features_enabled']['donate'] !== true)
            die();
        if (!Session::get("loggedIn") || Session::get("userAgent") != $_SERVER['HTTP_USER_AGENT']) {
            Session::set("loggedIn", false);
            Session::destroy();
            $this->view->redirect("account/login");
            exit();
        }

        $account_model = new Account_Model();
        $account_data = $account_model->getAccountData(Session::get("accountId"));

        $this->view->pw_widget_url = str_replace("[USER_ID]", Session::get("accountId"), PAYMENTWALL_WIDGET_URL);
        $this->view->servers = $_CONFIG['servers'];
        $this->view->active_server = Session::get("serverId");
        $this->view->email = $account_data['email'];
        $this->view->balance = number_format($account_data['balance'], 2, ".", "");
        $this->view->uid = Session::get("accountId");

        $server_model = new Server_Model(Session::get("serverId"));
        $this->view->game_accounts = $server_model->getAccounts(Session::get("accountId"), true);
        $this->view->max_accounts = $_CONFIG['servers'][Session::get("serverId")]['max_accounts'];

        $this->view->account_level = get_account_level($account_data['account_exp']);
        $this->view->account_exp = $account_data['account_exp'];
        $this->view->exp_percent = get_exp_percent($account_data['account_exp']);
        $this->view->bonus_percent = $_CONFIG['account_level_discount'][$this->view->account_level];
        $this->view->coin_ratio = (($this->view->bonus_percent / 100) * COINS_PER_DOLLAR) + COINS_PER_DOLLAR;
        if (!is_int($this->view->coin_ratio))
            $this->view->coin_ratio = number_format($this->view->coin_ratio, 2, ".", "");

        $this->view->transaction_log = $this->model->GetTransactions(Session::get("accountId"));
        $this->view->features = $_CONFIG['features_enabled'];
        $this->view->render("donate/log");
        
        $this->view->title = _s("TITLE_TRANSACTIONLOG");
    }

}
