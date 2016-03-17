<?php

require_once 'application/lib/url.php';
require_once 'application/lib/task_result.php';
require_once 'application/lib/user_data.php';

/**
 * Description of site
 *
 * @author webber
 */
class Site extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->driver('cache');
        
        $this->load->model('Site_model');
        $this->load->model('Module_model');        
    }

    function index() {

        $userData = unserialize($this->session->userdata("userData"));
        if (empty($userData)) {
            redirect("Site/login");
        }
        
        $this->cache->file->clean(); //清除快取
        $data["breadcrumb"] = array("title" => "", "name" => "", "action" => "");

        $this->load->view('templates/Header', $data);
        $this->load->view('site/Index');
        $this->load->view('templates/Footer');
    }

    function login() {

        $this->load->view('site/Login');
    }

    /**
     * 登入檢查ajax 
     */
    function check_user() {

        $account = $this->input->post('account');
        $password = md5($this->input->post('password'));
        $chptcha = $this->input->post('chptcha');
        $taskResult = new TaskResult();
        $userData = new user_data();
        $loginCode = $this->session->userdata('loginCode');

        if (!empty($loginCode)) {
            if ($chptcha != $loginCode) {
                $taskResult->Message = "驗證碼錯誤";
                $taskResult->IsSuccess = false;
                echo json_encode($taskResult);
            } else {

                $result = $this->Site_model->get_user($account, $password);

                if ($result !== null) {
                    $taskResult->IsSuccess = true;

                    //判斷是否為admin
                    if ($this->Site_model->isAdmin($account)) {
                        $userData->isAdmin = true;
                    } else {
                        $userData->isAdmin = false;
                    }

                    //將資訊紀錄在session        
                    $userData->userId = $result["userId"];
                    $userData->userAccount = $account;
                    $this->session->set_userdata('userData', serialize($userData));

                    echo json_encode($taskResult);
                } else {
                    $taskResult->Message = "帳號或密碼錯誤";
                    $taskResult->IsSuccess = FALSE;
                    echo json_encode($taskResult);
                }
            }
        } else {
            $taskResult->Message = "請更換驗證碼";
            $taskResult->IsSuccess = FALSE;
            echo json_encode($taskResult);
        }
    }

    function logout() {
        $this->session->unset_userdata('userData');
        redirect("/Site/login");
    }

}


