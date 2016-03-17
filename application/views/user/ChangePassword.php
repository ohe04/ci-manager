<form class="cmxform" id="commentForm" method="post">

    <legend>*星號為必填欄位　       
    </legend>

    <table>
        <tr>
            <td class="td_title_style">
                <label for="name" style="password">*舊密碼</label>
            </td>
            <td class="td_mark_style">
                <input id="oldPwd" type="password" name="oldPwd" class="required chrnum" value=""  maxlength="15"/>
            </td>
        </tr>
        <tr>
            <td class="td_title_style">
                <label for="name">*新密碼</label>
            </td>
            <td class="td_mark_style">                
                <input id="newPwd" type="password" name="newPwd" class="required chrnum" value=""  minlength="6" maxlength="15"/>
            </td>
        </tr>
        <tr>
            <td class="td_title_style">
                <label for="name">*確認新密碼</label>
            </td>
            <td class="td_mark_style">                
                <input id="confromNewPwd" type="password" class="required chrnum" equalTo="#newPwd" value="" minlength="6" maxlength="15"/>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="td_mark_style">
                <input id="save_btn" type="button" class="btn btn-primary" value="修改資料">
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">

    $(function () {
        //須與form表單ID名稱相同
        $("#commentForm").validate();

        $('#save_btn').click(function () {

            if ($("#commentForm").valid()) {

                //回傳資料儲存  
                $.ajax({
                    url: "/User/change_pwd",
                    type: "post",
                    data: $("#commentForm").serialize(),
                    success: function (data) {

                        if ($.parseJSON(data).IsSuccess)
                        {
                            alertify.alert("修改成功！",function(){
                                location.href = location.href;
                            });
                        }
                        else
                        {
                            alertify.alert($.parseJSON(data).Message);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alertify.alert("修改資料錯誤！");
                    }
                });
            }
        });

        jQuery.validator.addMethod("chrnum", function (value, element) {
            var chrnum = /^([a-zA-Z0-9]+)$/;
            return this.optional(element) || (chrnum.test(value));
        }, "只能輸入英文和數字");
    });
</script>
