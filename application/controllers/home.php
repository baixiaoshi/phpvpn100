<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index() {

		$data = array();
		$this->load->view("home/index",$data);
	}


	public function logout() {


		if (isset($_SESSION['username'])) {
			$sid = session_id();
			unset($_SESSION['username']);
			unset($_SESSION['user_id']);
			unset($_SESSION['is_login']);

			setcookie($sid, '', time()-3600, '/');
			setcookie('_hash_', '', time()-3600, '/');
			setcookie('_username_', '', time()-3600, '/');

		}
		header("Location: /home/index");

	}
	public function login() {
		
		$p = $this->input->post();
		$username = isset($p['username']) ? trim($p['username']) : '';
		$password = isset($p['password']) ? trim($p['password']) : '';

		$this->load->model('login_model');
		// $login_check_ret = $this->login_model->check_login();


		// if ($login_check_ret) {
		// 	header("Location: /home/index");
		// 	return ;
		// }
		if (!empty($username) && !empty($password)) {

			
			$login_ret = $this->login_model->login($username, $password);
			
			if ($login_ret) {
				ret_msg(0, '登入成功');
			} else {
				ret_msg(1, '登入失败');
			}
		}

		$this->load->view("home/login");
	}

	public function register() {
		$p = $this->input->post();
		
		if (isset($_SESSION['send_time']) && isset($p['vcode'])) {
			$ttl = 60;
			//校验vcode是否过期
			$send_time = $_SESSION['send_time'];
			if (($send_time + $ttl) < time()) {
				ret_msg(1, '验证码已经过期了');
				return TRUE;
			}
			$vcode = isset($_SESSION['vcode']) ? trim($_SESSION['vcode']) : '';
			if ($vcode != trim($p['vcode'])) {
				ret_msg(2, '验证码填写错误'.$vcode);
				return TRUE;
			}

			$mobile = isset($p['mobile']) ? trim($p['mobile']) : '';
			$email = isset($p['email']) ? trim($p['email']) : '';
			$password = isset($p['password']) ? trim($p['password']) : '';

			$this->load->model('member_model');
			$user_exists = $this->member_model->check_exists($mobile, $email);

			if ($user_exists) {
				ret_msg(2, '该用户已存在!');
				return TRUE;
			}

			$add_ret = $this->member_model->add_user($mobile, $email, $password);
			if (!$add_ret) {
				ret_msg(3, '注册失败');
				return TRUE;
			}

			ret_msg(0, '注册成功!');
			return TRUE;
		}		


		$this->load->view("home/register");
	}


	public function send_msg() {

		$p = $this->input->post();
		$mobile = isset($p['mobile']) ? trim($p['mobile']) : '';

		if (!preg_match("/^\d{11}$/i", $mobile)) {
			ret_msg(2, '手机号码格式不对!');
			return FALSE;
		}


		$this->load->model('member_model');

		$user_exists = $this->member_model->check_exists($mobile, 'xxx.com');
		if ($user_exists) {
			ret_msg(2, '该用户已存在!');
			return TRUE;
		}

		$user = 'baixiaoshi';
		$pass = 'baixiaoshi';
		$vcode = gen_vcode(4);
		$ttl  = 60;
		if (isset($_SESSION['send_time'])) {

			$send_time = $_SESSION['send_time'];
			if (($send_time + $ttl) > time()) {
				ret_msg(1, '发送过于频繁');
				return TRUE;
			}
		}
		
		$_SESSION['vcode'] = $vcode;
		$_SESSION['send_time'] = time();


		sms_send($user, $pass, $vcode, $mobile, $ttl);
		ret_msg(0, '发送信息成功');
		return TRUE;
	}

	public function send_mail() {
		$p = $this->input->post();
		$email = isset($p['email']) ? trim($p['email']) : '';
		if (!preg_match("/@\w*\.com$/i", $email)) {
			ret_msg(2, '邮件格式不对!');
			return FALSE;
		}
		$this->load->model('member_model');
		$user_exists = $this->member_model->check_exists('12323', $email);
		if ($user_exists) {
			ret_msg(2, '该用户已存在!');
			return TRUE;
		}


		$vcode = gen_vcode(4);
		$ttl  = 60;
		if (isset($_SESSION['send_time'])) {

			$send_time = $_SESSION['send_time'];
			if (($send_time + $ttl) > time()) {
				ret_msg(1, '发送过于频繁');
				return TRUE;
			}
		}
		
		$_SESSION['vcode'] = $vcode;
		$_SESSION['send_time'] = time();
		send_email($email, '验证码:'.$vcode.' ,phpvpn100', 'code:'.$vcode);
		ret_msg(0, '发送验证码成功，请查收邮件!');
		return TRUE;
		

	}


}