<?php
if (!defined('ctx')) die();

class Auction_Model extends Model {

    public $error = "";
    private $item_grades = array(
        'none' => 0,
        'd' => 1,
        'c' => 2,
        'b' => 3,
        'a' => 4,
        's' => 5
    );

    public function __construct() {
        parent::__construct();
    }

    public function GetItems($filter) {
        $data = array(
			'server_id' => Session::get("serverId")
        );

        $sql = "SELECT * FROM auction WHERE server_id = :server_id AND id > 0";
        $filter_old = true;

        if ($filter['enchant'] != "") {
            $sql .= " AND item_enchant >= :enchant";
            $data['enchant'] = $filter['enchant'];
        }

        if ($filter['grade'] != "all") {
            $sql .= " AND item_grade = :grade";
            $data['grade'] = $this->item_grades[$filter['grade']];
        }

        if ($filter['category'] != "all") {
            $sql .= " AND item_category = :category";
            $data['category'] = $filter['category'];
        }

        if (isset($filter['created']) && $filter['created'] === true) {
            $sql .= " AND owner_id = :owner_id";
            $data['owner_id'] = $filter['owner_id'];
        }

        if (isset($filter['unsold']) && $filter['unsold'] === true) {
            $filter_old = false;
            $sql .= " AND owner_id = :owner_id AND end_date < :nowtime AND bidder_id = 0";
            $sql .= " AND received_item = 0";
            $data['owner_id'] = $filter['owner_id'];
            $data['nowtime'] = time();
        }

        if (isset($filter['bought']) && $filter['bought'] === true) {
            $filter_old = false;
            $sql .= " AND bidder_id = :bidder_id AND end_date < :nowtime";
            $sql .= " AND received_item = 0";
            $data['bidder_id'] = $filter['bidder_id'];
            $data['nowtime'] = time();
        }

        if ($filter_old) {
            $sql .= " AND end_date > :nowtime";
            $data['nowtime'] = time();
        }

        $start = (intval($filter['page']) - 1) * 10;
        $sql .= " LIMIT " . $start . ",10";
        $result = $this->db->select($sql, $data);
        return $result;
    }

    public function GetItemCount($filter) {
        $data = array(
			'server_id' => Session::get("serverId")
        );

        $sql = "SELECT count(*) as `count` FROM auction WHERE server_id = :server_id AND id > 0";
        $filter_old = true;

        if ($filter['enchant'] != "") {
            $sql .= " AND item_enchant >= :enchant";
            $data['enchant'] = $filter['enchant'];
        }

        if ($filter['grade'] != "all") {
            $sql .= " AND item_grade = :grade";
            $data['grade'] = $this->item_grades[$filter['grade']];
        }

        if ($filter['category'] != "all") {
            $sql .= " AND item_category = :category";
            $data['category'] = $filter['category'];
        }

        if (isset($filter['created']) && $filter['created'] === true) {
            $sql .= " AND owner_id = :owner_id";
            $data['owner_id'] = $filter['owner_id'];
        }

        if (isset($filter['unsold']) && $filter['unsold'] === true) {
            $filter_old = false;
            $sql .= " AND owner_id = :owner_id AND end_date < :nowtime AND bidder_id = 0";
            $sql .= " AND received_item = 0";
            $data['owner_id'] = $filter['owner_id'];
            $data['nowtime'] = time();
        }

        if (isset($filter['bought']) && $filter['bought'] === true) {
            $filter_old = false;
            $sql .= " AND bidder_id = :bidder_id AND end_date < :nowtime";
            $sql .= " AND received_item = 0";
            $data['bidder_id'] = $filter['bidder_id'];
            $data['nowtime'] = time();
        }

        if ($filter_old) {
            $sql .= " AND end_date > :nowtime";
            $data['nowtime'] = time();
        }

        $result = $this->db->select($sql, $data);
        return $result[0]['count'];
    }

    public function CreateAuction($data) {
        $this->db->insert("auction", $data);
    }

    public function GetAuction($auction_id) {
        $result = $this->db->select("SELECT * FROM auction WHERE id = :auction_id", array('auction_id' => $auction_id));
        if (is_array($result) && count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function Bid($auction_id, $uid, $amount) {
        $sth = $this->db->prepare("UPDATE `auction` SET `current_bid` = `current_bid` + `step`, `bidder_id` = :bidder_id WHERE id = :auction_id");
		$sth->bindValue(":bidder_id", $uid);
		$sth->bindValue(":auction_id", $auction_id);
		$sth->execute();

		$data = array(
		    'auction_id' => $auction_id,
		    'bidder_id' => $uid,
		    'bid_amount' => $amount,
		    'bid_time' => time()
		);
		$this->db->insert("auction_log", $data);
    }

    public function BuyNow($auction_id, $uid, $amount) {
        $sth = $this->db->prepare("UPDATE `auction` SET `current_bid` = `buynow_price`, `bidder_id` = :bidder_id, `end_date` = 0 WHERE id = :auction_id");
		$sth->bindValue(":bidder_id", $uid);
		$sth->bindValue(":auction_id", $auction_id);
		$sth->execute();

		$data = array(
		    'auction_id' => $auction_id,
		    'bidder_id' => $uid,
		    'bid_amount' => $amount,
		    'bid_time' => time()
		);
		$this->db->insert("auction_log", $data);
    }

    public function EndAuction($lot_id) {
        $data = array(
            'end_date' => 0,
            'bidder_id' => 0
        );
        $this->db->update("auction", $data, "id = :lot_id", array('lot_id' => $lot_id));
    }

    public function ReceivedItem($lot_id) {
        $this->db->update("auction", array('received_item' => 1), "id = :lot_id", array('lot_id' => $lot_id));
    }

    public function GetPendingMoney($uid, $update) {
        $data = array(
            'uid' => $uid,
            'nowtime' => time()
        );
        $result = $this->db->select("SELECT SUM(current_bid) as pending FROM auction WHERE owner_id = :uid AND bidder_id > 0 AND end_date < :nowtime AND received_money = 0", $data);

        if ($update === true) {
            $this->db->update("auction", array("received_money" => 1), "owner_id = :uid AND bidder_id > 0 AND end_date < :nowtime AND received_money = 0", $data);
        }

        return $result[0]['pending'];
    }

    public function GetWidgetItems() {
        $data = array(
			'server_id' => Session::get("serverId")
        );

        $sql = "SELECT * FROM auction WHERE server_id = :server_id AND id > 0 AND end_date > :nowtime ORDER BY end_date ASC LIMIT 3";
        $data['nowtime'] = time();

        $result = $this->db->select($sql, $data);
        return $result;
    }
}