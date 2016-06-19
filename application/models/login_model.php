<?php

class Login_model extends CI_Model {
 	


	public function check_login() {

		$hash = isset($_COOKIE['_hash_']) ? trim($_COOKIE['_hash_']) : '';
		$username = isset($_COOKIE['_username_']) ? trim($_COOKIE['_username_']) : '';

		if ($hash) {
			$sql = "SELECT * FROM guoguovpn.member WHERE hash='$hash'";
			$db = $this->load->database('guoguovpn', TRUE);
			$userinfo = $db->query($sql)->row_array();
			if (!$userinfo) {
				return FALSE;
			}

			if (($userinfo['email'] == $username) || ($userinfo['mobile'] == $username)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function login($username, $password) {

		if (!$username || !$password) {
			return FALSE;
		}

		$sql = "SELECT * FROM guoguovpn.member WHERE mobile='$username' OR email='$username'";
		$db = $this->load->database('guoguovpn', TRUE);
		$userinfo = $db->query($sql)->row_array();
		if (!$userinfo) {
			return FALSE;
		}
		
		//var_dump($userinfo['password'], md5($password));exit;
	
		$userinfo['password'] = stripslashes($userinfo['password']);
		if ($userinfo['password'] != md5($password)) {
			return FALSE;
		}

		$username = !empty($userinfo['email']) ? $userinfo['email'] : $userinfo['mobile'];

		$hash = md5($userinfo['password'].time());

		$sql = "UPDATE guoguovpn.member SET `hash`='$hash' WHERE id=".$userinfo['id'];
		$ret = $db->query($sql);

		$_SESSION['username'] = $username;
		$_SESSION['user_id'] = $userinfo['id'];
		$_SESSION['is_login'] = TRUE;
		//写入cookie
		setcookie('_hash_', $hash, time() + 3600*24*30, '/');
		setcookie('_username_', $username, time() + 3600*24*30, '/');
		return $userinfo;
	}
}
?>