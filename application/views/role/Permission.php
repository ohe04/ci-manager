<form class="cmxform" id="commentForm" method="post">

    <legend>*請勾選權限
        <a style="float: right" href="/Role/index">回到列表</a>
    </legend>

    <input type="hidden" name="roleId" value="<?= $roleId ?>">

    <?php foreach ($resultData as $item): ?>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th colspan="5" class="info" style="text-align:center;color:#337ab7;font-weight:400">
                        <?= $item["className"] ?>
                    </th>          
                </tr>
                <tr>               
                    <th>                        
                        <input type="button" class="all_permission btn btn-info btn-sm" value="全部權限" data-action="off"/>
                        模組名稱
                    </th>
                    <th>
                        <input type="button" class="index btn btn-default btn-sm" value="檢視權限" data-action="off"/>
                        檢視
                    </th>
                    <th>
                        <input type="button" class="create btn btn-default btn-sm" value="新增權限" data-action="off"/>
                        新增
                    </th>
                    <th>
                        <input type="button" class="edit btn btn-default btn-sm" value="編輯權限" data-action="off"/>
                        編輯
                    </th>
                    <th>
                        <input type="button" class="delete btn btn-default btn-sm" value="刪除權限" data-action="off"/>
                        刪除
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($item["classData"] as $moduleItem): ?>
                    <tr>
                        <td>
                            <input type="button" ref="<?= $moduleItem["moduleId"] ?>" class="wnd_btn btn btn-default btn-sm" value="全部權限" data-action="off"/>
                            <?= $moduleItem["moduleName"] ?>
                        </td>
                        <td>
                            <input class="wnd_<?= $moduleItem["moduleId"] ?>" name="view[]" type="checkbox" value="<?= $moduleItem["moduleId"] ?>" <?= ($moduleItem["view"] === "0") ? "" : 'checked="checked"' ?>>
                        </td>
                        <td>
                            <input class="wnd_<?= $moduleItem["moduleId"] ?>" name="create[]" type="checkbox" value="<?= $moduleItem["moduleId"] ?>" <?= ($moduleItem["create"] === "0") ? "" : 'checked="checked"' ?>>
                        </td>
                        <td>
                            <input class="wnd_<?= $moduleItem["moduleId"] ?>" name="edit[]" type="checkbox" value="<?= $moduleItem["moduleId"] ?>" <?= ($moduleItem["edit"] === "0") ? "" : 'checked="checked"' ?>>
                        </td>
                        <td>
                            <input class="wnd_<?= $moduleItem["moduleId"] ?>" name="delete[]" type="checkbox" value="<?= $moduleItem["moduleId"] ?>" <?= ($moduleItem["delete"] === "0") ? "" : 'checked="checked"' ?>>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endforeach ?>
</form>

<input id="save_btn" type="button" class="float_right btn btn-primary btn-sm" value="儲存權限">
<div style="clear: both">

<script type="text/javascript">

    $(".all_permission").click(function () {

        var action = $(this).attr("data-action");

        if (action === "off") {
            $(this).parent().parent().parent().next().find("input[type='checkbox']").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(this).parent().parent().parent().next().find("input[type='checkbox']").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $(".index").click(function () {

        var action = $(this).attr("data-action");

        if (action === "off") {
            $(this).parent().parent().parent().next().find("input[name='view[]']").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(this).parent().parent().parent().next().find("input[name='view[]']").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $(".create").click(function () {

        var action = $(this).attr("data-action");

        if (action === "off") {
            $(this).parent().parent().parent().next().find("input[name='create[]']").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(this).parent().parent().parent().next().find("input[name='create[]']").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $(".edit").click(function () {

        var action = $(this).attr("data-action");

        if (action === "off") {
            $(this).parent().parent().parent().next().find("input[name='edit[]']").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(this).parent().parent().parent().next().find("input[name='edit[]']").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $(".delete").click(function () {

        var action = $(this).attr("data-action");

        if (action === "off") {
            $(this).parent().parent().parent().next().find("input[name='delete[]']").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(this).parent().parent().parent().next().find("input[name='delete[]']").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $(".wnd_btn").click(function () {

        var wnd_id = $(this).attr("ref");
        var action = $(this).attr("data-action");

        if (action === "off") {
            $(".wnd_" + wnd_id + "").each(function () {
                $(this).prop("checked", true);
            });
            $(this).attr("data-action", "on");
        }
        else {
            $(".wnd_" + wnd_id + "").each(function () {
                $(this).prop("checked", false);
            });
            $(this).attr("data-action", "off");
        }
    });

    $('#save_btn').click(function () {

        //回傳資料儲存  
        $.ajax({
            url: "/Role/save_permission",
            type: "post",
            data: $("#commentForm").serialize(),
            success: function (data) {
                if ($.parseJSON(data).IsSuccess)
                {
                    alertify.alert("儲存成功！", function () {
                        location.reload();
                    });
                }
                else
                {
                    alertify.alert($.parseJSON(data).Message);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alertify.alert("修改權限錯誤！");
            }
        });
    });




</script>



