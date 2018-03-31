<?php
if (!defined('ctx')) die();

class Ticket_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function newTicket($ticket_data) {
        $this->db->insert("ticket", $ticket_data);
    }

    public function newComment($ticket_data, $admin) {
        $this->db->insert("ticket_comment", $ticket_data);
        $this->db->update("ticket", array('status' => $admin == 0 ? 1 : 2), "id = :ticket_id", array('ticket_id' => $ticket_data['ticket_id']));
    }

    public function getTicket($ticket_id, $include_comments) {
        $result = $this->db->select("SELECT * FROM ticket WHERE id = :ticket_id", array('ticket_id' => $ticket_id));
        if (is_array($result) && count($result) > 0) {
            $return_data = $result[0];
            if ($include_comments) {
                $result = $this->db->select("SELECT * FROM ticket_comment WHERE ticket_id = :ticket_id ORDER BY post_date ASC", array('ticket_id' => $ticket_id));
                if (is_array($result) && count($result) > 0) {
                    $return_data['comments'] = $result;
                } else {
                    //
                }
            }
            return $return_data;
        }

        return false;
    }

    public function getTickets($uid) {
        $data = array(
            'ma_id' => $uid,
        );
        $result = $this->db->select("SELECT * FROM ticket WHERE ma_id = :ma_id ORDER BY create_date DESC", $data);
        if (is_array($result) && count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getAllTickets($status = -1) {
        $result = false;
        if ($status == -1) {
            $result = $this->db->select("SELECT * FROM ticket WHERE status > 0 ORDER BY create_date DESC");
        } else {
            $result = $this->db->select("SELECT * FROM ticket WHERE status = :status ORDER BY create_date DESC", array('status' => $status));
        }
        if (is_array($result) && count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function closeTicket($ticket_id) {
        $this->db->update("ticket", array('status' => 0), "id = :ticket_id", array('ticket_id' => $ticket_id));
    }
}