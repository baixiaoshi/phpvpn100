<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Controller
{


	public function __contruct()
	{
		$this->load->model("Login_model");
		$this->Login_model->check_login();
		parent::__contruct();
	}


	public function add_game_auths()
	{	
		$p = $this->input->post();
		if(!isset($p['userid'])) show_error('userid参数必须有');
		$userid = $p['userid'];
		$auths = isset($p['auths']) ? trim($p['auths']) : '';
		if($auths)
		{
			$auths = explode(',', $auths);
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

		$ret = $this->user_model->update_gameids($userid,$auths);
		if(!$ret) showmsg('添加游戏权限失败!!!',0);
		showmsg('添加游戏权限成功!!!',1);
	}

	/**
	 * 获取用户列表
	 * @return [type] [description]
	 */
	public function get_user_list()
	{	

		$page = isset($_POST['page']) ? addslashes($_POST['page']) : 1;
		$keyword = isset($_POST['kw']) ? addslashes($_POST['kw']) : '';

		$where = 'where 1=1 ';
		if(isset($_POST['kw']) && !empty($_POST['kw']))
		{	
			$kw = addslashes($_POST['kw']);
			$where .= " and `username` like '%$kw%' or `realname` like '%$kw%'";
		}
		$total_rows = $this->user_model->get_total_rows($where);
		$pagesize = isset($_POST['mypagesize']) ? $_POST['mypagesize'] : 10;				//分页
		$offset = isset($_POST['page']) ? ((int)$_POST['page'] - 1)*$pagesize : 0;
		$where .=" order by `uid` desc limit $offset,$pagesize";
		
		$pageobj = $this->get_pagecode($total_rows,$pagesize,'/backend/user/get_user_list','ajax_node');

		$users = $this->user_model->get_user($where);
		
		$this->smartytpl->assign('mypagesize',$pagesize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->assign('pagerstr',$pageobj->pageStr());
		$this->smartytpl->assign('users',$users);
		$this->smartytpl->display('backend/user_list.tpl');
	}

	/**
	 * 添加用户
	 */
	public function add_user()
	{	
		$groups = $this->group_model->get_data(['group_id','group_name']);
		
		if( isset($_POST['is_add']) && !empty($_POST['is_add']) )
		{	
			$username = ( isset($_POST['username']) && !empty($_POST['username']) ) ? addslashes($_POST['username']) : '';
			$realname = ( isset($_POST['realname']) && !empty($_POST['realname']) ) ? addslashes($_POST['realname']) : '';
			$is_super = ( isset($_POST['is_super']) && !empty($_POST['is_super']) ) ? addslashes($_POST['is_super']) : 0;

			if( isset($_POST['group_ids']) && !empty($_POST['group_ids']) )
			{
				$group_ids = explode(',', trim($_POST['group_ids']));
				foreach($group_ids as $k=>$v)
				{
					$group_ids[$k] = (int)$v;
				}
			}	
			else
			{
				$group_ids = array();
			}
			$group_ids = json_encode($group_ids);

			$ret = $this->user_model->exists_user($username);
			if(!$ret)
			{
				showmsg($this->user_model->errmsg,0);
			}
			$field = array('username','realname','group_ids','is_super_admin');
			$value = array("'".$username."'","'".$realname."'","'".$group_ids."'",$is_super);
			$this->user_model->insert($field,$value);
			showmsg('添加用户成功',1);
		}

		$this->smartytpl->assign('groups',$groups);
		$this->smartytpl->display('backend/add_user.tpl');
	}

	/**
	 * 编辑用户
	 * @return [type] [description]
	 */
	public function edit_user()
	{	
		if(!isset($_POST['uid']) || empty($_POST['uid']))
			showerror('uid参数必须传递');
		$uid = addslashes(trim($_POST['uid']));

			$mypagesize = isset($_POST['mypagesize']) ? $_POST['mypagesize'] : 10;	
		$page = isset($_POST['page']) ? addslashes($_POST['page']) : 1;
		$keyword = isset($_POST['kw']) ? addslashes($_POST['kw']) : '';

		$where = " where `uid`=$uid";
		if(isset($_POST['is_edit']) && !empty($_POST['is_edit']))
		{	
			$username = ( isset($_POST['username']) && !empty($_POST['username']) ) ? addslashes($_POST['username']) : '';
			$realname = ( isset($_POST['realname']) && !empty($_POST['realname']) ) ? addslashes($_POST['realname']) : '';
			$is_super = ( isset($_POST['is_super']) && !empty($_POST['is_super']) ) ? addslashes($_POST['is_super']) : 0;

			if( isset($_POST['group_ids']) && !empty($_POST['group_ids']) )
			{
				$group_ids = explode(',', trim($_POST['group_ids']));
				foreach($group_ids as $k=>$val)
				{
					$group_ids[$k] = (int) $val;
				}
			}	
			else
			{
				$group_ids = array();
			}
				
			$group_ids = json_encode($group_ids);
			
			$set = " set `username`='$username',`realname`='$realname',`is_super_admin`='$is_super',`group_ids`='$group_ids'";

			$ret = $this->user_model->update($set,$where);
			if(!$ret)
			{
				showmsg('更新失败');
			}
			showmsg('更新成功');
		}
		else
		{
			$user = $this->user_model->get_list(['username','realname','group_ids','ban','is_super_admin'],$where);
			$group = $this->group_model->get_data(['group_id','group_name']);
			
			if(!empty($user) && !empty($user[0]['group_ids']))
			{
				$group_ids = json_decode($user[0]['group_ids']);
			}
			else
			{
				$group_ids = array();
			}
			foreach($group as $k=>$v)
			{
				if(in_array($v['group_id'], $group_ids))
				{
					$group[$k]['is_sel'] = 1;
				}
				else
				{
					$group[$k]['is_sel'] = 0;
				}
			}
		}
		
		
		$this->smartytpl->assign('mypagesize',$mypagesize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->assign('uid',$uid);
		$this->smartytpl->assign('user',$user[0]);
		$this->smartytpl->assign('group',$group);
		$this->smartytpl->display('backend/edit_user.tpl');
	}

	/**
	 * 封号一个用户
	 * @return [type] [description]
	 */
	public function forbidden_user()
	{
		if(!isset($_POST['uid']) && !empty($_POST['uid']))
		{
			showerror('uid参数错误');
		}
		$uid = addslashes($_POST['uid']);
		$ban = trim($_POST['ban']);

		$set = " set `ban`=$ban";
		$where = " where `uid`=$uid";
		$ret = $this->user_model->update($set,$where);
		if(!$ret)
		{
			showmsg('切换状态错误',0);
		}
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