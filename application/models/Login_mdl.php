<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_mdl extends My_mdl
{
    public $max_attempt = ''; // Maximum login attempt.
    public $max_attempt_round = ''; // Deactivate the user after how many rounds of incurrect login attempt over.
    public $lock_time = ''; // 4 - 8 Minuts
    public $expire = ''; // Login session exipration in minuts
    public $remember_expire = ''; // Remember me cookie exipration in months

    function __construct()
    {
        parent::__construct();
        $this->max_attempt = 2; // Maximum login attempt.
        $this->max_attempt_round = 2; // Maximum login attempt.
        $this->lock_time = rand(4, 8); // 4 - 8 Minuts
        $this->expire = 60; // Session exipration in Minuts
        $this->remember_expire = 3; // 3 Months. Remember me cookie exipration in months

        $this->loadTable('users', 'usr');
        $this->nameField = 'usr_username';
    }


    /**
     * Checking is the user logged in. If yes, returns his id
     * 
     * An employee is considered as logged in iff:
     * 
     * 1. There should be a value (that must match with the value of user session) for Fld: session.ssn_name 
     *    curresponding to the emply_id.
     * 2. The client id of the emply_id should match with the value of client session.
     * 3. The session should not be expired (ie: ssn_expire > current datetime).
     * 4. Employee should be active.
     * 
     * @return boolean
     */
    function is_logged_in($remember_me)
    {
        $user_session = $this->session->user;
        $this->db->from("session,$this->table");

        $this->db->where('ssn_name', $user_session);

        $this->db->where('ssn_expire > ', get_sql_date_time());
        $this->db->where('usr_id = ssn_fk_users');
        $this->db->where('usr_status', ACTIVE);

        $row = $this->db->get()->row_array();
        if ($row) {
            $ssn_id = $row['ssn_id'];

            // Extending the session expiration.
            $this->db->update('session', array('ssn_expire' => $this->cal_expiation(get_sql_date_time())), "ssn_id = $ssn_id");

            // Returning the user's id.
            return $row['usr_id'];
        } else if ($cookie = get_cookie(encript_cookie_name($remember_me), TRUE)) {
            $this->db->from("remember_me,$this->table");
            $this->db->select("usr_id,usr_username");
            $this->db->where('rmbr_cookie', $cookie);
            $this->db->where('usr_id = rmbr_fk_users');
            $this->db->where('usr_status', ACTIVE);
            $row = $this->db->get()->row_array();

            if ($row) {
                make_logged_in($row['usr_id'], $row['usr_username'], TRUE, $cookie);

                // Returning the user's id.
                return $row['usr_id'];
            }
        }
        return FALSE;
    }

    function save_db_session($usr_id, $key)
    {
        $where = array('ssn_fk_users' => $usr_id);
        $row = $this->get_row($where, '', 'session');

        // Removing if is there any session data related to the current employee in Tbl: session
        if ($row)
            $this->db->delete('session', $where);

        $start = get_sql_date_time();
        $end = $this->cal_expiation($start);
        $last_update = isset($row['ssn_start']) ? $row['ssn_start'] : '';


        $data = array(
            'ssn_name' => $key,
            'ssn_fk_users' => $usr_id,
            'ssn_start' => $start,
            'ssn_expire' => $end,
            'ssn_ip' => $this->input->ip_address(),
            'ssn_devise' => $this->get_device(),
            'ssn_mac' => $this->get_mac(),
            'ssn_last_updated' => $last_update,
        );

        $this->db->insert('session', $data);
    }

    function save_remember_me($user_id, $cookie, $old_cookie = '')
    {
        if (!$cookie)
            return;
        if ($old_cookie) {

            $this->db->set('rmbr_cookie', $cookie);
            $this->db->set('rmbr_last_updated', get_sql_date_time());
            $this->db->where("rmbr_fk_users", $user_id);
            $this->db->where('rmbr_cookie', $old_cookie);
            $this->db->update('remember_me');
        } else {
            $data['rmbr_fk_users'] = $user_id;
            $data['rmbr_name'] = '';
            $data['rmbr_cookie '] = $cookie;
            $data['rmbr_last_updated'] = get_sql_date_time();
            $this->db->insert('remember_me', $data);
        }
    }

    function forgot_me($user_id, $remember_me)
    {
        $cookie = get_cookie(encript_cookie_name($remember_me), TRUE);
        $where['rmbr_fk_users'] = $user_id;
        $where['rmbr_cookie'] = $cookie;
        $this->db->delete('remember_me', $where);
        delete_cookie(encript_cookie_name($remember_me));
    }

    function delete_expired_remembers()
    {
        $expire =  date('Y-m-d 23:59:59', strtotime("-$this->remember_expire months", strtotime(date('Y-m-d'))));
        $where['rmbr_last_updated <= '] =  $expire;
        $this->db->delete('remember_me', $where);
    }

    /**
     * 
     * @param type $start: It must be a formated date as date('Y-m-d H:i:s'). (you may use get_SQL_date_time() function for this)
     * @return type
     */
    function cal_expiation($start)
    {
        return date('Y-m-d H:i:s', strtotime("+$this->expire minutes", strtotime($start)));
    }


    function reset_attempt($user_id, $attempt = 0)
    {
        $this->db->set('usr_attempt', $attempt);
        $this->db->set('usr_lock', NULL);
        $this->db->where("usr_attempt > ", $this->max_attempt);
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function delete_all_attempts($user_id)
    {
        $this->db->set('usr_attempt', 0);
        $this->db->set('usr_attempt_round', 1);
        $this->db->set('usr_lock', NULL);
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function is_locked($user_id)
    {
        $this->db->select("usr_lock");
        $this->db->where("usr_lock >= ", date('Y-m-d H:i:s'));
        $this->db->where($this->p_key, $user_id);
        $r = $this->db->get($this->table)->row_array();
        return $r['usr_lock'];
    }

    function lock_user($user_id)
    {
        $this->db->set('usr_lock', date('Y-m-d H:i', strtotime("+$this->lock_time min")));
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function get_user_id($unm, $active = false)
    {
        $this->db->select("$this->p_key");
        $this->db->where("($this->nameField = '$unm' OR usr_email = '$unm' OR usr_mob = '$unm')");
        if ($active)
            $this->db->where($this->statusField, ACTIVE);
        $r = $this->db->get($this->table)->row_array();
        $r = isset($r[$this->p_key]) ? $r[$this->p_key] : '';
        return $r;
    }

    function is_active_user($user_id)
    {
        $this->db->from("$this->table");
        $this->db->where($this->statusField, ACTIVE);
        $this->db->where($this->p_key, $user_id);
        return $this->db->count_all_results();
    }

    function check_attempts($user_id)
    {
        $this->db->from("$this->table");
        $this->db->where("usr_attempt", $this->max_attempt);
        $this->db->where($this->p_key, $user_id);
        $count = $this->db->count_all_results();
        return $count;
    }

    function check_attempts_round($user_id)
    {
        $this->db->from("$this->table");
        $this->db->where("usr_attempt_round > ", $this->max_attempt_round);
        $this->db->where($this->p_key, $user_id);
        $count = $this->db->count_all_results();
        return $count;
    }

    function increment_attempt($user_id)
    {
        $this->db->set('usr_attempt', 'usr_attempt + 1', FALSE);
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function increment_attempt_round($user_id)
    {
        $this->db->set('usr_attempt', 1);
        $this->db->set('usr_attempt_round', 'usr_attempt_round + 1', FALSE);
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function deactivate_user($user_id)
    {
        $this->db->set('usr_status', INACTIVE);
        $this->db->where($this->p_key, $user_id);
        $this->db->update($this->table);
    }

    function check_password($user_id, $pwd)
    {
        $this->db->from($this->table);
        $this->db->select('usr_password');
        $this->db->where($this->p_key, $user_id);
        $r = $this->db->get()->row_array();

        $pwd_enc = $r['usr_password'];
        return check_encription($user_id, $pwd, $pwd_enc);
    }

    function check_otp($user_id, $otp)
    {
        $this->db->from($this->table);
        $this->db->where($this->p_key, $user_id);
        $this->db->where('usr_otp', $otp);
        $r = $this->db->get()->row_array();
        return $r;
    }

    function get_device()
    {
        $this->load->library('user_agent');

        if ($this->agent->is_browser()) {
            // $agent = $this->agent->browser() . ' ' . $this->agent->version();
            $agent = 'PC';
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unknown User Agent';
        }

        return $agent;
    }

    function get_mac()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddr = false;

        #run the external command, break output into lines
        $arp = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        #look for the output line describing our IP address
        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if ($cols[0] == $ipAddress) {
                $macAddr = $cols[1];
            }
        }
        return $macAddr;
    }



    //   function set_configs($max_attempt, $expire)
    //   {
    //      $this->max_attempt = $max_attempt;
    //      $this->expire = $expire;
    //   }


    // function is_in_group($grp_id, $match_cli = TRUE)
    // {
    //     // The client id will be matchedd inside the $this->is_logged_in() function
    //     $usr_id = $this->is_logged_in($match_cli);

    //     if (!$usr_id)
    //         return FALSE;

    //     $this->db->where('usr_id', $usr_id);
    //     $this->db->where("FIND_IN_SET($grp_id,usr_group)");
    //     $count = $this->db->count_all_results($this->table);
    //     return $count;
    // }

    // function is_master_admin($match_cli = TRUE)
    // {
    //     $grp_id = 1; // Group id of Master Admin = 1 (Table: erp_groups)
    //     return $this->is_in_group($grp_id, $match_cli);
    // }

    // function is_admin($match_cli = TRUE)
    // {
    //     $grp_id = 2; // Group id of Admin = 2 (Table: erp_groups)
    //     return $this->is_in_group($grp_id, $match_cli);
    // }

    // function get_user_id($unm, $cli_id)
    // {
    //     $this->db->from("$this->table");
    //     $this->db->select('usr_id');
    //     $this->db->where($this->nameField, $unm);
    //     $this->db->where('usr_fk_clients', $cli_id);
    //     $row = $this->db->get()->row_array();

    //     if ($row)
    //         return $row['usr_id'];

    //     return '';
    // }

    // function is_active_user($unm, $cli_id)
    // {
    //     $this->db->from("$this->table");
    //     $this->db->where($this->statusField, ACTIVE);
    //     $this->db->where($this->nameField, $unm);
    //     $this->db->where('usr_fk_clients', $cli_id);
    //     return $this->db->count_all_results();
    // }

    // function deactivate_user($unm, $cli_id)
    // {
    //     $this->db->set('usr_status', INACTIVE);
    //     $this->db->where($this->nameField, $unm);
    //     $this->db->where('usr_fk_clients', $cli_id);
    //     $this->db->update($this->table);
    // }

    // function check_password_expiration($user_id, $pwd, $cli_id)
    // {
    //     $this->db->from("$this->table");
    //     $this->db->where($this->p_key, $user_id);
    //     $this->db->where('usr_password', $pwd);
    //     $this->db->where('usr_fk_clients', $cli_id);
    //     $this->db->where('usr_start <= ', get_SQL_date_time());
    //     $this->db->where('usr_expire >= ', get_SQL_date_time());
    //     $count = $this->db->count_all_results();
    //     return $count;
    // }
}
