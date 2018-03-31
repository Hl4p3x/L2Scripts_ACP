<?php

if (!defined('ctx')) die();

class HopZoneAPI {

    public function process($data) {
        $return_data = array(
            'voted' => false,
            'vote_time' => ''
        );

        if ($data->voted == true) {
            // correct hopzone's dumbass timestamp
            $now = time();
            $hz_dt = new DateTime($data->hopzoneServerTime);
            $diff_hours = round(($now - $hz_dt->getTimestamp())/3600);

            $time_offset = $diff_hours*3600;

            $vote_dt = new DateTime($data->voteTime);

            $return_data['voted'] = true;
            $return_data['vote_time'] = $vote_dt->getTimestamp() + $time_offset; // correct timezone offset
        }

        return $return_data;
    }

}