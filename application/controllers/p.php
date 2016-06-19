<?php
/*
	公共权限文件
*/
class P extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function hello()
	{
	
		$this->load->model("Login_model") ;
		$this->Login_model->test() ;
		$this->Login_model->check_login();
		dump($this->Login_model->err_msg,'red') ;
		dump($this->Login_model->username,'blue') ;								// 加载了权限模块后，就能显示出该登录的用户的用户名
		dump($this->Login_model->login,'green');								// 是否登录标记
		dump($this->Login_model->gameids,'yellow') ;							// 数组；就是用户拥有的游戏权限，如果==0；就是没有限制游戏

		dump($this->Login_model->get_files(),'red') ;							// 用户拥有的文件访问列表，通常放在左边做列表显示的
		dump($this->Login_model->group_files,'green') ;
		
		dump($_SERVER,'red');
		$url = $_SERVER["PATH_INFO"] ;

		echo "
		<script type=\"text/javascript\">count();</script>
		<div class=\"pv-box\" id=\"count\"></div>
		" ;
		$this->Login_model->get_log($url) ;
	}
	public function count()
	{
		$url = isset($_POST['url'])?trim($_POST['url']) : '' ;
		$this->load->model("Login_model") ;
		$this->Login_model->get_log($url) ;
	}
	public function show_notice()
	{	
		$html = '<div class="tip-background">
      		<span class="left-trigon">◆</span>
      		<section class="content-wrap">
      			<div class="xiezi"></div>
      			<p><a class="tip-title">&nbsp;标题1:</a>111111111111111111111</p>
      			<p><a class="tip-title">&nbsp;标题2:</a>2222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222</p>
      			<p><a class="tip-title">&nbsp;标题3:</a>3333333333333333333333333333333333333333333333333333333333333333333333</p>
      			<p><a class="tip-title">&nbsp;标题3:</a>3333333333333333333333333333333333333333333333333333333333333333333333</p>
      			<div class="xiezi"></div>
      		</section>
      	</div>
        <div class="tip-content">
        	<section class="content-wrap">
        		<div class="xiezi"></div>
      			<p><a class="tip-title">&nbsp;标题1:</a>111111111111111111111</p>
      			<p><a class="tip-title">&nbsp;标题2:</a>2222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222</p>
      			<p><a class="tip-title">&nbsp;标题3:</a>3333333333333333333333333333333333333333333333333333333333333333333333</p>
      			<p><a class="tip-title">&nbsp;标题3:</a>3333333333333333333333333333333333333333333333333333333333333333333333</p>
      			<div class="xiezi"></div>
      		</section>
        </div>';
        echo $html;
	}
	public function __destruct()
	{
		//dump("public function __destruct()");
	}
}