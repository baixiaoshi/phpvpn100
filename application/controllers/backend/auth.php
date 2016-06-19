<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends MY_Controller
{


	public function __contruct()
	{
		$this->load->model("Login_model");
		$this->Login_model->check_login();
		parent::__contruct();
	}

	//添加文件权限
	public function add_auth()
	{	

		if(isset($_POST['is_add']) && !empty($_POST['is_add']))
		{	
			$ret = $this->report_file_model->field_exists($_POST);

			if(!$ret)
			{	
				showmsg($this->report_file_model->errmsg,0);
			}
			$cat_mod = isset($_POST['cat_mod']) ? trim($_POST['cat_mod']) : '';
			$parent_id = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : 0;
			$filepath = isset($_POST['filepath']) ? addslashes($_POST['filepath']) : '';
			$filename = isset($_POST['filename']) ? trim($_POST['filename']) : '';
			$group_type = isset($_POST['group_type']) ? trim($_POST['group_type']) : 0;
			$order = isset($_POST['order']) ? trim($_POST['order']) : 0;
			$is_show = isset($_POST['is_show']) ? trim($_POST['is_show']) : 0;
			$open_all = isset($_POST['open_all']) ? trim($_POST['open_all']) : 0;
			

			$field = "`mod`,`parent_id`,`filepath`,`filename`,`group_type`,`order`,`show`,`open_all`";
			$values = "'$cat_mod',$parent_id,'$filepath','$filename',$group_type,$order,$is_show,$open_all";
			$ret = $this->report_file_model->insert($field,$values);
			if($ret)
				showmsg('添加文件成功',1);
			else
				showmsg('添加文件失败',0);
		}
		//获取模块的类别
		$cat_mod= $this->report_file_model->get_mod();
		
		if(!$cat_mod)
		{
			showerror($this->report_file_model->errmsg);
		}

		$where = ' where  `group_type` !=2';
		$files = $this->report_file_model->get_list([],$where);
		$options = $this->report_file_model->get_file_tree(0,$files,0);
		
		//获取
		$this->smartytpl->assign('options',$options);
		$this->smartytpl->assign('cat_mod',$cat_mod);
		$this->smartytpl->display('backend/add_auth.tpl');
	}

	public function add_group_auth()
	{
		if(isset($_POST['is_add']) && !empty($_POST['is_add']))
		{	
			$ret = $this->report_file_model->field_exists($_POST);

			if(!$ret)
			{	
				showmsg($this->report_file_model->errmsg,0);
			}
			$cat_mod = isset($_POST['cat_mod']) ? trim($_POST['cat_mod']) : '';
			$parent_id = isset($_POST['parent_id']) ? trim($_POST['parent_id']) : 0;
			$filepath = isset($_POST['filepath']) ? addslashes($_POST['filepath']) : '';
			$filename = isset($_POST['filename']) ? trim($_POST['filename']) : '';
			$group_type = isset($_POST['group_type']) ? trim($_POST['group_type']) : 0;
			$order = isset($_POST['order']) ? trim($_POST['order']) : 0;
			$is_show = isset($_POST['is_show']) ? trim($_POST['is_show']) : 0;
			$open_all = isset($_POST['open_all']) ? trim($_POST['open_all']) : 0;
			

			$field = "`mod`,`parent_id`,`filepath`,`filename`,`group_type`,`order`,`show`,`open_all`";
			$values = "'$cat_mod',$parent_id,'$filepath','$filename',$group_type,$order,$is_show,$open_all";
			$insert_id = $this->report_file_model->insert($field,$values);
			if(!$insert_id)
			{	
				showerror('插入数据失败');
			}
			$ret = $this->report_file_model->update_group_ids($parent_id,$insert_id);
			if(!$ret)
			{
				showmsg('数据更新失败',0);
			}
			showmsg('数据更新成功',1);
		}
		//获取模块的类别
		$cat_mod= $this->report_file_model->get_mod();
		if(!$cat_mod)
		{
			showerror($this->report_file_model->errmsg);
		}

		$where = ' where `show`=1 and `group_type` =1';
		$files = $this->report_file_model->get_list([],$where);
		
		//获取
		$this->smartytpl->assign('files',$files);
		$this->smartytpl->assign('cat_mod',$cat_mod);
		$this->smartytpl->display('backend/add_group_auth.tpl');
	}


	//配置组成员文件
	public function config_group_auth()
	{	

		if(isset($_POST['is_add']) && !empty($_POST['is_add']))
		{	
			$id = trim($_POST['id']);
			if(!empty($_POST['auths']))
			{
				$auths = explode(',', $_POST['auths']);
				foreach($auths as $key=>$val)
				{
					$auths[$key] = (int)$val;
				}
			}
			else
			{
				$auths = array();
			}
			$auths = json_encode($auths);
			$set = " `group_ids`='$auths'";
			$where = " where id=$id";
		 	$ret = $this->report_file_model->update($set,$where);
		 	if(!$ret)
		 	{
		 		showmsg('更新数据失败');
		 	}
		 	showmsg('更新数据成功');
		}
		$where = " where `group_type`=1 and `show`=1";
		$row = $this->report_file_model->get_list_index('id',[],$where);
		
		if(empty($row))
		{
			showerror('组目录为空,不需要配置',0);
		}
		if(isset($_POST['id']) && !empty($_POST['id']))
		{
			$id = trim($_POST['id']);
			$group_ids = $row[$id]['group_ids'];
		}
		else
		{	
			$new_row = current($row);
			$id = $new_row['id'];
			$group_ids = $new_row['group_ids'];
		}
		
		if($group_ids == '')
		{
			$group_ids = array();
		}
		else
		{
			$group_ids = json_decode($group_ids);
		}
	
		
		$where = " where `group_type`=2 and `show`=1";
		$nodes = $this->report_file_model->get_list([],$where);
		
		foreach($nodes as $k=>$v)
		{	
			
			if(in_array($v['id'], $group_ids))
			{
				$nodes[$k]['is_sel'] = 1;
			}
			else
			{
				$nodes[$k]['is_sel'] = 0;
			}
		}
	
		$this->smartytpl->assign('id',$id);
		$this->smartytpl->assign('nodes',$nodes);
		$this->smartytpl->assign('groups',$row);
		$this->smartytpl->display('backend/config_group_auth.tpl');

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
			$where .= " and `username` like '%$kw%'";
		}
		$total_rows = $this->user_model->get_total_rows($where);
		$pagesize = isset($_POST['mypagesize']) ? $_POST['mypagesize'] : 10;				//分页
		$offset = isset($_POST['page']) ? ((int)$_POST['page'] - 1)*$pagesize : 0;
		$where .=" limit $offset,$pagesize";
		
		$pageobj = $this->get_pagecode($total_rows,$pagesize,'/backend/user/get_user_list','ajax_node');

		$users = $this->user_model->get_user($where);
		if(!$users)
		{
			show_error($this->user_model->errmsg);
		}

		$this->smartytpl->assign('mypagesize',$pagesize);
		$this->smartytpl->assign('page',$page);
		$this->smartytpl->assign('keyword',$keyword);
		$this->smartytpl->assign('pagerstr',$pageobj->pageStr());
		$this->smartytpl->assign('users',$users);
		$this->smartytpl->display('backend/user_list.tpl');
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