<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
    body{
        margin:0;
        padding:0;
        border:0;
    }
    .head_box{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 60px;
        background-color: #cf4646;
        z-index: 1;
        box-shadow: 0px 0px 10px  0px #000;
    }
    .head_box .logo_text{
        position: relative;
        left: 10px;
        top: 10px;
        width: 200px;
        height: 40px;
        line-height: 40px;
        color: #fff;
        background-color: transparent;
        font-size:18px;
    }
    .register{
        float: right;
        position: relative;
        top: -35px;
        right: 200px;
        color: #FFF;
        font-size: 18px;
    }
    .login{
        float: right;
        position: relative;
        top: -35px;
        right: 110px;
        font-size: 18px;
        color: #FFF;
    }
    .body_box{
        position: relative;
        width:100%;
        height: 800px;
    }
    .body_box .phpimg_box {
        position: relative;
        width:100%;
        height: 300px;
        background-color:#cf4646;
        top:60px;
        box-shadow: 0px 0px 10px  0px #000;

    }
    .svndesc{
        position: relative;
        width: 100%;
        height: 30px;
        line-height: 30px;
        text-align: center;
        top: -100px;
        font-size: 24px;
        color: #FFF;
    }
    .php_icon{
        position: relative;
        width: 100px;
        height: 100px;
        overflow: hidden;
        margin: 0px auto;
        top: -390px;
        z-index: 2;
    }
    .php_icon img{
        width:100px;
    }
    .tab_box {
        width: 80%;
        margin: 0 auto;
        height: 470px;
    }
    .tab_box .tab{
        float: left;
        width: 47.9%;
        height: 150px;
        margin: 10px 5px;

        position: relative;
        top: 100px;
    }

    .tab_box .tab .keyword{
        width:100%;
    }

    .tab_box .tab .keyword_desc{
        width:100%;
    }


    .loginout_btn{
        position: relative;
        right: -200px;
    }

    .pay_box{
        clear: both;
        position: relative;
        width:100%;
        height: 300px;
        text-align: center;
    }

    .pay_box .pay_img{
        position: relative;
        width:700px;
        height: 300px;
        margin:0px auto;

    }

    .pay_box .pay_img .pay_btn{
        float:left;

    }

    .footer_box{
        position: relative;
        top:200px;
        border:1px solid #F00;
        clear: both;
        width:100%;
        height: 200px;
        background-color:#cf4646;
        text-align: center;
        box-shadow: 10px 0px 10px  0px #000;
    }
    .qq_qun{
        margin:0px auto;
        position: relative;
        top:90px;
        width:400px;
        height: 40px;
        vertical-align: middle;
        line-height: 40px;
        color:#FFF;
        font-size:30px;
    }

         a:link{
            color:#FFF;
            text-decoration: none;
        }
        a:visited{
            color:#FFF;
            text-decoration: none;
        }
        a:hover{
            color:#FFF;
            text-decoration: none;
        }
        a:active{
            color:#FFF;
            text-decoration: none;
        }
    </style>
    <meta charset="UTF-8" />
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>

<div class="head_box">
    <div class="logo_text">
        <span><a href="/">www.phpvpn100.com</a></span>
    </div>

    <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])):?>
    <div class="register">
        <p>欢迎 ： <a  class="indexlink" id="userinfo" style="color:#FFF; text-decoration:none;cursor:pointer;"><?php echo $_SESSION['username'] ?></a></p>
    </div>
    <div class="login">
        <p><a href="/home/logout" class="indexlink loginout_btn" style="color:#FFF; text-decoration:none;cursor:pointer;">退出</a></p>
    </div>
    <?php else: ?>
    <div class="register">
        <p><a href="/home/register" class="indexlink" style="color:#FFF; text-decoration:none;">注册</a></p>
    </div>
    <div class="login">
        <p><a href="/home/login" class="indexlink" style="color:#FFF; text-decoration:none;">登入</a></p>
    </div>

    <?php endif; ?>
    <!-- <div class="logined"></div> -->
</div>

<div class="body_box">
    <div class="phpimg_box">
        <div class="51phpvpn" style="position: relative;
        height: 300px;
        line-height: 300px;
        text-align: center;
        font-size: 80px;
        color: #FFF;
        top: -30px;">phpvpn100</div>
        <div class="svndesc">phpvpn100,专属php程序员的vpn,我们在做!<br/>
        主机地址1(香港) : vpn1.phpvpn100.com<br/>
                             主机地址2(美国) : vpn2.phpvpn100.com</div>

        <div class="php_icon"><img src="http://7i7iur.com1.z0.glb.clouddn.com/php.png" /></div>
    </div>
    <div class="tab_box">
        <div class="tab">
            <h2 class="keyword">10元/月</h2>
            <div class="keyword_desc">
                我们就收10元/月,扫码支付即可开通账号,各种免费活动，敬请期待!
            </div>
        </div>
        <div class="tab">
            <h2 class="keyword">跨平台性</h2>
            <div class="keyword_desc">
                支持IOS,Android,浏览器插件,客户端完善中!
            </div>
        </div>
        <div class="tab">
            <h2 class="keyword">浏览器插件</h2>
            <div class="keyword_desc">
                插件一键安装,让你体验专属的vpn感觉,浏览器插件近期推出!
            </div>
        </div>
        <div class="tab">
            <h2 class="keyword">专业可信赖</h2>
            <div class="keyword_desc">
                我们的团队是资深的php开发者,资深架构师,我们都有程序员情结。
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="pay_box">
            <div class="pay_img">
                <div class="zhifubao pay_btn">
                    <a href="/download/alipay_pay.png"><img src="/download/alipay_pay.png" style="width:230px;"/></a>
                </div>
                <div class="weixin">
                    <a href="/download/weixin_pay.png"><img src="/download/weixin_pay.png" style="width:250px" /></a>
                </div>
            </div>
    </div>
</div>

<div class="footer_box ">
    <div class="qq_qun">
        <span>qq交流群 : 569063128</span>
    </div>
</div>

<a class="bshareDiv" href="http://www.bshare.cn/share">分享按钮</a><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#uuid=3e974300-a0aa-4a99-b947-76ffa2078150&style=3&fs=4&textcolor=#ffffff&bgcolor=#cf4646&bp=qqmb,bsharesync,sinaminiblog,qzone,189share,sohuminiblog,renren,xinhuamb,tianya,shouji,ifengmb,neteasemb,qqxiaoyou,kaixin001,weixin,douban,qqim&text=分享"></script>

</body>

<script type="text/javascript">
    
    $('#userinfo').click(function(){
        window.location.href="/user/getinfo";
    });


</script>

</html>