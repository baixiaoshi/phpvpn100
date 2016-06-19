<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{	

	public function __construct()
	{	
		parent::__construct();
		session_start();

	}

	/**
	 * 判断是否登入
	 * @return boolean [description]
	 */
	public function try_login()
	{
		$this->load->model("Login_model");
		return $this->Login_model->check_login();
		
	}

}