<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="/application/views/js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/application/views/js/common.js"></script>

    <link rel="stylesheet" type="text/css" href="/application/views/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/application/views/js/bootstrap/css/bootstrap-theme.min.css">


    <style type="text/css">
        body{
            position: relative;
            left:0px;
            top:0px;
            width:100%;
            height: 100%;
            background-color:#cf4646;
        }
        .box{
            position: relative;
            width:400px;
            height: 300px;
            margin:100px auto;
            background-color:#FFF;
        }
        .box .mobile, .box .email{
            position: relative;
            top: 30px;
            width: 300px;
            margin: 0px auto;
        }
        .box .password {
            position: relative;
            top: 60px;
            width: 300px;
            margin: 0px auto;
        }
        .box .register_btn, .box .register_btn_email{
            position: relative;
            top: 40px;
            width: 300px;
            margin: 0px auto;
        }
        .tit{
            color:#F00;
            position: relative;
            top: 80px;
            left: 100px;
            height:20px;
        }
        .nav-tabs>li{
            position: relative;
            left:120px;
        }

        .mobile_vcode,.email_vcode{
            position: relative;
            width: 80px;
            top: 80px;
            left: 50px;
        }
        .mobile_code_btn,.email_vcode_btn{
            position: relative;
            top: 45px;
            left: 150px;
        }
        .mydomain{
            width: 100%;
            height: 40px;
            line-height: 50px;
            color: #FFF;
            text-align: center;
            vertical-align: middle;
            font-size: 40px;
            position: relative;
            top: 40px;
        }

        .mydomain a:link{
            color:#FFF;
            text-decoration: none;
        }
        .mydomain a:visited{
            color:#FFF;
            text-decoration: none;
        }
        .mydomain a:hover{
            color:#FFF;
            text-decoration: none;
        }
        .mydomain a:active{
            color:#FFF;
            text-decoration: none;
        }
    </style>
    <meta charset="UTF-8" />
</head>
<body>
     <div class="mydomain">
            <a href="/">phpvpn100</a>
    </div>
    <div class="box">
       

        <ul id="myTab" class="nav nav-tabs">
           <li class="active" id="tabone">
              <a href="#home" data-toggle="tab">
                 手机号
              </a>
           </li>
           <li><a href="#ios" data-toggle="tab">邮箱</a></li>
        </ul>



        <div id="myTabContent" class="tab-content">
           <div class="tab-pane fade in active" id="home">
                <div class="login_form">
                    <input type="text" class="form-control mobile" placeholder="手机号" name="mobile">
                    <input type="password" class="form-control password" placeholder="密码" name="mobile_password">
                    <input type="text" class="form-control mobile_vcode" placeholder="验证码" name="mobile_vcode">
                    <input type="button" class="btn btn-danger mobile_code_btn" onclick="check_ttl(this);" value="点击获取验证码" />
                    <p class="tit"></p>
                    <button type="button" class="btn btn-default btn-lg btn-block register_btn">注册</button>
                </div>
           </div>
           <div class="tab-pane fade" id="ios">
              <div class="login_form">
                    <input type="text" class="form-control email" placeholder="邮箱" name="email">
                    <input type="password" class="form-control password" placeholder="密码" name="email_password">
                    <input type="text" class="form-control email_vcode" placeholder="验证码" name="email_vcode">
                    <input type="button" class="btn btn-danger email_vcode_btn" onclick="check_ttl_v2(this);" value="点击获取验证码" />
                    <p class="tit"></p>
                    <button type="button" class="btn btn-default btn-lg btn-block register_btn_email">注册</button>
                </div>
           </div>

            

        </div>
    <div>
</div>


    <script type="text/javascript">
        
        $('.register_btn').click(function(){
            var mobile = $('input[name="mobile"]').val();
            var password = $('input[name="mobile_password"]').val();
            var vcode = $('input[name="mobile_vcode"]').val();

            var reg = /^\d{11}$/i;
            if (reg.test(mobile) === false) {
                alert('手机格式不对');
                return false;
            }
            if (password == '') {
                alert('密码不能为空');
                return false;
            }
            $.post(
                'register',
                {mobile:mobile,password:password,vcode:vcode},
                function(data){
                    if (data['code'] == 0) {
                        alert(data['msg']);
                        window.location.href="login";
                    } else {
                        alert(data['msg']);
                    }
                },
                'json'
                );

        });



        $('.register_btn_email').click(function(){
            var email = $('input[name="email"]').val();
            var password = $('input[name="email_password"]').val();
            var vcode = $('input[name="email_vcode"]').val();

            var reg = /@\w*\.com$/i;
            if (reg.test(email) === false) {
                alert('邮箱格式不对');
                return false;
            }
            if (password == '') {
                alert('密码不能为空');
                return false;
            }
            $.post(
                'register',
                {email:email,password:password,vcode:vcode},
                function(data){
                    if (data['code'] == 0) {
                        alert(data['msg']);
                        window.location.href="login";
                    } else {
                        alert(data['msg']);
                    }
                },
                'json'
                );

        });




        var ttl = 60;
        function settime(obj) {
            
            if (ttl == 0) {
                obj.removeAttribute('disabled');
                obj.value = '点击获取验证码';
                ttl = 60;
            } else {
                obj.setAttribute('disabled', true);
                obj.value = "重新发送" + ttl + "秒后";
                ttl --;
                setTimeout(function(){
                settime(obj)
                },1000);
            }
        }

        function check_ttl(obj) {

            var mobile = $('input[name="mobile"]').val();
             var reg = /^\d{11}$/i;
            if (reg.test(mobile) ===  false) {
                
                alert('手机格式不对');
                return false;
            }
           
            $.post(
                'send_msg',
                {mobile:mobile},
                function(data){
                     if (data['code'] != 0) {
                         alert(data['msg']);
                     } else {
                        alert(data['msg']);
                        settime(obj);
                    }
                },
                'json'
            )
        }

        function check_ttl_v2(obj) {

            var email = $('input[name="email"]').val();

           

            var reg = /@\w*\.com$/i;
            if (reg.test(email) ===  false) {
                
                alert('邮件格式不对');
                return false;
            }
            $.post(
                'send_mail',
                {email:email},
                function(data){
                     if (data['code'] != 0) {
                         alert(data['msg']);
                     } else {
                        alert(data['msg']);
                        settime(obj);
                    }
                },
                'json'
            )
        }

    </script>
</body>
</html>