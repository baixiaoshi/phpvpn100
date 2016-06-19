<?php
/*
	/group/groups/xxx
 */
class Group extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function groups()
	{
		$this->load->model('finance/Review_model');
		$this->load->model('Review_model');
		$url = trim($_SERVER["PATH_INFO"]);
		$ret = $this->Review_model->get_tree_v2($url);
	
		//取出show=0 的文件
		foreach($ret as $k=>$v)
		{
			if($v['show'] == 0)
				unset($ret[$k]);
		}
		
		$first_path = $ret[0]['filepath'] ;
		$this->smartytpl->assign('menu',$ret) ;
		$this->smartytpl->assign('first_path',$first_path) ;
		$this->smartytpl->display('group_nav.tpl');
	}

	public function show_blank()
	{
		echo '<center><h1>页面正在开发中!!!</h1></center>';
	}
}

