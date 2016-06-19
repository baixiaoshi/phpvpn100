<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>

<input type="text" name="mobile" value="" placeholder="手机号" /><br/>
<input type="text" name="email" value="" placeholder="邮箱"><br/>
<input type="text" name="days" value="0" placeholder="天数" /><br/>
<input type="button" value="增加天数" id="add_btn" />

<script type="text/javascript">
    
    $('#add_btn').click(function() {
        var mobile = $('input[name="mobile"]').val();
        var email = $('input[name="email"]').val();
        var days = $('input[name="days"]').val();

        $.post(
            '/user/admin888',
            {mobile:mobile,email:email,days:days},
            function(data){
                alert(data.msg);
            },
            'json'
        );
    });


</script>
</body>
</html>