<?php

if (!defined('ctx')) die();

class TopZoneAPI {

    public function process($data) {
        $return_data = array(
            'voted' => false,
            'vote_time' => ''
        );

        if ($data->ok == true && $data->result->isVoted == true) {
            $return_data['voted'] = true;
            $return_data['vote_time'] = $data->result->voteTime;
        }

        return $return_data;
    }

}
