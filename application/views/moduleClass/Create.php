<form class="cmxform" id="commentForm" method="post">

    <legend>*星號為必填欄位　
        <a style="float: right" href="/ModuleClass/index">回到列表</a>
    </legend>

    <table>
        <tr>
            <td class="td_mark_style">
                <label>*分類名稱</label>
            </td>
            <td class="td_mark_style">                   
                <input id="className" name="className" class="input_style required" maxlength="15"/>
            </td>         
        </tr>
        <tr>
            <td class="td_mark_style">
                <label>*符號代號</label>
            </td>
            <td class="td_mark_style">                   
                <input id="classCode" name="classCode" class="input_style required" maxlength="40"/>
            </td>         
        </tr>
        <tr>
            <td class="td_mark_style">
                <label>*分類排序</label>
            </td>
            <td class="td_mark_style">                   
                <input id="classIndex" name="classIndex" type="number" class="input_style required" min="1"/>
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
                    url: "/ModuleClass/insert_data",
                    type: "post",
                    data: $("#commentForm").serialize(),
                    success: function (data) {
                        if ($.parseJSON(data).IsSuccess)
                        {
                            alertify.alert("新增成功！", function () {
                                location.href = location.href;
                            });
                        }
                        else
                        {
                            alertify.alert($.parseJSON(data).Message);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alertify.alert("新增資料錯誤！");
                    }
                });
            }
        });
    });
</script>

