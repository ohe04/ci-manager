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
                <input type="hidden" name="userId" value="<?= $resultDatas["userId"] ?>"/>
                <input id="name" name="account" disabled="true" class="input_style" value="<?= $resultDatas["account"] ?>"/>
            </td>         
        </tr>
        <tr>
            <td class="td_user_style">
                <label for="name">*是否禁用</label>
            </td>
            <td class="td_mark_style">
                <select id="isDelete" name="isDelete" class="required">
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
                <select id="name" name="name" class="required">
                    <?php foreach ($roleDatas as $item): ?>
                        <option value="<?= $item["roleId"] ?>"><?= $item["name"] ?></option>                       
                    <?php endforeach ?>
                </select>
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

    //將符合資料的select勾選(是否禁用)
    var isDelete = "<?= $resultDatas["isDelete"] ?>";
    $('#isDelete option').each(function (i, item)
    {
        if ($(item).val() === isDelete)
        {
            $(item).attr('selected', true);
        }
    });

    //將符合資料的select勾選(使用者身分)
    var roleId = "<?= $relResult["roleId"] ?>";
    $('#name option').each(function (i, item)
    {
        if ($(item).val() === roleId)
        {
            $(item).attr('selected', true);
        }
    });

    $(function () {
        //須與form表單ID名稱相同
        $("#commentForm").validate();

        $('#save_btn').click(function () {

            if ($("#commentForm").valid()) {

                //回傳資料儲存  
                $.ajax({
                    url: "/User/edit_data",
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