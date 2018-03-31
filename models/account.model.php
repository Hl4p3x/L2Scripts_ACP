<?php
if (!defined('ctx')) die();

class Account_Model extends Model {

    public $error = "";

    public function __construct() {
        parent::__construct();
    }

    public function CheckAccount($account) {
        $data = array(
            'master_name' => $account
        );
        $result = $this->db->select("SELECT count(*) as m_count FROM master_account WHERE master_name = :master_name", $data);

        if ($result !== FALSE && $result[0]['m_count'] > 0)
            return true; // account exists

        return false; // account doesn't exist
    }

    public function CheckEmail($email) {
        $data = array(
            'email' => $email
        );
        $result = $this->db->select("SELECT count(*) as m_count FROM master_account WHERE email = :email", $data);

        if ($result !== FALSE && $result[0]['m_count'] > 0)
            return true; // email exists

        return false; // email doesn't exist
    }

    public function CreateAccount($data) {
        return $this->db->insert("master_account", $data);
    }

    public function AttemptLogin($login, $is_email) {
        $result = FALSE;
        if ($is_email) {
            $data = array(
                'email' => $login['master_name']
            );
            $result = $this->db->select("SELECT ma_id,master_password,suspended FROM master_account WHERE email = :email", $data);
        } else {
            $data = array(
                'master_name' => $login['master_name']
            );
            $result = $this->db->select("SELECT ma_id,master_password,suspended FROM master_account WHERE master_name = :master_name", $data);
        }

        if ($result !== FALSE && is_array($result) && count($result) > 0) {
            if ($result[0]['suspended'] == 0) {
                /*if (password_verify($login['master_password'], $result[0]['master_password'])) {*/
                if (hash(HASH_ALGO, $login['master_password']) == $result[0]['master_password']) {
                    $log = Array(
                        'ma_id' => $result[0]['ma_id'],
                        'time' => time (),
                        'ip' => $_SERVER['REMOTE_ADDR']
                    );
                    $this->db->insert("ip_log", $log);
                    return $result[0]['ma_id'];
                } else {
                    $this->error = _s("INVALID_USER_PASS");
                    return false;
                }
            } else {
                $this->error = _s("ACCOUNT_BANNED");
                return false;
            }
        } else {
            $this->error = _s("INVALID_USER_PASS");
            return false;
        }
    }

    public function getAccountData($uid) {
        $data = array(
            'ma_id' => $uid
        );
        $result = $this->db->select("SELECT * FROM master_account WHERE ma_id = :ma_id", $data);
        if ($result !== false)
            return $result[0];
        return false;
    }

    public function getLastLogin($uid){
        $data = Array(
            'ma_id' => $uid
        );
        $result = $this->db->select("SELECT * FROM ip_log WHERE ma_id = :ma_id ORDER BY time DESC LIMIT 0, 2", $data);
        return $result[count($result) - 1];
    }

    function resetPassword($pass, $email){
        $dat = Array(
            'master_password' => hash(HASH_ALGO, $pass) // password_hash($pass, PASSWORD_DEFAULT)
        );
        $dat2 = Array(
            'email' => $email
        );
        $this->db->update("master_account", $dat, "email = :email", $dat2);
    }

    public function ChangePasswordMA($uid, $old_password, $new_password) {
        $result = FALSE;

        $data = array(
            'ma_id' => $uid
        );
        $result = $this->db->select("SELECT master_password FROM master_account WHERE ma_id = :ma_id", $data);

        if ($result !== FALSE && is_array($result) && count($result) > 0) {
            if (hash(HASH_ALGO, $old_password) == $result[0]['master_password']) {
            //if (password_verify($old_password, $result[0]['master_password'])) {
                $this->db->update("master_account", array('master_password' => $new_password), "ma_id = :ma_id", $data);
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

    public function GetSpinStats($uid) {
        $return_data = array(
            'global_spins' => 0,
            'user_spins' => 0,
        );
        $result = $this->db->select("SELECT SUM(spin_count) as `spins` FROM master_account");
        if (is_array($result)) {
            $return_data['global_spins'] = $result[0]['spins'];
        }

        $result = $this->db->select("SELECT spin_count as `spins` FROM master_account WHERE ma_id = :ma_id", array('ma_id' => $uid));
        if (is_array($result)) {
            $return_data['user_spins'] = $result[0]['spins'];
        }

        return $return_data;
    }

    public function IncrementSpinCount($uid) {
        $stmt = $this->db->prepare("UPDATE master_account SET spin_count = spin_count + 1 WHERE ma_id = :ma_id");
        $stmt->bindValue(":ma_id", $uid);
        $stmt->execute();
    }

    public function IncrementExp($uid, $exp) {
        $stmt = $this->db->prepare("UPDATE master_account SET account_exp = account_exp + :exp WHERE ma_id = :ma_id");
        $stmt->bindValue(":ma_id", $uid);
        $stmt->bindValue(":exp", $exp);
        $stmt->execute();
    }
    
    public function InsertTempAccount($login, $password) {
        $result = false;
        do
        {
            $id = mt_rand(1000000,9999999);
            $result = $this->db->select("SELECT count(*) as temp_count FROM temp_register WHERE id = :id", array('id' => $id));
        } while (!is_array($result) || count($result) == 0 || $result[0]['temp_count'] > 0);
        
        $data = array(
            'id' => $id,
            'login' => $login,
            'password' => $password,
            'time' => time()
        );
        $this->db->insert("temp_register", $data);
        
        return $id;
    }
    
    public function GetTempAccount($id) {
        $result = $this->db->select("SELECT * FROM temp_register WHERE id = :id", array('id' => $id));
        
        // cleaup data
        $this->db->delete("temp_register", "id = :id OR `time` < :time", array('id' => $id, 'time' => (time()-600)));
        
        if (is_array($result) && count($result) > 0) {
            return $result[0];
        }
    }
    
        public function GetEmailFromLogin($login) {
        $result = $this->db->select("SELECT email FROM master_account WHERE master_name = :login", array('login' => $login));
        return $result[0]['email'];
    }

}