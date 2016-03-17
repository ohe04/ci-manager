<form class="cmxform" id="commentForm" method="post">

    <legend>*星號為必填欄位　
        <a style="float: right" href="/User/index">回到列表</a>
    </legend>

    <table>
        <tr>
            <td class="td_user_style">
                <label>*帳號</label>
            </td>
            <td class="td_mark_style">                        
                <input name="account" class="required chrnum input_style" maxlength="15"/>
            </td>         
        </tr>
        <tr>
            <td class="td_user_style">
                <label for="name">*密碼</label>
            </td>
            <td class="td_mark_style">
                <input id="password" name="password" type="password" class="required chrnum input_style" minlength="6" maxlength="15"/>
            </td>
        </tr>
        <tr>
            <td class="td_user_style">
                <label for="name">*是否禁用</label>
            </td>
            <td class="td_mark_style">
                <select name="isDelete" class="required">
                    <option value="0">否</option>  
                    <option value="1">是</option>                       
                </select>
            </td>
        </tr>
        <tr>
            <td class="td_user_style">
                <label>*使用者角色</label>
            </td>
            <td class="td_mark_style">
                <select name="name" class="required">
                    <?php foreach ($roleDatas as $item): ?>
                        <option value="<?= $item["roleId"] ?>"><?= $item["name"] ?></option>                       
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="td_mark_style">
                <input id="save_btn" type="button" class="btn btn-primary" value="新增資料">
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
                    url: "/User/insert_data",
                    type: "post",
                    data: $("#commentForm").serialize(),
                    success: function (data) {
                        if ($.parseJSON(data).IsSuccess)
                        {
                            alertify.alert("新增成功！",function(){
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
    });
</script>
