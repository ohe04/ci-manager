<form class="cmxform" id="commentForm" method="post">

    <legend>*星號為必填欄位　
        <a style="float: right" href="/Role/index">回到列表</a>
    </legend>

    <table>
        <tr>
            <td class="td_mark_style">
                <label>*角色名稱</label>
            </td>
            <td class="td_mark_style">    
                <input type="hidden" name="roleId" value="<?= $resultDatas["roleId"] ?>"/>
                <input id="name" name="name" class="input_style required" maxlength="15" value="<?= $resultDatas["name"] ?>"/>
            </td>         
        </tr>
        <tr>
            <td></td>
            <td class="td_mark_style">
                <input id="save_btn" type="button" class="btn btn-primary" value="儲存資料">
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
                    url: "/Role/edit_data",
                    type: "post",
                    data: $("#commentForm").serialize(),
                    success: function (data) {
                        if ($.parseJSON(data).IsSuccess)
                        {
                            alertify.alert("編輯成功！", function () {
                                location.href = location.href;
                            });
                        }
                        else
                        {
                            alertify.alert($.parseJSON(data).Message);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alertify.alert("編輯資料錯誤！");
                    }
                });
            }
        });
    });
</script>
