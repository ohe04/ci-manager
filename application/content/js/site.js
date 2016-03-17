$(document).ready(function () {

    //判斷螢幕高度與main_table高度,以填滿menu黑色部分
    var tableHeight = $("#main_table").height();
    var monitorHeight = document.documentElement.clientHeight;

    if (monitorHeight > tableHeight) {
        $("#main_table").css("min-height", monitorHeight - 50);
    }

    //選單點選事件
    $("ul>li.item>a").click(function () {

        var flag = $(this).siblings("ul").attr("data-flag");
        //點選同一個已展開menu
        if (flag == "open") {
            $("ul>li.item>.open").siblings("a").find("em").removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
            $("ul>li.item>.open").removeClass("open").slideToggle(300).attr("data-flag", "");
        }
        else {
            $("ul>li.item>.open").siblings("a").find("em").removeClass("fa-minus-square-o").addClass("fa-plus-square-o");  //改變圖案
            $("ul>li.item>.open").removeClass("open").slideToggle(300).attr("data-flag", "");                              //縮已展開menu
            $(this).siblings("ul").slideToggle(300).addClass("open").attr("data-flag", "open");                            //展開點選menu
            $(this).find("em").removeClass("fa-plus-square-o").addClass("fa-minus-square-o");                              //改變圖案
        }

    });

    $("#logout_cancel_btn").click(function () {
        $("#logout_msg").fadeOut();
    });

    $("#logout_btn").click(function () {
        $("#logout_msg").fadeIn();
    });

    $(window).resize(function () {
        detect_left_panel_h();
    });

});

//判斷內容物高度跟螢幕高度 決定左邊panel黑色區塊
function detect_left_panel_h() {
    //判斷螢幕高度與main_table高度,以填滿menu黑色部分
    var tableHeight = $("#main_table").height();
    var monitorHeight = document.documentElement.clientHeight;

    if (monitorHeight > tableHeight) {
        $("#main_table").css("min-height", monitorHeight - 50);
    }
}


