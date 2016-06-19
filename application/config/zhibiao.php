<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$table_xy = "
(	SELECT 
		`startdate`,
		`gameid`,
		`versionid`,
		`show_num`,
		`download_num`,
		`install_num`,
		`new_user`,
		`new_ip`,
		`active_num`	,
		`active_ip`,
		`connect_num`,
		`nonconnect_num`,
		`new_connect_num`,
		`start_num`,
		`new_start_num`,
		`uninstall_user`,
		`new_uninstalluser`,
		`total_download`,
		`total_installnum`,
		`total_activem`,
		`total_ipnum`,
		`total_uninstallnum` ,
		0 AS `pay_money`,
		0 AS `pay_user`,
		0 AS `pay_ip`,
		0 AS `new_paynum`,
		0 AS `new_payip`,
		0 AS `total_paymoney`,
		0 AS `total_payuser`,
		0 AS `unlostnum`,
		0 AS `lostnum` 
	FROM `xyzs_kpi_rpt`
	UNION ALL
	SELECT 
		`t2`.`startdate`,
		`t2`.`gameid`,
		'all' AS `versionid`,
		`t1`.`show_num`,
		`t1`.`download_num`,
		`t1`.`install_num`,
		`t1`.`new_user`,
		`t1`.`new_ip`,
		`t1`.`active_num`	,
		`t1`.`active_ip`,
		`t1`.`connect_num`,
		`t1`.`nonconnect_num`,
		`t1`.`new_connect_num`,
		`t1`.`start_num`,
		`t1`.`new_start_num`,
		`t1`.`uninstall_user`,
		`t1`.`new_uninstalluser`,
		`t1`.`total_download`,
		`t1`.`total_installnum`,
		`t1`.`total_activem`,
		`t1`.`total_ipnum`,
		`t1`.`total_uninstallnum`,
		`t2`.`pay_money`,
		`t2`.`pay_user`,
		`t2`.`pay_ip`,
		`t2`.`new_paynum`,
		`t2`.`new_payip`,
		`t2`.`total_paymoney`,
		`t2`.`total_payuser`,
		`t2`.`unlostnum`,
		`t2`.`lostnum`
	FROM 
	(
		SELECT	`startdate`,
			SUM(`show_num`) AS `show_num`,
			SUM(`download_num`) AS `download_num`,
			SUM(`install_num`) AS `install_num`,
			SUM(`new_user`) AS `new_user`,
			SUM(`new_ip`) AS `new_ip`,
			SUM(`active_num`) AS `active_num`	,
			SUM(`active_ip`) AS `active_ip`,
			SUM(`connect_num`) AS `connect_num`,
			SUM(`nonconnect_num`) AS `nonconnect_num`,
			SUM(`new_connect_num`) AS `new_connect_num`,
			SUM(`start_num`) AS `start_num`,
			SUM(`new_start_num`) AS `new_start_num`,
			SUM(`uninstall_user`) AS `uninstall_user`,
			SUM(`new_uninstalluser`) AS `new_uninstalluser`,
			SUM(`total_download`) AS `total_download`,
			SUM(`total_installnum`) AS `total_installnum`,
			SUM(`total_activem`) AS `total_activem`,
			SUM(`total_ipnum`) AS `total_ipnum`,
			SUM(`total_uninstallnum`) AS `total_uninstallnum` 
		FROM `xyzs_kpi_rpt` 
		WHERE `gameid` IN(1117001,1117002,1117003)
		 GROUP BY `startdate`
	) 
	AS `t1`,`xyzs_kpi_rpt1` as `t2`
	WHERE `t1`.`startdate` = `t2`.`startdate`

	UNION ALL
	SELECT 
		`startdate`,
		`gameid`,
		'all' AS `versionid`,
		SUM(`show_num`) AS `show_num`,
		SUM(`download_num`) AS `download_num`,
		SUM(`install_num`) AS `install_num`,
		SUM(`new_user`) AS `new_user`,
		SUM(`new_ip`) AS `new_ip`,
		SUM(`active_num`) AS `active_num`	,
		SUM(`active_ip`) AS `active_ip`,
		SUM(`connect_num`) AS `connect_num`,
		SUM(`nonconnect_num`) AS `nonconnect_num`,
		SUM(`new_connect_num`) AS `new_connect_num`,
		SUM(`start_num`) AS `start_num`,
		SUM(`new_start_num`) AS `new_start_num`,
		SUM(`uninstall_user`) AS `uninstall_user`,
		SUM(`new_uninstalluser`) AS `new_uninstalluser`,
		SUM(`total_download`) AS `total_download`,
		SUM(`total_installnum`) AS `total_installnum`,
		SUM(`total_activem`) AS `total_activem`,
		SUM(`total_ipnum`) AS `total_ipnum`,
		SUM(`total_uninstallnum`) AS `total_uninstallnum` ,
		0 AS `pay_money`,
		0 AS `pay_user`,
		0 AS `pay_ip`,
		0 AS `new_paynum`,
		0 AS `new_payip`,
		0 AS `total_paymoney`,
		0 AS `total_payuser`,
		0 AS `unlostnum`,
		0 AS `lostnum` 
	FROM `xyzs_kpi_rpt` 
	GROUP BY `startdate`,`gameid` ) AS t";

$config = array(
	'kpi_type'	=>	array(
		1	=>	'官网',
		2	=>	'新用户',
		3 	=>	'活跃',
		4	=>	'付费',
		5	=>	'流失',
		6	=>	'总量'

		),
	#键=projectid
	'kpi'	=>	array(

			1	=>	array(
				'show_num'	=>	array(
						'name'	=>	'展示量',
						'field'	=>	'show_num',
						'default_show'	=>	0,	//是否默认展示到前台  1=展示，0不展示
						'kpi_type'	=>	1,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'download_num'	=>	array(
						'name'	=>	'下载量',
						'field'	=>	'download_num',
						'default_show'	=>	1,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
	
				'install_num'	=>	array(
						'name'	=>	'安装量',
						'field'	=>	'install_num',
						'default_show'	=>	1,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'install_rate'	=>	array(
						'name'	=>	'安装成功率',
						'field'	=>	'(install_num/download_num)*100 AS install_rate',
						'default_show'	=>	0,
						'kpi_type'		=>	2,
						'format'	=>	2,
						'suffix'	=>	'%'
					),
				'add_install_num'	=>	array(
						'name'	=>	'净增安装量',
						'field'	=>	'(install_num - new_uninstalluser) AS add_install_num',
						'default_show'	=>	0,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_user'	=>	array(
						'name'	=>	'新登设备数',
						'field'	=>	'new_user',
						'default_show'	=>	0,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_ip'	=>	array(
						'name'	=>	'新登IP',
						'field'	=>	'new_ip',
						'default_show'	=>	0,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'active_num'	=>	array(
						'name'	=>	'活跃设备数',
						'field'	=>	'active_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'active_ip'	=>	array(
						'name'	=>	'活跃IP',
						'field'	=>	'active_ip',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'connect_num'	=>	array(
						'name'	=>	'用户连接数',
						'field'	=>	'connect_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),

				'nonconnect_num'	=>	array(
						'name'	=>	'未连接用户数',
						'field'	=>	'(active_num-connect_num) AS nonconnect_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				
				'new_connect_num'	=>	array(
						'name'	=>	'新用户连接数',
						'field'	=>	'new_connect_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'start_num'	=>	array(
						'name'	=>	'启动次数',
						'field'	=>	'start_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'old_active'	=>	array(
						'name'	=>	'去新活跃设备数',
						'field'	=>	'(active_num-new_user) AS old_active',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_start_num'	=>	array(
						'name'	=>	'新增用户启动次数',
						'field'	=>	'new_start_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'old_start_num'	=>	array(
						'name'	=>	'去新启动次数',
						'field'	=>	'(start_num-new_start_num) AS old_start_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'activerate'	=>	array(
						'name'	=>	'天活跃率',
						'field'	=>	'(active_num /total_installnum)*100 AS activerate',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	2,
						'suffix'	=>	'%'
					),
				'pay_money'	=>	array(
						'name'	=>	'充值额',
						'field'	=>	'pay_money',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	2,
						'suffix'	=>	''
					),
				'pay_user'	=>	array(
						'name'	=>	'充值设备数',
						'field'	=>	'pay_user',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'pay_ip'	=>	array(
						'name'	=>	'充值IP',
						'field'	=>	'pay_ip',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'		=>	0,			
						'suffix'		=>	'',		
					),
				'new_paynum'	=>	array(
						'name'	=>	'新增充值设备数',
						'field'	=>	'new_paynum',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_payip'	=>	array(
						'name'	=>	'新增充值IP',
						'field'	=>	'new_payip',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_payip'	=>	array(
						'name'	=>	'新增充值IP',
						'field'	=>	'new_payip',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	0,
						'suffix'	=>	''
					),

				'arpu'	=>	array(
						'name'	=>	'付费设备平均付费',
						'field'	=>	'(pay_money/pay_user) AS arpu',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	2,
						'suffix'	=>	''
					),
				'arpu1'	=>	array(

						'name'	=>	'活跃设备平均付费',
						'field'	=>	'(pay_money/active_num) AS arpu1',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	2,
						'suffix'	=>	''
					),
				
				'payrate'	=>	array(
						'name'	=>	'充值转化率',
						'field'	=>	'(pay_user/active_num)*100 AS payrate',
						'default_show'	=>	0,
						'kpi_type'		=>	4,
						'format'	=>	2,
						'suffix'	=>	'%'
					),

				#流失指标
				'uninstall_user'	=>	array(
						'name'	=>	'卸载量',
						'field'	=>	'uninstall_user',
						'default_show'	=>	0,
						'kpi_type'		=>	5,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'new_uninstalluser'	=>	array(
						'name'	=>	'新装卸载量',
						'field'	=>	'new_uninstalluser',
						'default_show'	=>	0,
						'kpi_type'		=>	5,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'unlostnum'	=>	array(
						'name'	=>	'潜在流失设备数',
						'field'	=>	'unlostnum',
						'default_show'	=>	0,
						'kpi_type'		=>	5,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'lostnum'	=>	array(
						'name'	=>	'流失设备数',
						'field'	=>	'lostnum',
						'default_show'	=>	0,
						'kpi_type'		=>	5,
						'format'	=>	0,
						'suffix'	=>	''
					),

				#总量
				'total_paymoney'	=>	array(
						'name'	=>	'累计充值金额',
						'field'	=>	'total_paymoney',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	2,
						'suffix'	=>	''
					),
				'total_payuser'	=>	array(
						'name'	=>	'累计充值设备数',
						'field'	=>	'total_payuser',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'total_download'	=>	array(
						'name'	=>	'累计下载量',
						'field'	=>	'total_download',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'total_installnum'	=>	array(
						'name'	=>	'累计安装量',
						'field'	=>	'total_installnum',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'total_activem'	=>	array(
						'name'	=>	'累计设备数',
						'field'	=>	'total_activem',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'total_ipnum'	=>	array(
						'name'	=>	'累计IP数',
						'field'	=>	'total_ipnum',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'total_uninstallnum'	=>	array(
						'name'	=>	'累计卸载量',
						'field'	=>	'total_uninstallnum',
						'default_show'	=>	0,
						'kpi_type'		=>	6,
						'format'	=>	0,
						'suffix'	=>	''
					)

				

				),
			2	=>	array(
				'show_num'	=>	array(
						'name'	=>	'展示量',
						'field'	=>	'show_num',
						'default_show'	=>	0,	//是否默认展示到前台  1=展示，0不展示
						'kpi_type'		=>	1,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'download_num'	=>	array(
						'name'	=>	'下载量',
						'field'	=>	'download_num',
						'default_show'	=>	1,
						'kpi_type'		=>	2,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'active_ip'	=>	array(
						'name'	=>	'活跃IP',
						'field'	=>	'active_ip',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					),
				'connect_num'	=>	array(
						'name'	=>	'用户连接数',
						'field'	=>	'connect_num',
						'default_show'	=>	0,
						'kpi_type'		=>	3,
						'format'	=>	0,
						'suffix'	=>	''
					)
				
				),
			#XY助手
			'1117124'	=>	array('show_num','download_num','install_num','install_rate','new_user','new_ip','active_num','old_active','active_ip','connect_num','nonconnect_num','new_connect_num','start_num','new_start_num','old_start_num','activerate','pay_money','pay_user','pay_ip','new_paynum','new_payip','arpu','arpu1','payrate','uninstall_user','new_uninstalluser','unlostnum','lostnum','total_paymoney','total_payuser','total_download','total_installnum','total_activem','total_ipnum','total_uninstallnum'),
			#手机版
			'1117001'	=>	array('show_num','download_num','install_num','install_rate','new_user','new_ip','active_num','old_active','active_ip','start_num','new_start_num','old_start_num','activerate','total_download','total_installnum','total_activem'),
			#pc版本
			'1117002'	=>	array('show_num','download_num','install_num','install_rate','add_install_num','new_user','new_ip','active_num','old_active','active_ip','connect_num','nonconnect_num','new_connect_num','start_num','new_start_num','old_start_num','activerate','uninstall_user','new_uninstalluser','total_installnum','total_activem'),
			#越狱版
			'1117003'	=>	array('new_user','new_ip','active_num','old_active','active_ip','start_num','new_start_num','old_start_num','activerate','total_activem'),
			#笨方法配置的字段
			'config_total_v1'	=>	array(
						'active_num'=>'active_num',
						'pay_user'=>'pay_user',
						'payrate'=>'(pay_user/active_num)*100 AS `payrate`'
						),
			'config_total_v2'	=>	array(
						'show_num'			=>	'SUM(`show_num`) AS `show_num`',
						'download_num'		=>	'SUM(`download_num`) AS `download_num`',
						'install_num'		=>	'SUM(`install_num`) AS `install_num`',
						'install_rate'		=>	'(SUM(`install_num`)/SUM(`download_num`))*100 AS `install_rate`',
						'add_install_num'	=>	'(SUM(`install_num`)-SUM(`uninstall_user`)) AS `add_install_num`',
						'new_user'			=>	'SUM(`new_user`) AS new_user',
						'new_ip'			=>	'SUM(`new_ip`) AS new_ip',
						'connect_num'		=>	'SUM(`connect_num`) AS `connect_num`',
						'nonconnect_num'	=>	'(SUM(`active_num`) - SUM(`connect_num`)) AS nonconnect_num',
						'new_connect_num'	=>	'SUM(`new_connect_num`) AS `new_connect_num`',
						'start_num'			=>	'SUM(`start_num`) AS `start_num`',
						'new_start_num'		=>	'SUM(`new_start_num`) AS `new_start_num`',
						'old_start_num'		=>	'(SUM(`start_num`)-SUM(`new_start_num`)) AS `old_start_num`',
						/*'activerate'		=>	'(SUM(`start_num`)-SUM(`total_installnum`)) AS `activerate`','(active_num /total_installnum)*100 AS activerate',*/
						'pay_money'			=>	'SUM(`pay_money`) AS `pay_money`',
						'new_paynum'		=>	'SUM(`new_paynum`) AS `new_paynum`',
						'new_payip'			=>	'SUM(`new_payip`) AS `new_payip`',
						'arpu'				=>	'(SUM(`pay_money`)/SUM(`pay_user`)) AS `arpu`',/*sum(pay_money/pay_user)*/
						'arpu1'				=>	'(SUM(`pay_money`)/SUM(`active_num`)) as `arpu1`',/*SUM(`pay_money`)/active_num)*/
						'uninstall_user'	=>	'SUM(`uninstall_user`) AS `uninstall_user`',
						'new_uninstalluser'	=>	'SUM(`new_uninstalluser`) AS `new_uninstalluser`'
						/*'total_installnum'	=>	'max(`total_installnum`) AS `total_installnum`',*/
				),	
			# 下面这个能做跨表操作的，下面计算的是基本指标，如果存在跨表计算的，就在下面的 config_total_v3_fields 做计算
			'config_total_v3'	=>	array(																	
						'show_num'			=>	'SUM(`show_num`) AS `show_num`',
						'download_num'		=>	'SUM(`download_num`) AS `download_num`',
						'install_num'		=>	'SUM(`install_num`) AS `install_num`',
						'install_rate'		=>	'(SUM(`install_num`)/SUM(`download_num`))*100 AS `install_rate`',
						'add_install_num'	=>	'(SUM(`install_num`)-SUM(`uninstall_user`)) AS `add_install_num`',
						'new_user'			=>	'SUM(`new_user`) AS new_user',
						'new_ip'			=>	'SUM(`new_ip`) AS new_ip',
						'connect_num'		=>	'SUM(`connect_num`) AS `connect_num`',
						'nonconnect_num'	=>	'(SUM(`active_num`) - SUM(`connect_num`)) AS nonconnect_num',
						'new_connect_num'	=>	'SUM(`new_connect_num`) AS `new_connect_num`',
						'start_num'			=>	'SUM(`start_num`) AS `start_num`',
						'new_start_num'		=>	'SUM(`new_start_num`) AS `new_start_num`',
						'old_start_num'		=>	'(SUM(`start_num`)-SUM(`new_start_num`)) AS `old_start_num`',
						'pay_money'			=>	'SUM(`pay_money`) AS `pay_money`',
						'new_paynum'		=>	'SUM(`new_paynum`) AS `new_paynum`',
						'new_payip'			=>	'SUM(`new_payip`) AS `new_payip`',
						'uninstall_user'	=>	'SUM(`uninstall_user`) AS `uninstall_user`',
						'new_uninstalluser'	=>	'SUM(`new_uninstalluser`) AS `new_uninstalluser`',
					),	
			# 下面是涉及到了跨表运算指标计算
			'config_total_v3_fields'		=>	array(
						'arpu'				=>	'`pay_money`/`pay_user` AS `arpu`',
						'arpu1'				=>	'`pay_money`/`active_num` AS `arpu1`',
				),
		),
	#键=projectid
	'table'	=>	array(
			1	=>	$table_xy,
			2	=>	$table_xy
		),
	'platforms'	=>	array(
				1	=>	array(
					'platform_id'	=>	1,
					'platform_name'	=>	'XY助手'
					)
		),
	'data_type'	=>	array('day','month','quarter','year')
	


	);
