<?php

class Member_model extends CI_Model {
 	
 	public function add_user($mobile, $email, $password) {

 		if (empty($mobile) && empty($email) && empty($password)) {
 			return FALSE;
 		}
 		$db = $this->load->database('guoguovpn', TRUE);

        $password = addslashes($password);
        $md5_pwd = md5($password);
 		$sql = "INSERT INTO member(mobile, email, `password`,real_password)VALUES('$mobile','$email','$md5_pwd','$password')";
 		$ret = $db->query($sql);
 		return $ret ? TRUE : FALSE;
 	}

    public function check_exists($mobile, $email) {


        $sql = "SELECT * FROM guoguovpn.member WHERE mobile='$mobile' OR email='$email'";
       
        $db = $this->load->database('guoguovpn', TRUE);
        $ret = $db->query($sql)->row_array();

       return empty($ret) ? FALSE : TRUE;
    }

    public function get_userinfo($user_id) {
        if (!is_numeric($user_id)) {
            return FALSE;
        }

        $sql = "SELECT * FROM member WHERE id=$user_id";

        $db = $this->load->database('guoguovpn', TRUE);
        $userinfo = $db->query($sql)->row_array();

        if (!$userinfo) {
            return FALSE;
        }
        return $userinfo;
    }


    public function add_user_time($mobile, $email, $days) {

        $sql = "SELECT * FROM guoguovpn.member WHERE mobile='$mobile' AND email='$email'";
        $db = $this->load->database('guoguovpn', TRUE);
        $ret_obj = $db->query($sql)->row();
        if ($ret_obj) {
            
            $end_time = $ret_obj->end_time;
            if ($end_time <= time()) {
                $point_time = time();
            } else {
                $point_time = $end_time;
            }
            $end_time_stamp = $point_time + ($days * 24 * 3600);
            $user_id = $ret_obj->id;
            $sql = "UPDATE guoguovpn.member SET end_time='$end_time_stamp' WHERE id=$user_id";
            $ret = $db->query($sql);
            if ($ret) {
                return TRUE;
            }
        }

        return FALSE;

    }

    public function reload_pptpd() {
        $time = time();
        $sql = "SELECT * FROM guoguovpn.member WHERE end_time > $time";

        $db = $this->load->database('guoguovpn', TRUE);
        $users = $db->query($sql)->result();
        $config_file = '/tmp/chap-secrets';
        if (file_exists($config_file)) {
            unlink($config_file);
        }
        
        foreach ($users as $user) {
            
            if (!empty($user->mobile)) {
                $username = $user->mobile;
            } else if (!empty($user->email)) {
                $username = $user->email;
            }
            $real_password = $user->real_password;
            $content = "$username pptpd $real_password *";

            file_put_contents($config_file, $content."\r\n", FILE_APPEND);

        }
        return TRUE;
    }

}
?>