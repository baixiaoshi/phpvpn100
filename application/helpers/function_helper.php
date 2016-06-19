<?php

function ret_msg($code, $message = '', $data = array()) {
	$data = array(
		'code' => $code,
		'msg' => $message,
		'data' => $data
		);
	exit(json_encode($data));

}

//是否在页面上展示sql语句
function show_sql($sql)
{	
	echo '<!--' ;
	echo 'sql:'.$sql.'<br/><br/>' ;
	echo '-->' ;
}


//对二维数组进行自定义排序
function myusort($array,$field,$order='desc')
{	
	if(!is_array($array) || !$array) return array();
	$sort_array = array();
	foreach($array as $k=>$v)
	{	
		if(!isset($v[$field]))
			$sort_array[] = '';
		else
			$sort_array[] = $v[$field];
	}

	if($order === 'desc')
	{
		array_multisort($sort_array,SORT_DESC,$array);
	}
	else
	{
		array_multisort($sort_array,SORT_ASC,$array);
	}
	return $array;
}


function array_sort_keep_key($array,$field,$type='asc')
{ 
	$keysvalue= $new_array= array(); 
	foreach($array as $k=>$v)
	{ 
		$keysvalue[$k] = $v[$field]; 
	} 
	if($type== 'asc')
	{ 
		asort($keysvalue); 
	}
	else
	{ 
		arsort($keysvalue); 
	}

	foreach($keysvalue as $k=>$v){ 
		$new_array[$k] = $array[$k]; 
	} 
	return$new_array; 
} 




function dump($args,$color=false)
{
	if($color)
		echo "<font color=$color>" ;
	echo "<pre>" ;
	print_r($args) ;
	echo "</pre>" ;
	if($color)
	echo "</font>" ;
}




/**
 * 获得用户操作系统的换行符
 *
 * @access  public
 * @return  string
 */
function get_crlf()
{	

    if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
        $the_crlf = "\r\n";
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
        $the_crlf = "\r"; // for old MAC OS
    else
        $the_crlf = "\n";
    return $the_crlf;
}

/**
 *  打印堆栈调用,用于调试跟踪
 * @return [type] [description]
 */
function print_stacks($is_detail=0)
{
	$stacks = debug_backtrace() ;
	$ret = array() ;
	foreach($stacks as $k=>$v)
	{
		$ret[$k]['file'] = isset($v['file']) ? $v['file'] :'--';
		$ret[$k]['line'] = isset($v['line']) ? $v['line'] :'--';
		$ret[$k]['function'] = isset($v['function']) ? $v['function'] :'--';
		if($is_detail == 1)
			$ret[$k]['args'] = isset($v['args']) ? $v['args'] :'--';
	}

	dump($ret) ;
}


function sms_send($user, $pass, $vcode, $mobile, $ttl = 60) {

	$statusStr = array(
        "0" => "短信发送成功",
        "-1" => "参数不全",
        "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "密码错误",
        "40" => "账号不存在",
        "41" => "余额不足",
        "42" => "帐户已过期",
        "43" => "IP地址限制",
        "50" => "内容含有敏感词"
    );
    $smsapi = "http://api.smsbao.com/";
    $user = $user; //短信平台帐号
    $pass = md5($pass); //短信平台密码
    $content="【phpvpn100.com】您的验证码是【{$vcode}】,{$ttl}秒过期";//要发送的短信内容
    $phone = $mobile;//要发送短信的手机号码
    $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
    $result_code =file_get_contents($sendurl) ;
    return $result_code;

}


function send_email($to_email, $title, $content) {
    require_once(APPPATH . 'libraries/Smtp.php');
    $mail = new MySendMail();
    $mail->setServer("smtp.qq.com", "634842632@qq.com", "gxpuieuhebyxbaie", 465, true); //设置smtp服务器，普通连接方式
    //$mail->setServer("smtp.gmail.com", "XXXXX@gmail.com", "XXXXX", 465, true); //设置smtp服务器，到服务器的SSL连接
    $mail->setFrom("634842632@qq.com"); //设置发件人
    $mail->setReceiver($to_email); //设置收件人，多个收件人，调用多次
    //$mail->setCc("XXXX"); //设置抄送，多个抄送，调用多次
    //$mail->setBcc("XXXXX"); //设置秘密抄送，多个秘密抄送，调用多次
    //$mail->addAttachment("XXXX"); //添加附件，多个附件，调用多次
    $mail->setMail($title, $content); //设置邮件主题、内容

    $mail->sendMail(); //发送
}

function gen_vcode($num) {
	if (!is_numeric($num)) {
		return '';
	}
	$vcode_str = '';
	for ($i=0; $i < $num; $i++) { 
		$vcode_str .= rand(1, 9);
	}
	return $vcode_str;
}


?>