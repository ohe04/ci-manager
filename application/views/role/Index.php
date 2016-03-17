<p>
    <a href="create" class="btn btn-success">新增
    </a>
</p>

<table class="table table-striped table-bordered table-hover">
    <tbody>
        <tr>
            <th>角色名稱</th>
            <th>操作</th>
        </tr>
        <?php foreach ($resultDatas as $item): ?>
            <tr>                
                <td><?php echo $item['name'] ?></td>               
                <td class="role_operating">
                    <a href="/Role/permission/<?= $item["roleId"] ?>"><i class="fa fa-unlock-alt"></i>權限</a> | 
                    <a href="/Role/edit/<?= $item["roleId"] ?>"><i class="fa fa-pencil"></i>編輯</a> | 
                    <a class="delete" data-id="<?= $item["roleId"] ?>" href="#"><i class="fa fa-trash-o"></i>刪除</a>
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
                        url: "/Role/delete_data",
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

