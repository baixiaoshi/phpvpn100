<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="/application/views/js/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/application/views/js/common.js"></script>

    <link rel="stylesheet" type="text/css" href="/application/views/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/application/views/js/bootstrap/css/bootstrap-theme.min.css">
    <meta charset="UTF-8" />

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
        .box .username{
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
        .box .login_btn{
            position: relative;
            top: 90px;
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
        #daxiang{
            width: 80px;
            position: relative;
            left: 150px;
        }
        .mydomain{
            width: 100%;
            height: 60px;
            text-align: center;
            color: #FFF;
            font-size: 80px;
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
</head>
<body>
    <div class="mydomain"><a href="/" >phpvpn100</a></div>
    <div class="box">
        
        <div class="logo"><a href="/" ><img id="daxiang" src="http://7i7iur.com1.z0.glb.clouddn.com/php.png" /></a></div>
        <div class="login_form">
            <input type="text" class="form-control username" placeholder="邮箱/手机号" name="username">
            <input type="password" class="form-control password" placeholder="密码" name="password">
            <p class="tit"></p>
            <button type="button" class="btn btn-default btn-lg btn-block login_btn">登入</button>
        </div>

    </div>

    <script type="text/javascript">
        
        $('.login_btn').click(function() {
            var username = $('input[name="username"]').val();
            var password = $('input[name="password"]').val();
            if (username === '') {
                $('.tit').html('邮箱或手机号不能为空!');
                return false;
            }
            var len = username.toString().length;

            if (len > 30) {
                $('.tit').html('邮箱或手机不能大于30个字符!!!');
                return false;
            }


            var reg = /^[0-9a-zA-Z@\.]*$/i;
            
            if (reg.test(username) === false) {
                $('.tit').html('只允许数字和字母!!!');
                return false;
            }
            $('.tit').html('');
            $.post(
                "/home/login",
                {username:username,password:password},
                function(data) {
                    if (data['code'] == 0) {
                        window.location.href = '/home/index';
                    } else {
                        $('.tit').html('登入失败');
                    }
                },
                'json'
            );
        });


    </script>
</body>
</html>