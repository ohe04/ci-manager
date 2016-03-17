<!DOCTYPE html>

<!--判斷是否登入-->
<?php
require_once 'application/lib/user_data.php';
$userData = unserialize($this->session->userdata("userData"));
//取得模組
$moduleDatas = $this->Module_model->get_permission_modules();
?>

<html>
    <head>
        <meta charset="UTF-8">       
        <link href="<?php echo base_url("application/content/css/lib/reset.css"); ?>" rel="stylesheet" type="text/css"/>
        <script src="<?php echo base_url("application/content/js/lib/jquery.js"); ?>" type="text/javascript"></script>
        <link href="<?php echo base_url("application/content/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet" type="text/css"/>
        <script src="<?php echo base_url("application/content/js/lib/bootstrap.min.js"); ?>" type="text/javascript"></script>
        <link href="<?php echo base_url("application/content/css/site.css"); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url("application/content/font_awesome/css/font-awesome.min.css"); ?>" rel="stylesheet" type="text/css"/>
        <script src="<?php echo base_url("application/content/js/site.js"); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("application/content/alertify/alertify.min.js"); ?>" type="text/javascript"></script>        
        <link href="<?php echo base_url("application/content/alertify/alertify.core.css"); ?>" rel="stylesheet" type="text/css"/>        
        <link href="<?php echo base_url("application/content/alertify/alertify.bootstrap.css"); ?>" rel="stylesheet" type="text/css"/>
        <script src="<?php echo base_url("application/content/validation/jquery.validate.js"); ?>" type="text/javascript"></script>
        
        <title>黃金娛樂網</title>
        <link rel="shortcut icon" href="<?php echo base_url("application/content/img/icon.png"); ?>">
    </head>
    <body>        
        <div id="header">
            <div id="logo">
            </div>
            <div class="event" id="logout">
                <a id="logout_btn">
                    <i class="fa fa-sign-out"></i>
                </a>
            </div>
        </div>       
        <table id="main_table">
            <tr>
                <td style="background: linear-gradient(to right,#3a3633 93%,#2a2725 100%);width:200px">
                    <div id="left_panel" style="position:absolute;top:0">
                        <div id="login-info">
                            <span>
                                <i class="fa fa-2x fa-user"></i>
                                <span id="login_name">
                                    <?= $userData->userAccount ?>
                                </span>
                            </span>
                        </div>
                        <nav>
                            <ul>
                                <?php foreach ($moduleDatas as $data): ?>
                                    <li class="item">
                                        <a href="#"><i class="fa fa-lg fa-fw <?= $data["classCode"] ?>"></i> <span class="menu-item-parent"><?= $data["className"] ?></span><b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b></a>
                                        <ul>
                                            <?php foreach ($data["classData"] as $moduleItem): ?>
                                                <li>
                                                    <a href="/<?= $moduleItem["moduleCode"] ?>/index"><?= $moduleItem["moduleName"] ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>     

                                <li class="item">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-cog"></i> <span class="menu-item-parent">個人設定</span><b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b></a>
                                    <ul>
                                        <li>
                                            <a href="/User/changePassword">修改密碼</a>
                                        </li>
                                    </ul>
                                </li>

                                <?php if ($userData->isAdmin) { ?>
                                    <li class="item">
                                        <a href="#"><i class="fa fa-lg fa-fw fa-television"></i> <span class="menu-item-parent">系統設定</span><b class="collapse-sign"><em class="fa fa-plus-square-o"></em></b></a>
                                        <ul>
                                            <li>
                                                <a href="/User/index">系統使用者</a>
                                            </li>
                                            <li>
                                                <a href="/Role/index">系統角色</a>
                                            </li>  
                                            <li>
                                                <a href="/ModuleClass/index">系統模組分類</a>
                                            </li>  
                                            <li>
                                                <a href="/Module/index">系統模組</a>
                                            </li>  
                                        </ul>
                                    </li>    
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                </td>
                <td>
                    <!--breadcrumb-->
                    <div id="ribbon">
                        <span class="ribbon-button-alignment"> 
                            <span id="refresh" class="btn-ribbon"><i class="fa fa-home"></i></span> 
                        </span>
                        <ol class="breadcrumb">
                            <?php if (!empty($breadcrumb["title"])) { ?>
                                <li><?= $breadcrumb["title"] ?></li>
                            <?php } ?>
                            <?php if (!empty($breadcrumb["name"])) { ?>
                                <li><?= $breadcrumb["name"] ?></li>
                            <?php } ?>
                            <?php if (!empty($breadcrumb["action"])) { ?>
                                <li><?= $breadcrumb["action"] ?></li>
                            <?php } ?>
                        </ol>
                    </div>
                    <div id="content">
