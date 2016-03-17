<p>
    <a href="create" class="btn btn-success">新增
    </a>
</p>

<table class="table table-striped table-bordered table-hover">
    <tbody>
        <tr>
            <th>帳號</th>
            <th>系統角色</th>
            <th>禁用</th>
            <th>操作</th>
        </tr>
        <?php foreach ($resultDatas as $item): ?>
            <tr>
                <td><?php echo $item['account'] ?></td>
                <td><?php echo $item['name'] ?></td>
                <td>
                    <?php if ($item['isDelete'] == 1) { ?>
                        <input class="check-box" disabled="disabled" type="checkbox" checked="checked">
                    <?php } else { ?>
                        <input class="check-box" disabled="disabled" type="checkbox">
                    <?php } ?>
                </td>
                <td class="user_operating">
                    <a href="/User/permission/<?= $item["userId"] ?>"><i class="fa fa-unlock-alt"></i>權限</a> | 
                    <a href="/User/edit/<?= $item["userId"] ?>"><i class="fa fa-pencil"></i>編輯</a> | 
                    <a href="/User/chagnePwd/<?= $item["userId"] ?>"><i class="fa fa-expeditedssl"></i>修改密碼</a> | 
                    <a class="delete" data-id="<?= $item["userId"] ?>" href="#"><i class="fa fa-trash-o"></i>刪除</a>
                </td>
            </tr> 
        <?php endforeach ?>
    </tbody>
</table>

<script>

    $(function () {

        //點擊刪除按鈕
        $(".delete").click(function () {

            var id = $(this).attr("data-id");

            alertify.confirm("是否刪除？", function (e) {
                if (e) {

                    $.ajax({
                        url: "/User/delete_data",
                        type: "post",
                        data: {id: id},
                        success: function (data) {

                            if ($.parseJSON(data).IsSuccess)
                            {
                                alertify.alert("刪除成功！",function(){
                                    location.reload();
                                });                                
                            }
                            else
                            {
                                alertify.alert($.parseJSON(data).Message);
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alertify.alert("刪除資料錯誤！");
                        }
                    });
                }
            });

        });
    });

</script>
