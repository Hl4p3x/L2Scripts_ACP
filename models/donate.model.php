<?php
if (!defined('ctx')) die();

class Donate_Model extends Model {

    public $error = "";

    public function __construct() {
        parent::__construct();
    }

    public function PaymentReceived($txn_id, $amount, $vendor, $uid) {
        $this->LogVendorTransaction($txn_id, $amount, $vendor, $uid);
        $this->LogTransaction(_s("PAYMENT_SUCCESS_LOG"), $amount, $uid);
	$this->AddBalance($uid, $amount);
    }
    
    public function CheckVendorTransaction($txn_id, $vendor) {
        $data = array(
            'txn_id' => $txn_id,
            'vendor' => $vendor
        );
        $result = $this->db->select("SELECT count(*) as txn_count FROM vendor_txn_log WHERE txn_id = :txn_id AND vendor = :vendor", $data);
        if (is_array($result) && $result[0]['txn_count'] == 0) {
            return true;
        }
        
        return false;
    }
    
    public function LogVendorTransaction($txn_id, $amount, $vendor, $ma_id) {
        $data = array(
            'txn_id' => $txn_id,
            'vendor' => $vendor,
            'amount' => $amount,
            'ma_id' => $ma_id,
            'date' => date('Y-m-d H:i:s')
        );
        $this->db->insert("vendor_txn_log", $data);
    }

    public function LogTransaction($action, $amount, $uid, $character="", $server_id=-1) {
        $result = $this->db->select("SELECT balance FROM `master_account` WHERE `ma_id` = :ma_id", array("ma_id" => $uid));
        if (is_array($result) && count($result) > 0) {
            $balance = $result[0]['balance'];
            $new_balance = $balance + $amount;
            $log_data = array(
                'action' => $action,
                'date' => time(),
                'character' => $character,
                'amount' => $amount,
                'balance' => $new_balance,
                'server_id' => $server_id,
                'ma_id' => $uid
            );
            $this->db->insert("balance_log", $log_data);
        }
    }

    public function GetTransactions($uid) {
        global $_CONFIG;
        $return_data = array();
        $result = $this->db->select("SELECT * FROM balance_log WHERE ma_id = :ma_id ORDER BY date DESC", array('ma_id' => $uid));
        foreach ($result as $row) {
            $return_data[] = array(
                'action' => $row['action'],
                'date' => date("j/m/Y H:i", $row['date']),
                'character' => $row['character'],
                'amount' => $row['amount'],
                'balance' => $row['balance'],
                'server' => isset($_CONFIG['servers'][$row['server_id']]) ? $_CONFIG['servers'][$row['server_id']]['name'] : ""
            );
        }

        return $return_data;
    }

    public function DeductBalance($uid, $amount) {
		$sth = $this->db->prepare("UPDATE `master_account` SET `balance` = `balance` - :amount WHERE ma_id = :ma_id");
		$sth->bindValue(":amount", $amount);
		$sth->bindValue(":ma_id", $uid);
		$sth->execute();
    }

    public function AddBalance($uid, $amount) {
        $sth = $this->db->prepare("UPDATE `master_account` SET `balance` = `balance` + :amount WHERE ma_id = :ma_id");
		$sth->bindValue(":amount", $amount);
		$sth->bindValue(":ma_id", $uid);
		$sth->execute();
    }
}