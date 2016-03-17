<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of url
 * 處理網址擴充功能
 * @author webber
 */
class url {

    public static function Action($controllerName, $actionName, $getParam = null) {

        $flag = true;

        $resultUrl = "/" . $controllerName . "/" . $actionName;

        if (!is_null($getParam)) {
            foreach ($getParam as $key => $value) {

                if ($flag) {
                    $resultUrl .= "?$key=$value";
                    $flag = FALSE;
                } else {
                    $resultUrl .= "&$key=$value";
                }                
            }
        }
        return $resultUrl;
    }

}
