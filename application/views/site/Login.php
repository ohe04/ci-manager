<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">      
        <link href="/application/content/css/lib/reset.css" rel="stylesheet" type="text/css"/>
        <script src="/application/content/js/lib/jquery.js" type="text/javascript"></script>
        <link href="/application/content/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/application/content/css/site.css" rel="stylesheet" type="text/css"/>
        <link href="/application/content/font_awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>        
        <script src="/application/content/alertify/alertify.min.js" type="text/javascript"></script>        
        <link href="/application/content/alertify/alertify.core.css" rel="stylesheet" type="text/css"/>        
        <link href="/application/content/alertify/alertify.bootstrap.css" rel="stylesheet" type="text/css"/> 

        <title>黃金娛樂城</title>
        
    </head>
    <body>
        <form id="loginForm">
            <div id="login_main">
                <div id="login_header">
                    黃金娛樂城
                </div>
                <fieldset>
                    <section>
                        <label class="label">
                            帳號
                        </label>
                        <lable id="account_lable" class="input_text">
                            <i class="icon-append fa fa-user"></i>
                            <input id="account" type="text" name="account" class="input_style" tabindex="1">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-user txt-color-teal"></i> 
                                請輸入帳號
                            </b>
                        </lable>
                    </section>
                    <section>
                        <label class="label">
                            密碼
                        </label>
                        <lable id="password_lable" class="input_text">
                            <i class="icon-append fa fa-lock"></i>
                            <input id="password" type="password" name="password" class="input_style" tabindex="2">
                            <b class="tooltip tooltip-top-right">
                                <i class="fa fa-lock txt-color-teal"></i> 
                                請輸入密碼
                            </b>
                        </lable>
                    </section>
                    <section>
                        <label class="label">
                            驗證碼
                        </label>
                        <lable id="password_lable" class="input_text">
                            <img class="chptcha" title="點擊更換驗證碼" src="/captcha/LoginCaptcha" alt="">
                            <input id="chptcha" type="text" name="chptcha" maxlength="4" tabindex="3">                     
                        </lable>
                    </section>
                </fieldset>
                <div id="login_footer">
                    <button id="loginBtn" type="button" class="btn btn-primary">登入</button>
                </div>
            </div>
        </form>
    </body>
</html>


<script>
    $(function () {

        //點擊更換驗證碼
        $(".chptcha").click(function () {
            $(this).attr("src", "<?= url::Action("captcha", "LoginCaptcha") ?>");
        });

        $("#loginBtn").click(function () {

            var account = $("#account").val();
            var password = $("#password").val();
            var chptcha = $("#chptcha").val();

            if (account === "" || password === "" || chptcha === "") {
                alertify.alert("請輸入必填欄位");
            }
            else {
                //檢查登入資訊
                $.ajax({
                    url: "/site/check_user",
                    type: "post",
                    data: $("#loginForm").serialize(),
                    success: function (data) {

                        if ($.parseJSON(data).IsSuccess)
                        {
                            location.href = "/Site/index";
                        }
                        else
                        {
                            alertify.alert($.parseJSON(data).Message);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alertify.alert("登入錯誤！");
                    }
                });
            }
        });

        //按下ENTER事件
        $("#chptcha").keyup(function (event) {
            if (event.keyCode == 13) {
                $("#loginBtn").click();
            }
        });
    });
</script>




