<?php
if (!defined('ctx')) die();

class Server_Model extends Model {

    private $config = false;

    public function __construct($server_id) {
        //parent::__construct(); // parent db not required

        global $_CONFIG;
        $this->config = $_CONFIG['servers'][$server_id];

        $this->login_db = new Database("mysql", $this->config["ls_mysql_host"], $this->config["ls_mysql_db"], $this->config["ls_mysql_user"], $this->config["ls_mysql_pass"]);
        $this->game_db = new Database("mysql", $this->config["gs_mysql_host"], $this->config["gs_mysql_db"], $this->config["gs_mysql_user"], $this->config["gs_mysql_pass"]);
    }

    public function MACheck($ma_id, $login) {
        $data = array(
            'ma_id' => $ma_id,
            'login' => $login
        );
        $result = $this->login_db->select("SELECT count(*) as `count` FROM accounts WHERE ma_id = :ma_id AND login = :login", $data);
        return ($result[0]['count'] != 0);
    }

    public function getAccounts($ma_id, $include_chars) {
        $data = array(
            'ma_id' => $ma_id
        );
        $return_data = array();

        $result = $this->login_db->select("SELECT login FROM accounts WHERE ma_id = :ma_id", $data);
        if ($result === FALSE || !is_array($result) || count($result) == 0)
            return $return_data;

        if ($include_chars) {
            foreach ($result as $row) {
                $return_data[$row['login']] = $this->getCharacters($row['login']);
            }
        } else {
            foreach ($result as $row) {
                $return_data[] = $row['login'];
            }
        }

        return $return_data;
    }

    public function getCharacters($account) {
        $data = array(
            'account_name' => $account
        );
        $return_data = array();

        $result = $this->game_db->select("SELECT characters.charId,char_name,characters.level,base_class as classid,onlinetime,pvpkills,pkkills,reputation as karma,online,(SELECT MAX(`name`) FROM `clan_subpledges` WHERE clan_subpledges.clan_id=clan_data.clan_id) AS `clan_name` FROM characters LEFT JOIN clan_data ON characters.clanid > 0 AND characters.clanid = clan_data.clan_id LEFT JOIN character_subclasses ON character_subclasses.charId = characters.charId AND class_id = classid WHERE account_name = :account_name", $data);
        return $result;
    }

    public function getStats() {
        global $_CONFIG;
        $return_data = array();

        // exclusions
        $ex = "";
        if ($_CONFIG['stat_exclusions'] != '') {
            $ex = " WHERE charId NOT IN (".$_CONFIG['stat_exclusions'].")";
        }

        $result = $this->game_db->select("SELECT char_name,characters.level,classid,onlinetime,pvpkills,pkkills,reputation as karma,(SELECT MAX(`name`) FROM `clan_subpledges` WHERE clan_subpledges.clan_id=clan_data.clan_id) AS `clan_name` FROM characters LEFT JOIN clan_data ON characters.clanId > 0 AND characters.clanId = clan_data.clan_id".$ex." ORDER BY pkkills DESC LIMIT 0,20");
        if (count($result) < 20) {
            for ($i=count($result); $i<20; $i++) {
            	$result[$i] = array('char_name' => '', 'level' => '', 'classid' => '', 'onlinetime' => '', 'pvpkills' => '', 'pkkills' => '', 'karma' => '', 'clan_name' => '');
            }
        }
        $return_data['pk'] = $result;

        $result = $this->game_db->select("SELECT char_name,characters.level,classid,onlinetime,pvpkills,pkkills,reputation as karma,(SELECT MAX(`name`) FROM `clan_subpledges` WHERE clan_subpledges.clan_id=clan_data.clan_id) AS `clan_name` FROM characters LEFT JOIN clan_data ON characters.clanId > 0 AND characters.clanId = clan_data.clan_id".$ex." ORDER BY pvpkills DESC LIMIT 0,20");
        if (count($result) < 20) {
            for ($i=count($result); $i<20; $i++) {
            	$result[$i] = array('char_name' => '', 'level' => '', 'classid' => '', 'onlinetime' => '', 'pvpkills' => '', 'pkkills' => '', 'karma' => '', 'clan_name' => '');
            }
        }
        $return_data['pvp'] = $result;

        $result = $this->game_db->select("SELECT char_name,characters.level,classid,onlinetime,pvpkills,pkkills,reputation as karma,(SELECT MAX(`name`) FROM `clan_subpledges` WHERE clan_subpledges.clan_id=clan_data.clan_id) AS `clan_name` FROM characters LEFT JOIN clan_data ON characters.clanId > 0 AND characters.clanId = clan_data.clan_id".$ex." ORDER BY onlinetime DESC LIMIT 0,20");
        if (count($result) < 20) {
            for ($i=count($result); $i<20; $i++) {
            	$result[$i] = array('char_name' => '', 'level' => '', 'classid' => '', 'onlinetime' => '', 'pvpkills' => '', 'pkkills' => '', 'karma' => '', 'clan_name' => '');
            }
        }
        $return_data['onlinetime'] = $result;

        $result = $this->game_db->select("SELECT char_name,characters.level,classid,onlinetime,pvpkills,pkkills,reputation as karma,(SELECT MAX(`name`) FROM `clan_subpledges` WHERE clan_subpledges.clan_id=clan_data.clan_id) AS `clan_name` FROM characters LEFT JOIN clan_data ON characters.clanId > 0 AND characters.clanId = clan_data.clan_id".$ex." ORDER BY level DESC LIMIT 0,20");
        if (count($result) < 20) {
            for ($i=count($result); $i<20; $i++) {
            	$result[$i] = array('char_name' => '', 'level' => '', 'classid' => '', 'onlinetime' => '', 'pvpkills' => '', 'pkkills' => '', 'karma' => '', 'clan_name' => '');
            }
        }
        $return_data['level'] = $result;

        return $return_data;
    }

    public function createAccount($uid, $account, $password) {
        $result = $this->login_db->select("SELECT count(*) as `count` FROM accounts WHERE login = :login", array('login' => $account));
        if ($result[0]['count'] == 0) {
            $data = array(
                'login' => $account,
                'password' => $password,
                'ma_id' => $uid
            );
            $this->login_db->insert("accounts", $data);
            return true;
        } else {
            $this->error = _s("ACCOUNT_TAKEN");
            return false;
        }
    }

    public function ChangePasswordGA($login, $old_password, $new_password) {
        $result = FALSE;

        $data = array(
            'login' => $login
        );
        $result = $this->login_db->select("SELECT password FROM accounts WHERE login = :login", $data);

        if ($result !== FALSE && is_array($result) && count($result) > 0) {
            if ($old_password == $result[0]['password']) {
                $this->login_db->update("accounts", array('password' => $new_password), "login = :login", $data);
                return true;
            } else {
                $this->error = _s("INVALID_OLD_PASS");
                return false;
            }
        } else {
            $this->error = _s("CANT_FIND_ACCOUNT");
            return false;
        }
    }

    public function SetPasswordGA($login, $new_password) {
        $result = FALSE;

        $data = array(
            'login' => $login
        );
        $result = $this->login_db->select("SELECT count(*) as `count` FROM accounts WHERE login = :login", $data);

        if ($result[0]['count'] > 0) {
            $this->login_db->update("accounts", array('password' => $new_password), "login = :login", $data);
            return true;
        } else {
            $this->error = _s("CANT_FIND_ACCOUNT");
            return false;
        }
    }

    public function CheckChar($char_name, $uid) {
        $result = $this->game_db->select("SELECT account_name FROM characters WHERE char_name = :char_name", array('char_name' => $char_name));
        if (is_array($result) && count($result) > 0) {
            $login = $result[0]['account_name'];
            $result = $this->login_db->select("SELECT ma_id FROM accounts WHERE login = :login", array('login' => $login));
            if (is_array($result) && count($result) > 0) {
                if ($result[0]['ma_id'] == $uid) {
                    return true;
                }
            }
        }

        return false;
    }

    public function GetInventory($char_name) {
        $return_data = array(
            'paperdoll' => array(),
            'inventory' => array()
        );
        $result = $this->game_db->select("SELECT charId as obj_Id FROM characters WHERE char_name = :char_name", array('char_name' => $char_name));
        if (is_array($result) && count($result) > 0) {
            $char_id = $result[0]['obj_Id'];
            $return_data['paperdoll'] = $this->game_db->select("SELECT * FROM items WHERE owner_id = :char_id AND loc = 'PAPERDOLL'", array('char_id' => $char_id));
            $return_data['inventory'] = $this->game_db->select("SELECT * FROM items WHERE owner_id = :char_id AND loc = 'INVENTORY'", array('char_id' => $char_id));
        }

        return $return_data;
    }

    public function GetAvatarInfo($char_name) {
        $return_data = array(
            0 => '', // class
            1 => '', // sex
        );

        $result = $this->game_db->select("SELECT base_class as class_id, sex from characters WHERE char_name = :char_name", array('char_name' => $char_name));
        if (is_array($result) && count($result) > 0) {
            $return_data[0] = $result[0]['class_id'];
            $return_data[1] = $result[0]['sex'];
        }

        return $return_data;
    }

    public function GetItemData($uid, $item_id) {
        $result = $this->game_db->select("SELECT item_id,`count`,enchant_level,owner_id,account_name,online FROM items INNER JOIN characters on characters.charId = items.owner_id WHERE object_id = :object_id", array('object_id' => $item_id));
        if (is_array($result) && count($result) > 0) {
            $return_data = $result[0];
            $result = $this->login_db->select("SELECT ma_id FROM accounts WHERE login = :login", array('login' => $return_data['account_name']));
            if (is_array($result) && count($result) > 0) {
                if ($result[0]['ma_id'] == $uid) {
                    return $return_data;
                }
            }
        }

        $this->error = _s("CANT_FIND_ITEM");
        return false;
    }

    public function DeleteItem($item_id, $count) {
        try {
            $result = $this->game_db->select("SELECT count FROM items WHERE object_id = :object_id", array('object_id' => $item_id));
            if (is_array($result) && count($result) > 0) {
                if ($count == $result[0]['count']) {
                    $this->game_db->delete("items", "object_id = :object_id", array('object_id' => $item_id));
                    return true;
                } else if ($count < $result[0]['count']) {
                    $this->game_db->update("items", array('count' => ($result[0]['count'] - $count)), "object_id = :object_id", array('object_id' => $item_id));
                    return true;
                } else {
                    return false; // trying to delete item with count > db count
                }
            }
        } catch (PDOException $ex) {
            return false;
        }
        return false;
    }

    public function AddItemDelayed($char_id, $item_id, $count, $enchant) {
        $item_data = array(
            'owner_id' => $char_id,
            'item_id' => $item_id,
            'count' => $count,
            'enchant_level' => $enchant,
            'attribute' => 0,
            'attribute_level' => 0,
            'flags' => 0,
            'payment_status' => 0
        );

        $this->game_db->insert("items_delayed", $item_data);
    }
	
	public function AddItem($char_id, $item_id, $count, $enchant) {
		$result = $this->game_db->select("SELECT MAX(object_id+1) as newObj FROM items");
			if (is_array($result) && count($result) > 0) {
				$return_data = $result[0];
				$item_data = array(
				'owner_id' => $char_id,
				'object_id' => $return_data['newObj'],
				'item_id' => $item_id,
				'count' => $count,
				'enchant_level' => $enchant,
				'loc' => 'INVENTORY',
				'loc_data' => '0'
			);
        }
        
        $this->game_db->insert("items", $item_data);
    }

    public function GetCharacter($char_name) {
        $result = $this->game_db->select("SELECT * FROM characters WHERE char_name = :char_name", array('char_name' => $char_name));
        if (is_array($result) && count($result) > 0) {
            $result[0]['obj_Id'] = $result[0]['charId'];
            return $result[0];
        }
        return false;
    }

    public function ChangeName($char_name, $new_char_name) {
        $this->game_db->update("characters", array('char_name' => $new_char_name), "char_name = :old_char_name", array('old_char_name' => $char_name));
    }

    public function ChangeGender($char_name, $gender) {
        $this->game_db->update("characters", array('sex' => $gender), "char_name = :char_name", array('char_name' => $char_name));
    }

    public function GetCharacterById($char_id) {
        $result = $this->game_db->select("SELECT * FROM characters WHERE charId = :char_id", array('char_id' => $char_id));
        if (is_array($result) && count($result) > 0) {
            $result[0]['obj_Id'] = $result[0]['charId'];
            return $result[0];
        }
        return false;
    }

    public function CheckGameAccount($account) {
        $result = $this->login_db->select("SELECT count(*) as `count` FROM accounts WHERE login = :login", array('login' => $account));
        if ($result[0]['count'] == 0) {
            return true;
        }
        return false;
    }
	
	public function CheckDelayedItems($char_id) {
        $result = $this->game_db->select("SELECT * FROM items_delayed WHERE owner_id = :char_id", array('char_id' => $char_id));
        if ($result[0]['count'] == 0) {
            return true;
        }
        return false;
    }
}