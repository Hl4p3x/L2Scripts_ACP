<?php
if (!defined('ctx')) die();

class Vote_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function GetLastVotes($ma_id, $ip, $site_id = -1) {
        global $_CONFIG;

        $return_data = array();
        if ($site_id != -1) {
            $return_data[$site_id] = array(
                'vote_id' => -1,
                'vote_time' => 0,
                'rewarded' => false
            );
            $query_data = array(
                'ma_id' => $ma_id,
                'ip' => $ip,
                'site_id' => $site_id
            );

            $result = $this->db->select("SELECT * FROM vote_log WHERE site_id = :site_id AND (ma_id = :ma_id OR ip = :ip) ORDER BY vote_time DESC LIMIT 1", $query_data);
            if (is_array($result) && count($result) > 0) {
                $return_data[$site_id] = array(
                    'vote_id' => $result[0]['vote_id'],
                    'vote_time' => $result[0]['vote_time'],
                    'rewarded' => $result[0]['rewarded'] == 0 ? false : true
                );
            }
        } else {
            $query_data = array(
                'ma_id' => $ma_id,
                'ip' => $ip
            );
            foreach ($_CONFIG['vote_sites'] as $site => $data) {
                $return_data[$site] = array(
                    'vote_id' => -1,
                    'vote_time' => 0,
                    'rewarded' => false
                );
                $query_data['site_id'] = $site;
                $result = $this->db->select("SELECT * FROM vote_log WHERE site_id = :site_id AND (ma_id = :ma_id OR ip = :ip) ORDER BY vote_time DESC LIMIT 1", $query_data);
                if (is_array($result) && count($result) > 0) {
                    $return_data[$site] = array(
                        'vote_id' => $result[0]['vote_id'],
                        'vote_time' => $result[0]['vote_time'],
                        'rewarded' => $result[0]['rewarded'] == 0 ? false : true
                    );
                }
            }
        }

        return $return_data;
    }

    public function SetLastVote($ma_id, $ip, $site_id, $time) {
        $data = array(
            'ma_id' => $ma_id,
            'ip' => $ip,
            'site_id' => $site_id,
            'vote_time' => $time,
            'rewarded' => 0,
        );
        return $this->db->insert("vote_log", $data);
    }

    public function SetVoteRewarded($vote_id) {
        $this->db->update("vote_log", array('rewarded' => 1), "vote_id = :vote_id", array('vote_id' => $vote_id));
    }
}