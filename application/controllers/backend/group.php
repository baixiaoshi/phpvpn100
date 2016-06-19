<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Group extends MY_Controller
{


	public function __contruct()
	{
		$this->load->model("Login_model");
		$this->Login_model->check_login();
		
		parent::__contruct();
	}


	/**
	 * 获取用户组列表
	 * @return [type] [description]
	 */
	public function get_group_list()
	{	if(isset($_POST['postdata']))
			$_POST = $_POST['postdata'];
		$this->load->model('Group_model');
		$where = ' where group_id > 0';
		$group_name = '';
		if(isset($_POST['keyword']) && !empty($_POST['keyword']))
		{
			$group_name = addslashes($_POST['keyword']);
			$where .= " AND group_name like '%$group_name%'";
		}
			
		$pagesize = isset($_POST['persize']) ? $_POST['persize'] : 10;				//分页
		$offset = isset($_POST['page']) ? ((int)$_POST['page'] - 1)*$pagesize : 0;

		$total_rows = $this->Group_model->get_total_rows($where);
		$ret = $this->Group_model->get_list($offset,$pagesize,$where);
		
		$pageobj = $this->get_pagecode($total_rows,$pagesize,'/backend/group/get_group_list','ajax_node');
		$this->smartytpl->assign('pagerstr',$pageobj->pageStr());
		$this->smartytpl->assign('ret',$ret);
		$this->smartytpl->assign('keyword',$group_name);
		$this->smartytpl->display('backend/group_list.tpl');
	}


	/**
	 * 添加用户组
	 */
	public function add_group()
	{	
		if(isset($_POST['postdata']))
			$_POST = $_POST['postdata'];

		$this->load->model('Group_model');
		$ret = $this->Group_model->add_group_check(addslashes($_POST['group_name']));
		switch($ret)
		{
			case -1:
				showmsg('此用户组已经存在!!!',0);
				break;
			case -2:
				showmsg('插入用户组失败!!!',0);
				break;
			case 1:
				showmsg('添加用户组成功!!!',1);
		}

	}

	/**
	 * 编辑用户组
	 * @return [type] [description]
	 */
	public function edit_group()
	{	if(isset($_POST['postdata']))
			$_POST = $_POST['postdata'];
		$this->load->model('Group_model');
		$group_id = addslashes($_POST['group_id']);
		$where = " where group_id='$group_id'";
		$ret = $this->Group_model->edit_group_check(addslashes($_POST['group_name']),$where);
		switch($ret)
		{
			case -1:
				showmsg('此用户组已经存在!!!',0);
				break;
			case -2:
				showmsg('更新用户组失败!!!',0);
				break;
			case 1:
				showmsg('更新用户组成功!!!',1);
		}
	}


	public function add_game_auths()
	{
		if(!isset($_POST['group_id'])) show_error('group_id参数必须有');
		$group_id = addslashes($_POST['group_id']);

		if(isset($_POST['auths']) && !empty($_POST['auths']))
		{
			$auths = explode(',', addslashes($_POST['auths']));
			foreach($auths as $k=>$v)
			{
				$auths[$k] = (int)$v;
			}
			$auths = json_encode($auths);
		}
		else
		{
			$auths = json_encode(array());
		}	
		$set = " `gameids`='$auths'";
		$where = " where `group_id`=$group_id";
		$ret = $this->group_model->update($set,$where);
		if(!$ret) showmsg('添加游戏权限失败!!!',0);
		showmsg('添加游戏权限成功!!!',1);
	}

	//添加文件权限
	public function add_file_auths()
	{	
		if(!isset($_POST['group_id'])) show_error('group_id参数必须有');
		$group_id = addslashes($_POST['group_id']);

		if(isset($_POST['auths']) && !empty($_POST['auths']))
		{
			$auths = explode(',', addslashes($_POST['auths']));
			foreach($auths as $k=>$v)
			{
				$auths[$k] = (int)$v;
			}
			
			$auths = json_encode($auths);
		}
		else
		{
			$auths = json_encode(array());
		}	
		$set = " `group_auth`='$auths'";
		$where = " where `group_id`=$group_id";
		$ret = $this->group_model->update($set,$where);
		if(!$ret) showmsg('添加文件权限失败!!!',0);
		showmsg('添加文件权限成功!!!',1);
	}

	public function hand_game_auth()
	{	
		$p = $this->input->post();
		if(!isset($_POST['group_id'])) showerror('group_id参数必须有');
		$group_id = addslashes($_POST['group_id']);
		$userid = isset($p['userid']) ? trim($p['userid']) : 0;
		$persize = isset($_POST['persize']) ? addslashes($_POST['persize']) : 10;
		$page = isset($_POST['page']) ? addslashes($_POST['page']) : 1;
		$keyword = isset($_POST['keyword']) ? addslashes($_POST['keyword']) : '';

		$gameids = $this->group_model->get_games($group_id,$userid);
		$privileges = $this->group_model->get_game_privileges($gameids);

		$all_sel = 1 ;
		foreach($privileges as $source=>$val)
		{
			if($val['is_sel'] == 0)
			{
				$all_sel = 0 ;
				break ;
			}
		}
	
		$this->smartytpl->assign('datajson',json_encode($privileges));
		$this->smartytpl->assign('group_id',$group_id);
		$this->smartytpl->assign('privileges',$privileges);
		$this->smartytpl->assign('persize',$persize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->assign('all_sel',$all_sel);
		$this->smartytpl->display('backend/hand_game_auth.tpl');
	}


	public function user_auth()
	{
		$p = $this->input->post();
		$group_id = isset($p['group_id']) ? trim($p['group_id']) : 0 ;
		$userid = isset($p['uid']) ? trim($p['uid']) : 0;
		$persize = isset($_POST['persize']) ? addslashes($_POST['persize']) : 10;
		$page = isset($_POST['page']) ? addslashes($_POST['page']) : 1;
		$keyword = isset($_POST['keyword']) ? addslashes($_POST['keyword']) : '';

		$gameids = $this->group_model->get_games($group_id,$userid);
		$privileges = $this->group_model->get_game_privileges($gameids);

		$all_sel = 1 ;
		foreach($privileges as $source=>$val)
		{
			if($val['is_sel'] == 0)
			{
				$all_sel = 0 ;
				break ;
			}
		}
	
		$this->smartytpl->assign('datajson',json_encode($privileges));
		$this->smartytpl->assign('userid',$userid);
		$this->smartytpl->assign('privileges',$privileges);
		$this->smartytpl->assign('persize',$persize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->assign('all_sel',$all_sel);
		$this->smartytpl->display('backend/hand_user_auth.tpl');
	}

	/**
	 * 处理权限
	 * @return [type] [description]
	 */
	public function hand_file_auth()
	{
		if(!isset($_POST['group_id'])) showerror('group_id参数必须有');
		$group_id = addslashes($_POST['group_id']);
		$persize = isset($_POST['persize']) ? addslashes($_POST['persize']) : 10;
		$page = isset($_POST['page']) ? addslashes($_POST['page']) : 1;
		$keyword = isset($_POST['keyword']) ? addslashes($_POST['keyword']) : '';
		$privileges = $this->group_model->get_file_privileges($group_id);
		//dump($privileges);
		$this->smartytpl->assign('group_id',$group_id);
		$this->smartytpl->assign('privileges',$privileges);
		$this->smartytpl->assign('persize',$persize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->display('backend/hand_file_auth.tpl');

	}


	/**
	 * 分页的公共控制器
	 * @param  [int] $total_rows   总的记录数
	 * @param  [int] $pagesize     每页显示多少页
	 * @param  [array] $click_config 配置数组
	 * @return [type]               一个分页类的对象
	 */
	public function get_pagecode($total_rows,$pagesize,$url,$node_id)
	{
		require_once APPPATH.'libraries/MY_Pagination.php';
		$click_config = array('url'=>$url,'node_id'=>$node_id);
		$pageobj = new MY_Pagination($total_rows,$pagesize,$click_config);
		return $pageobj;
	}
}