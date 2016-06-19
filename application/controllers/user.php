<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	public function __contruct()
	{
		parent::__contruct();
	}


	public function getinfo() {

		$this->load->model('member_model');
		if (isset($_SESSION['user_id'])) {
			$user_id = trim($_SESSION['user_id']);
			$userinfo = $this->member_model->get_userinfo($user_id);
		} else {
			exit('你还没有登入!');
		}
		$data['userinfo'] = $userinfo;
		$this->load->view("member/getinfo", $data);
	}

	public function admin888() {

		$p = $this->input->post();
		if (isset($p['mobile']) && isset($p['email']) && isset($p['days'])) {
			
			$mobile = trim($p['mobile']);
			$email = trim($p['email']);
			$days = trim($p['days']);

			if (!empty($mobile)) {
				if (!preg_match("/^\d{11}$/i", $mobile)) {
					ret_msg(2, '手机号码格式不对!');
					return FALSE;
				}
			}

			if (!empty($email)) {
				if (!preg_match("/@\w*\.com$/i", $email)) {
					ret_msg(2, '邮件格式不对!');
					return FALSE;
				}
			}

			$days = intval($days);
			if (!is_int($days) || ($days <= 0)) {
				ret_msg(3, '天数格式不对');
				return FALSE;
			}

			$this->load->model('member_model');
			$ret = $this->member_model->add_user_time($mobile, $email, $days);
			if (!$ret) {
				 ret_msg(4, '插入数据库失败');
				 return FALSE;
			}

			//下面做整个文件的重启操作
			$ret = $this->member_model->reload_pptpd();
			ret_msg(0, $ret);
			return TRUE;
		}
		$this->load->view("member/admin888");

	}


}