<?php

class Test extends MY_Controller
{
	public $db = null;
	public function __construct()
	{	
		parent::__construct();
		$this->load->model('finance/Income_model');
		$this->db = $this->load->database('app_data',true);
	}


	/**
	 * 1.查找finance_detail表，通过month和project_id来查看是否是相同的记录
	 * 2.查处相同记录再来验证
	 */

	public function fc()
	{	
		$finance = $this->load->database('finance',true);
		$sql = "SELECT * FROM finance.finance_detail";
		$rows = $finance->query($sql)->result_array();
		$ret_arr = array();
		foreach($rows as $key=>$val)
		{	
			/**
			 *  月份+平台+项目+客商
			 */
			if(isset($val['month']) && isset($val['platform_id']) && isset($val['merchant_id']) && isset($val['project_id']))
			{
				$ret_arr[$val['month']][$val['platform_id']][$val['merchant_id']][$val['project_id']][] = $val['order_id'];
			}
		}

		//获取订单的状态

		$sql = "SELECT * FROM finance.finance_order";
		$order_rows = $finance->query($sql)->result_array();
		$order_ret_arr = array();
		foreach($order_rows as $key=>$val)
		{
			if(isset($val['id']))
				$order_ret_arr[$val['id']] = $val['status'];
		}


		$ret_arr_2 = array();
		foreach($ret_arr as $month=>$month_val)
		{
			foreach($month_val as $platform_id=>$platform_val)
			{
				foreach($platform_val as $merchant_id=>$merchant_val)
				{
					foreach($merchant_val as $project_id=>$project_val)
					{	
						//var_dump(count($project_val));
						if(count($project_val) >=2)
						{
							foreach($project_val as $key=>$order_id)
							{	
								if(isset($order_ret_arr[$order_id]))
									$ret_arr_2[$month][$platform_id][$merchant_id][$project_id][$order_id] = $order_ret_arr[$order_id];
							}
						}
					}
				}
			}
		}

		foreach($ret_arr_2 as $month=>$month_val)
		{
			foreach($month_val as $platform_id=>$platform_val)
			{
				foreach($platform_val as $merchant_id=>$merchant_val)
				{
					foreach($merchant_val as $project_id=>$project_val)
					{
						$max_index = self::get_max($project_val);

						dump($project_val);
						// $tmpCount = 1;
						// foreach($project_val as $order_id=>$status)
						// {
						// 	if(($status === $max_index) && ($tmpCound === 1))
						// 	{	
						// 		$tmpCount++;
						// 		continue;	
						// 	}
						// 	$sql = "DELETE  FROM finance.finance_detail WHERE `month`='$month' AND `platform_id`='$platform_id' AND `merchant_id`='$merchant_id' AND `project_id`='$project_id';";
						// 	file_put_contents("/opt/tmp/haha.sql",$sql."\r\n",FILE_APPEND);	
							
						// }
					}
				}
			}
		}

	}


	public function get_max($data)
	{
		if(!is_array($data) || !$data)
			return false;
		$max_val = max($data);
		return $max_val;
	}


	public function get_sql($txt_path,$sql)
	{	
		$tmpsql = $sql;
		$handler = fopen($txt_path,'r');
		$i = 1;
		while(($line = fgets($handler,4096)) !== false)
		{	
			$line_arr = explode("\t", $line);
			$appid = trim($line_arr[0]);
			$classid = trim($line_arr[1]);
			$flag = trim($line_arr[2]);
			$support = trim($line_arr[3]);
			$sql .="('$appid','$classid','$flag','$support'),";
			if($i%100 === 0)
			{
				$sql = rtrim($sql,",");
				$ret = $this->db->query($sql);
				if($ret)
				{	$sql = $tmpsql;
					echo 'ok';
				}
			}
			$i++;
		}
	}


	public function haha()
	{
		$txt_path = '/opt/tmp/sjmf_ralation.txt';
		$sql = "INSERT INTO dw_mofang_app_relation(appid,classid,`flag`,support)VALUES";
		$sql_relation_table = self::get_sql($txt_path,$sql);
		
		//$txt_path = '/opt/tmp/sjmf_relation_detail.txt';
		//$sql = "INSERT INTO dw_mofang_app_relation_detail(appid,classid,`flag`,gamelist)VALUES";
		//$sql_relation_detail_table = self::get_sql($txt_path,$sql);
		
	}

	public function reg()
	{
		//$str = '[2015-12-15 17:46:07] ksystem\HttpRequest.ERROR: http code is not 200 {"url":"http://api.kingnet.com//m/delivery/index/53","http_code":500,"response_msg":"hello world"} []';
		$str = '[2015-12-15 00:01:25] ksystem\HttpRequest.ERROR: http code is not 200 {"url":"https://b2b.mycard520.com.tw/MyCardIngameService/Auth?facId=GFD1678&facTradeSeq=124902864581381450108882&hash=e6727c1499731d98c85ad1d86b8e3d7359a5222e1f456e995d91e80843755cc1","http_code":null,"response_msg":null} []';
		$regexp = "#\[(\d{4}-\d{2}-\d{2}\s?\d{2}:\d{2}:\d{2})\].*?{\"url\":\"(.*?)\",\"http_code\":(.*?),\"response_msg\":(.*?)}.*#is";

		preg_match_all($regexp,$str,$matchs);
		echo '<pre>';
		print_r($matchs);
		echo '</pre>';
	}

	/*回去思路，把所有在发票系统中的提单人找出来，到审批表中查处所有被作废的订单，并且把他们的上一次的状态都查出来，如果不是1,
	则代表是误操作，是需要恢复的数据*/
	public function backup()
	{
		$sql = "SELECT * FROM finance.config_department_user WHERE fp_status=5";
		$ret = $this->db->query($sql)->result_array();
		$username_arr = array();
		foreach($ret as $k=>$v)
		{
			if(isset($v['username']))
				array_push($username_arr,$v['username']);
		}

		$instr = $username_arr ? implode("','",$username_arr) : 0;
		$instr = "'".$instr."'";
		$sql = "SELECT * FROM finance.fp_finance_examine WHERE `status`=-1 AND username IN($instr)";
		$ret = $this->db->query($sql)->result_array();
		$order_id_arr = array();
		foreach($ret as $k=>$v)
		{
			if(isset($v['order_id']))
				array_push($order_id_arr,$v['order_id']);
		}
		$instr = $order_id_arr ? implode(',', $order_id_arr) : 0;

		$sql = "SELECT id,order_id,username,`status`,`update_time` FROM finance.fp_finance_examine WHERE order_id IN($instr) ORDER BY `update_time` DESC";
		$ret = $this->db->query($sql)->result_array();
		$new_arr = array();
		foreach($ret as $k=>$v)
		{
			if(isset($v['order_id']))
				$new_arr[$v['order_id']][] = $v;
		}

		foreach($new_arr as $k=>$v)
		{
			if(isset($v[1]) && ($v[1]['status'] === '1' || $v[1]['status'] === '0' ))
				unset($new_arr[$k]);
		}

		dump($new_arr);
		if($new_arr)
		{
			$this->huifu($new_arr);
		}
	}

	public function huifu($data)
	{
		if(empty($data)) die('恢复完全了');
		$examine_ids = $order_ids = array();
		foreach($data as $k=>$v)
		{
			if(isset($v[0]['id']))
				array_push($examine_ids,$v[0]['id']);
			
		}
		$ids = $examine_ids ? implode(',', $examine_ids) : 0;

		$sql = "DELETE FROM finance.fp_finance_examine WHERE id IN($ids)";
		dump($sql);
		$this->db->query("begin;");
		$ret = $this->db->query($sql);
		if(!$ret)
			$this->db->query('rollback;');

		foreach($data as $k=>$v)
		{	
			$tmpstatus = $v[1]['status'];
			$tmporderid =$v[1]['order_id'];
			$sql = "UPDATE finance.fp_finance_order SET `status`=$tmpstatus WHERE id=$tmporderid";
			dump($sql);
			$ret = $this->db->query($sql);
			if(!$ret)
			{
				$this->db->query('rollback;');
				break;
			}
		}
		$this->db->query('commit;');
		dump('ok');
	}

	/**
	 * 恢复思路:记得备份表
	 * 1.从sr_finance_order表中把所有审批成功的记录查出来,分自主和联运处理
	 * 2.自主的到sr_finance_match表中处理，联运的直接在sr_finance_detail表中处理
	 * 3.把这些记录找出来之后通过month,platform_id,channel_id,project_id来将sr_order_rpt表中的order_id和settlement标记打上
	 * @return [type] [description]
	 */
	public function backup_data()
	{	
		$sql = "SELECT id as order_id,`status`,flag FROM finance.sr_finance_order WHERE `status`=5 OR (is_seal=0 AND `status`=4)";
		
		$this->do_log("/tmp/haha.log",$sql);
		$order_arr = $this->db->query($sql)->result_array();
		$this->do_log("/tmp/haha.log","order_arr:".json_encode($order_arr));
		$order_ids_zizhu = $order_ids_lianyun = array();
		foreach($order_arr as $k=>$v)
		{	
			if(isset($v['flag']))
			{	
				if($v['flag'] === "0")
					array_push($order_ids_zizhu,$v['order_id']);
				else if($v['flag'] === "1")
					array_push($order_ids_lianyun,$v['order_id']);
				else
					die('有不合法的flag字段标记'.$v['order_id']);
			}	
			else
			{
				die('flag这个值有错误');
			}
		}

		$this->do_log("/tmp/haha.log","order_ids_zizhu:".json_encode($order_ids_zizhu));
		$this->do_log("/tmp/haha.log","order_ids_lianyun:".json_encode($order_ids_lianyun));
		$lianyun_ret = $zizhu_ret = array();
		if($order_ids_lianyun)
		{	
			$idstr = $order_ids_lianyun ? implode(',', $order_ids_lianyun) : 0;
			$sql = "SELECT order_id,`month`,platform_id,channel_id,project_id as gameid FROM finance.sr_finance_detail WHERE order_id IN($idstr)";
			$this->do_log("/tmp/haha.log",$sql);
			$lianyun_ret = $this->db->query($sql)->result_array();

			$this->do_log("/tmp/haha.log","zizhu_ret:".json_encode($lianyun_ret));
		}

		if($order_ids_zizhu)
		{
			$idstr = $order_ids_zizhu ? implode(',', $order_ids_zizhu) : 0;
			$sql = "SELECT id AS detail_id,order_id FROM finance.sr_finance_detail WHERE order_id IN($idstr)";
			$this->do_log("/tmp/haha.log",$sql);
			$detail = $this->db->query($sql)->result_array();
			$this->do_log("/tmp/haha.log","detail:".json_encode($detail));
			$new_detail = array();
			if(!$detail) die('detail为空');
			$detail_ids = array();
			foreach($detail as $k=>$v)
			{
				if(isset($v['detail_id']))
					array_push($detail_ids,$v['detail_id']);
				else
					die('detail_id找不到,数据异常');
				$new_detail[$v['detail_id']] = $v;
			}
			$this->do_log("/tmp/haha.log","new_detail:".json_encode($new_detail));
			$detail_idstr = $detail_ids ? implode(',', $detail_ids) : 0 ;
			//去match表中查找
			$sql = "SELECT detail_id,`statdate` as `month`,platform_id,channel_id,gameid FROM finance.sr_finance_match WHERE detail_id IN($detail_idstr)";
		
			$this->do_log("/tmp/haha.log",$sql);
			$zizhu_ret = $this->db->query($sql)->result_array();
			foreach($zizhu_ret as $k=>$v)
			{	
				//dump($new_detail[$v['detail_id']]['order_id']);
				if(isset($v['detail_id']) && isset($new_detail[$v['detail_id']]['order_id']))
				{	
					//dump($new_detail[$v['detail_id']]);
					$zizhu_ret[$k]['order_id'] = $new_detail[$v['detail_id']]['order_id'];
				}
				else
				{
					dump($new_detail[$v['detail_id']]);
				}
					

			}

			$this->do_log("/tmp/haha.log","lianyun_ret:".json_encode($zizhu_ret));
		}

		//把order_rpt表中的数据获取出来
		$sql = "SELECT `statdate` as `month`,platform_id,channel_id,gameid FROM finance.sr_order_rpt WHERE (order_id=0 OR settlement=0)";
		$this->do_log("/tmp/haha.log",$sql);
		$order_rpt = $this->db->query($sql)->result_array();
		$this->do_log("/tmp/haha.log","order_rpt:".json_encode($order_rpt));
		$new_order_rpt = array();
		foreach($order_rpt as $k=>$v)
		{
			if(isset($v['month'],$v['platform_id'],$v['channel_id'],$v['gameid']))
			{
				$new_order_rpt[$v['month']][$v['platform_id']][$v['channel_id']][$v['gameid']] = $v;
			}
		}

		$this->db->query('begin;');
//		var_dump($zizhu_ret,$lianyun_ret);

			$ret = array_merge($zizhu_ret,$lianyun_ret);

			$new_ret = array();
			foreach($ret as $k=>$v)
			{
				if(isset($v['month'],$v['platform_id'],$v['channel_id'],$v['gameid']) && isset($new_order_rpt[$v['month']][$v['platform_id']][$v['channel_id']][$v['gameid']]))
				{	
					list($month,$platform_id,$channel_id,$gameid) =array($v['month'],$v['platform_id'],$v['channel_id'],$v['gameid']);
					$order_id = $v['order_id'];

					$sql = "UPDATE finance.sr_order_rpt SET order_id=$order_id,settlement=1 WHERE statdate='$month' AND platform_id='$platform_id' AND channel_id='$channel_id' AND gameid='$gameid'";
					
					$this->do_log("/tmp/back.sql",$sql);
					$query_ret = $this->db->query($sql);
					if($query_ret)
						$this->db->query('commit;');
					else
					{
						$this->db->query('rollback;');
						$this->do_log("/tmp/back.sql","更新不成功的");
					}
						
				}
				else
				{	

					$this->do_log("/tmp/have_update.sql",$v['month'].'##'.$v['platform_id'].'##'.$v['channel_id'].'##'.$v['gameid']);
				}
			}



		echo 'ok';
	}

	public function do_log($path,$data)
	{
		file_put_contents($path,$data."\r\n",FILE_APPEND|LOCK_EX);
	}
	public function test_insert_no_evaluation_data()
	{	
		$this->load->model('data/evaluate_model');
		$post = '{"evaluation_id":"10000770","app_name":"111","app_size":"post","developers":"2222","game_cate":"10001","download_url":"http:\/\/dev2.xyzs.com\/assets\/app\/test\/\/10000770\/10000770.ipa"}';
		$post = json_decode($post,true);
		$this->evaluate_model->insert_no_evaluation_data($post);

	}
	public function test3()
	{
		$this->load->model('data/evaluate_model');
		$old_evaluation_id = 5813;
		$p = array(
			'app_name'	=>	'app_name',
			'developers'	=>	'developers',
			'level'		=>	'C'
			);
		$this->evaluate_model->del_evaluation($old_evaluation_id,$p);
	}

	public function test2()
	{	

		$this->load->model('data/evaluate_model');
		$params = array(
			'testname'	=>	'baixiaoshi',
			'age'		=>	24,
			'hobby'		=>	'runing'
			);
		$ret = $this->evaluate_model->socket_post_data('http://d.kingnet.com/test/login_v1',$params,80);
		
		//$ret = $this->evaluate_model->curl('http://test.kingnet.com',$params);
	}

	public function do_list()
	{
		file_download('http://dev2.xyzs.com/assets/app/test/770/10000770/10000770.ipa','10000770.ipa');
		exit;
	}
	//接收字段: 评测ID,游戏名称,游戏大小,开发商,游戏类型,下载地址
	public function do_sql()
	{	
		$p = $this->input->post();
		$this->load->model('data/evaluate_model');
		$id = rand(1000,10000);
		$p = array(
			'evaluation_id'	=>	'10000708',
			'app_name'		=>	'222',
			'app_size'			=>	333,//包体大小
			'developers'	=>	'测试开发商',
			'game_cate'		=>	'游戏类型',
			'download_url'	=>	'http://www.baidu.com'
			);
		$ret = $this->evaluate_model->insert_no_evaluation_data($p);
		if($ret['status'] === 0)
			$this->db->query('rollback;');
		$this->db->query('commit;');
		echo json_encode($ret);
	}	




    /*测试CI缓存*/
    public function test_cache()
    {
    	//$this->load->model('finance/Income_model');
    	//$ret = $this->Income_model->get_sr_order_rpt();
    	
    	//测试memcche单个item是不是2M
    	//$this->load->driver('adpter'=>'memcache','backup'=>'file');
    	$this->load->driver('cache',array('adapter'=>'memcache','backup'=>'file'));
    	if(!$foo = $this->cache->get('foo'))
    	{	
    		dump('no cache');
    		$foo = str_repeat('a', 1024*1024*1.5);
    		
    		$this->cache->save('foo',$foo,300);//保存5分钟
    	}
    	dump('cache');
    	dump($foo);

    }
	public function do_count()
	{
        $num1 = '100.21';
        $num2 = '100.20';
        if($num1 == $num2)
            dump('ok');
        else
            dump('failure');
	}


    public function app_monitor()
    {
        $app_monitor = $this->load->database('app_monitor',true);



        for($i=0;$i<60;$i++)
        {
            $statdate = date('Y-m-d',time()+24*3600*$i);
            $sql = "INSERT INTO app_monitor.app_monitor(`statdate`,`terminal`,`appid`,`appname`,`click_num`,`click_user`,`download_num`,`download_user`,`install_num`,`install_user`)VALUES";
            for($j=0;$j<60;$j++)
            {
                $terminal = ($j%2 == 0) ? 'mobile' : 'pc' ;
                $appid = 1000+$j*3;
                $appname = 'app名称_'.$j;
                $num = rand(1000,9999);
                $sql .= "('$statdate','$terminal','$appid','$appname',$num,$num,$num,$num,$num,$num),";
            }
            $sql = rtrim($sql,',');
            $app_monitor->query($sql);
        }

    }



public function map()
{
$html =<<< HTML
	$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column',
            margin: [ 50, 50, 100, 80]
        },
        title: {
            text: 'Worlds largest cities per 2008'
        },
        xAxis: {
            categories: [
                'Tokyo',
                'Jakarta',
                'New York',
                'Seoul',
                'Manila',
                'Mumbai',
                'Sao Paulo',
                'Mexico City',
                'Dehli',
                'Osaka',
                'Cairo',
                'Kolkata',
                'Los Angeles',
                'Shanghai',
                'Moscow',
                'Beijing',
                'Buenos Aires',
                'Guangzhou',
                'Shenzhen',
                'Istanbul'
            ],
            labels: {
                rotation: -45,
                align: 'right',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Population (millions)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>',
        },
        series: [{
            name: 'Population',
            data: [34.4, 21.8, 20.1, 20, 19.6, 19.5, 19.1, 18.4, 18,
                17.3, 16.8, 15, 14.7, 14.5, 13.3, 12.8, 12.4, 11.8,
                11.7, 11.2],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                x: 4,
                y: 10,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif',
                    textShadow: '0 0 3px black'
                }
            }
        }]
    });
});

HTML;

echo $html;
}



	




	public function hello($arg=4555)
	{
		sleep(1) ;
		$this->load->model("Login_model") ;
		$this->Login_model->check_login();

		$data = array(	'2014-04-05'	=>	array(	'count'	=>	14460,
													'xxxx'	=>	20,
													'aaa'	=> "10"),
						'2014-04-06'	=>	array(	'count'	=>	010,
													'xxxx'	=>	20330,
													'aaa'	=>	"10"),
						'2014-04-07'	=>	array(	'count'	=>	150,
													'xxxx'	=>	250,
													'aaa'	=>	"10"),
						'2014-04-08'	=>	array(	'count'	=>	14430,
													'xxxx'	=>	230,
													'aaa'	=>	"10"),
						'2014-04-09'	=>	array(	'count'	=>	14430,
													'xxxx'	=>	240,
													'aaa'	=>	"10"),
						'2014-04-10'	=>	array(	'count'	=>	010,
													'xxxx'	=>	20540,
													'aaa'	=>	"10"),
						'2014-04-11'	=>	array(	'count'	=>	150,
													'xxxx'	=>	250,
													'aaa'	=>	"10"),
						'2014-04-12'	=>	array(	'count'	=>	13330,
													'xxxx'	=>	230,
													'aaa'	=>	"10"),
						'2014-04-13'	=>	array(	'count'	=>	140,
													'xxxx'	=>	240,
													'aaa'	=>	"10"),
						'2014-04-14'	=>	array(	'count'	=>	010,
													'xxxx'	=>	200,
													'aaa'	=>	"10"),
						'2014-04-15'	=>	array(	'count'	=>	150,
													'xxxx'	=>	250,
													'aaa'	=>	"10"),
						'2014-04-16'	=>	array(	'count'	=>	130,
													'xxxx'	=>	230,
													'aaa'	=>	"10"),
						'2014-04-17'	=>	array(	'count'	=>	140,
													'xxxx'	=>	240,
													'aaa'	=>	"10"),
					) ;
		$keywords = array(	'count'		=>	'数量',
							'xxxx'		=>	'FCK',
							'aaa'		=> 'test') ;
		$js = make_hicharts($data,$keywords) ;
		
		$js_data = $js[0] ;
		
		$js_date = $js[1] ;


		echo '<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
				<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		' ;
		echo "
			<script language='javascript'>
			\$(function () {
				$('#container').highcharts({
					title: {
						text: 'Monthly Average Temperature',
						x: -20 //center
					},
					xAxis: {
						categories: $js_date
					},
					yAxis: {
						title: {
							text: ' '
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
						formatter: function() {
							return '<b>'+ this.series.name +'</b><br/>'+
								this.x +': '+ Highcharts.numberFormat(this.y, 0, ',')  ;
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						borderWidth: 0
					},
					series: $js_data
				});
			});






\$(function () {
    $('#container1').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Average Monthly Temperature and Rainfall in Tokyo'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: [{
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}°C',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Temperature',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Rainfall',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} mm',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'Rainfall',
            type: 'spline',
            yAxis: 1,
            data: [1149.9, 1171.5, 11106.4, 1129.2, 1144.0, 1176.0, 1135.6, 148.5, 216.4, 194.1, 95.6, 514.4],
            tooltip: {
                valueSuffix: ' mm'
            }

        }, {
            name: 'Temperature',
            type: 'spline',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
            tooltip: {
                valueSuffix: '°C'
            }
        }]
    });
});
			</script>
		" ;
		$this->Login_model->test() ;
		dump($this->Login_model->err_msg,'red') ;
		dump($this->Login_model->username,'blue') ;								// 加载了权限模块后，就能显示出该登录的用户的用户名
		dump($this->Login_model->login,'green');								// 是否登录标记
		dump($this->Login_model->gameids,'yellow') ;							// 数组；就是用户拥有的游戏权限，如果==0；就是没有限制游戏

		dump($this->Login_model->get_files(),'red') ;							// 用户拥有的文件访问列表，通常放在左边做列表显示的
		dump($this->Login_model->group_files,'green') ;
		
		dump($_SERVER,'red');
		$url = $_SERVER["PATH_INFO"] ;

		echo "
		<script type=\"text/javascript\">count('$url');</script>
		<div class=\"pv-box\" id=\"count\"></div>
		" ;
		// $this->Login_model->get_log($url) ;

	}
	public function hello1()
	{
		$this->load->model("Login_model") ;
		$this->Login_model->check_login();
		# 默认数据
		if(!$_POST)
		{
			$_POST = array(	'startdate'		=>	'2015-04-04',
							'enddate'		=>	'2015-04-30',
							'is_ajax'		=>	1,
							'download'		=>	0,
							'gameid'		=>	1060111,
							'xxxx'			=>	'xxxx',
							'persize'		=>	10,
					) ;
		}


		# 接数据
		$startdate = isset($_POST['startdate']) ? trim($_POST['startdate']) : date("Y-m-d",time()-3600*24*30) ;
		$enddate = isset($_POST['enddate']) ? trim($_POST['enddate']) : date("Y-m-d") ;
		$is_ajax = isset($_POST['is_ajax']) ? (int)($_POST['is_ajax']) : 1 ;
		$download = isset($_POST['download']) ? (int)($_POST['download']) : 0 ;
		$gameid = isset($_POST['gameid']) ? (int)($_POST['gameid']) : 0 ;
		$persize = (int)($_POST['persize']) ;

		# 校验
		if($persize<=0 || $persize>=100)
			$persize = 10 ;


		$data = array(	'2014-04-05'	=>	array(	'count'	=>	10,
													'xxxx'	=>	20,
													'aaa'	=> "10"),
						'2014-04-06'	=>	array(	'count'	=>	010,
													'xxxx'	=>	200,
													'aaa'	=>	"10"),
				) ;
		$this->smartytpl->assign('data',$data) ;
		$this->smartytpl->assign('statdate',$statdate) ;
		// $this->smartytpl->display('test5.tpl');
	}







	public function phpinfo()
	{
		phpinfo() ;
	}
	public function hello2()
	{
		/*$in = array(	'platform_id'	=>	array('005')
				);
		$status = 5 ;								//  status =-2 全部，-3全部审批了额，-4全部没审批完成 ;
		$order = 'flowid' ;
		$sort = 'desc' ;
		$offset = 0 ;
		$pagesize = 10 ;
		$download = 0 ;
		$this->load->model("Test_model") ;
		$ret = $this->Test_model->get_data($in,$status,$order,$sort,$offset,$pagesize,$download) ;
		dump($ret) ;
		
		$download = 1 ;
		$status = -4 ;
		$ret = $this->Test_model->get_data($in,$status,$order,$sort,$offset,$pagesize,$download) ;
		dump($ret) ;

		$download = 1 ;
		$status = -3 ;
		$ret = $this->Test_model->get_data($in,$status,$order,$sort,$offset,$pagesize,$download) ;
		dump($ret) ;
		die ;*/
		/*
		$url = $_SERVER["PATH_INFO"] ;
		$file_info = $this->Login_model->get_file_info_by_path($url) ;					// 获取该文件入口的基本信息
		$per_files = $this->Login_model->get_files() ;									// 获取该用户拥有的文件权限
		dump($per_files) ;
		dump($file_info) ;
		if(!$file_info)
		{
			exit("文件路径数据库里没配置") ;
		}
		if(!$per_files)
		{
			exit("无文件权限啊") ;
		}
		$group_ids = json_decode($file_info['group_ids'],true) ;
		dump($group_ids) ;																// 输出该组下面要列出的权限组文件
		foreach($per_files as $key =>$val)
		{
			//if($val['show']==0)															// 不显示，直接鄙视
			//	continue ;
			if(!in_array($val['id'],$group_ids))										// 该文件不属于该组的文件
				continue ;
			$filename = $val['filename'] ;
			echo "$filename<br>\n" ;													// 输出文件
		}
		dump("Login_model->gameids") ;
		dump($this->Login_model->gameids) ;
		*/

		$this->load->model("Test_model") ;
		$config = $this->Test_model->get_game_config() ;
		//dump($config) ;
		$today_data = $this->Test_model->get_realtime_data(array(11,22,33),'2015-06-03 10:40:00') ;
		//dump($today_data) ;
		$today_data1 = $this->Test_model->do_make_data($today_data,$config) ;
		


		$yesterday_data = $this->Test_model->get_realtime_data(array(11,22,33),'2015-06-03 10:40:00') ;
		$yesterday_data1 = $this->Test_model->do_make_data($yesterday_data,$config) ;
		/*
		$return = array('today'	=>	$today_data,
						'yes'	=>	$yesterday_data,
						'config'=>	$config ,
						) ;
		echo json_encode($return) ;
		die ;
		*/
		include("./application/views/test.php");
		die ;
		foreach($today_data1 as $leibie=>$leibie_var)
		{
			if($leibie == 'total')
			{
				// 运算下 total
				continue ;
			}
			foreach($leibie_var as $source=>$source_var)
			{
				continue ;
				foreach($source_var as $gameid=>$gameid_var)
				{
					if($gameid=='total')
						continue ;
					$today_money = $gameid_var['total_today_money'] ;
					$yesterday_money = isset($yesterday_data1[$leibie][$source][$gameid]['total_today_money'])?$yesterday_data1[$leibie][$source][$gameid]['total_today_money']:0 ;
					if($yesterday_money>0)
						$percent = ($today_money-$yesterday_money)/$yesterday_money ;
					else
						$percent = 0 ;
					echo "" ;
				}
			}
		}
	}
	public function monitor()
	{
		include("./application/views/templates/monitor.php");
	}
	public function testuser()
	{
		$username = 'testuser' ;
		if(!$this->Login_model->_login($username))
		{
			echo $this->Login_model->err_msg ;
			die("err") ;
		}
		header("location:/") ;
	}
	public function login_v1()
	{	
		if(!isset($_GET['username']))
		{
			echo "
				用户		部门		权限		组权限ID	权限ID<br>
				<a href='?username=test1' target=_blank>test1		部门A		提单		3		1</a><br>
				<a href='?username=test2' target=_blank>test2		部门A		部门审核	7		2</a><br>
				<a href='?username=test3' target=_blank>test3		部门B		提单		3		1	</a><br>
				<a href='?username=test4' target=_blank>test4		部门B		部门审核	7		2	</a><br>
				<a href='?username=test5' target=_blank>test5		数据部		数据部审批权限	4		3</a><br>
				<a href='?username=test6' target=_blank>test6		财务部		财务部审批	2		4</a><br>
				<a href='?username=test7' target=_blank>test7		盖章		盖章审核	5		5</a><br>
			" ;
			exit() ;
		}
		$username = trim($_GET['username']) ;
		if(!$this->Login_model->_login($username))
		{
			echo $this->Login_model->err_msg ;
			die("err") ;
		}
		// exit('haha') ;
		header("location:/") ;
	}
	/*public function __destruct()
	{
		dump("public function __destruct()");
	}*/
	public function i()
	{
		$this->db = $this->load->database('config', TRUE); 
		$sql = "SELECT `gameid`,`leibie` FROM `config_game`" ;
		$rows = $this->db->query($sql)->result_array() ; 
		$ret = array() ;
		foreach($rows as $key =>$val)
		{
			$gameid = $val['gameid'] ;
			$leibie = $val['leibie'] ;
			if(!isset($ret[$leibie]))
				$ret[$leibie] = array() ;
			$ret[$leibie][] = $gameid ;
		}
		foreach($ret as $leibie =>$val)
		{
			echo $leibie."<br>\n" ;
			echo implode(',',$val) ;
			echo "<br><br>\n\n" ;
		}
	}
	public function finance()
	{
		$this->db = $this->load->database('finance', TRUE); 
		$orderid = isset($_GET['orderid']) ? (int)($_GET['orderid']) : 1 ;
		$tostatus = isset($_GET['tostatus']) ? (int)($_GET['tostatus']) : 1 ;
		$this->load->model("finance/Finance_model") ;
		$remarks = "haha ddd" ;
		$username = $this->Login_model->username ;
		$this->db->query("begin") ;
		if(!$this->Finance_model->do_examine($username,$orderid,$tostatus,$remarks))
		{
			echo $this->Finance_model->err_msg ;
			echo "err<br> \n\n" ;
			$this->db->query("rollback") ;
		}
		else
		{
			echo "OK" ;
			$this->db->query("commit")  ;
		}
	}
	public function event()
	{
		$path = dirname(dirname(__FILE__)) ;
		include("$path/views/templates/test6.tpl");
	}
	public function app_test()
	{
		$this->load->model("Test_model") ;
		$this->Test_model->app_ttt() ;
	}
}
