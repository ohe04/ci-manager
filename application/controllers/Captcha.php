<?php

/**
 * Description of captcha
 *
 * @author webber
 */
class Captcha extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function LoginCaptcha() {
        //設置定義為圖片
        header("Content-type: image/PNG");

        $this->imgcode("loginCode");
    }



    function imgcode($flag) {
        $img_height = 30;  // 圖形高度  
        $img_width = 60;   // 圖形寬度  
        $mass = 0;        // 雜點的數量，數字愈大愈不容易辨識  
        $num = "";              // rand後所存的地方  
        $num_max = 4;      // 產生__個驗證碼  
//        for ($i = 0; $i < $num_max; $i++) {
//            $num .= rand(0, 9);
//        }

        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMOPQRSTUBWXYZ";
        $num = '';
        for ($i = 0; $i < $num_max; $i++) {
            $num .= $str[mt_rand(0, strlen($str) - 1)];
        }

        //把驗證碼存進session        
        $this->session->set_userdata($flag, $num);
        // 創造圖片，定義圖形和文字顏色  
        Header("Content-type: image/PNG");
        srand((double) microtime() * 1000000);
        $im = imagecreate($img_width, $img_height);
        $black = ImageColorAllocate($im, 250, 250, 250);         // (0,0,0)文字為黑色  
        $gray = ImageColorAllocate($im, 0, 0, 0); // (200,200,200)背景是灰色  
        imagefill($im, 0, 0, $gray);
        // 在圖形產上黑點，起干擾作用;  
        for ($i = 0; $i < $mass; $i++) {
            imagesetpixel($im, rand(0, $img_width), rand(0, $img_height), $black);
        }
        // 將數字隨機顯示在圖形上,文字的位置都按一定波動範圍隨機生成  
        // $strx = rand(3, 8);
        $strx = 11;
        for ($i = 0; $i < $num_max; $i++) {
            //$strpos = rand(1, 8);
            $strpos = 6;
            imagestring($im, 5, $strx, $strpos, substr($num, $i, 1), $black);
            $strx+=rand(8, 14);
        }
        ImagePNG($im);
        ImageDestroy($im);
    }

}
