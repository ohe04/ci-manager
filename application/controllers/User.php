<?php

require_once 'application/lib/task_result.php';
require_once 'application/lib/user_data.php';

/**
 * Description of User
 *
 * @author webber
 */
class User extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Rel_user_role_model');
        $this->load->model('User_permission_model');
        $this->load->model('Module_model');
        $this->load->library('session');
        $this->load->helper('url');

        $userData = unserialize($this->session->userdata("userData"));
        if (empty($userData)) {
            redirect("Site/login");
        }
    }

    public function index() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統使用者", "action" => "");
        $data['resultDatas'] = $this->User_model->get_users();

        $this->load->view('templates/Header', $data);
        $this->load->view('user/Index', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 新增使用者action
     */
    public function create() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統使用者", "action" => "新增");
        $data['roleDatas'] = $this->Role_model->get_roles();

        $this->load->view('templates/Header', $data);
        $this->load->view('user/Create');
        $this->load->view('templates/Footer');
    }

    public function edit($userId = "") {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統使用者", "action" => "編輯");
        $data['roleDatas'] = $this->Role_model->get_roles();
        $data['resultDatas'] = $this->User_model->get_users($userId);
        $data['relResult'] = $this->Rel_user_role_model->get_rel($userId);

        $this->load->view('templates/Header', $data);
        $this->load->view('user/Edit', $data);
        $this->load->view('templates/Footer');
    }

    public function permission($userId = "") {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統使用者", "action" => "編輯使用者權限");

        $this->User_permission_model->detect_permission($userId);
        $data["resultData"] = $this->User_permission_model->get_permission_view($userId);
        $data["userId"] = $userId;

        $this->load->view('templates/Header', $data);
        $this->load->view('user/Permission', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 管理者編輯使用者密碼
     * @param type $userId
     */
    public function chagnePwd($userId = "") {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統使用者", "action" => "編輯使用者密碼");
        $data["userId"] = $userId;

        $this->load->view('templates/Header', $data);
        $this->load->view('user/ChangePwd', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 修改密碼action
     */
    public function changePassword() {

        $data["breadcrumb"] = array("title" => "內容管理", "name" => "個人設定", "action" => "修改密碼");
        $this->load->view('templates/Header', $data);
        $this->load->view('user/ChangePassword');
        $this->load->view('templates/Footer');
    }

    /**
     * 修改密碼ajax
     */
    public function change_pwd() {

        $oldPwd = $this->input->post("oldPwd");
        $newPwd = $this->input->post("newPwd");

        $userData = unserialize($this->session->userdata("userData"));
        $account = $userData->userAccount;
        $taskResult = new TaskResult();

        $queryResult = $this->User_model->query_user($account, md5($oldPwd));

        if ($queryResult) {
            $updateResult = $this->User_model->change_password($account, md5($newPwd));

            if ($updateResult) {
                $taskResult->IsSuccess = TRUE;
                echo json_encode($taskResult);
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "更新資料錯誤";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "舊密碼錯誤";
            echo json_encode($taskResult);
        }
    }

    /**
     * 新增使用者ajax
     */
    public function insert_data() {

        $account = $this->input->post("account");
        $password = md5($this->input->post("password"));
        $isDelete = $this->input->post("isDelete");
        $roleId = $this->input->post("name");
        $taskResult = new TaskResult();

        $queryResult = $this->User_model->query_user($account);
        //先判斷帳號是否存在
        if (!$queryResult) {
            $insertResult = $this->User_model->insert_data($account, $password, $isDelete);

            if ($insertResult) {

                //取得新增使用者的ID
                $userId = $this->db->insert_id();

                $insertRelResult = $this->Rel_user_role_model->insert_rel($userId, $roleId);

                if ($insertRelResult) {
                    $taskResult->IsSuccess = TRUE;
                    echo json_encode($taskResult);
                } else {
                    $taskResult->IsSuccess = FALSE;
                    $taskResult->Message = "新增資料錯誤";
                    echo json_encode($taskResult);
                }
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "新增資料錯誤";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "此帳號已經存在";
            echo json_encode($taskResult);
        }
    }

    /**
     * 編輯資料ajax
     */
    public function edit_data() {

        $userId = $this->input->post("userId");
        $isDelete = $this->input->post("isDelete");
        $roleId = $this->input->post("name");
        $taskResult = new TaskResult();

        $updateResult = $this->User_model->edit_data($userId, $isDelete);

        if ($updateResult) {

            //先刪除關聯在新增
            $deleteResult = $this->Rel_user_role_model->delete_rel($userId);
            if ($deleteResult) {

                $insertResult = $this->Rel_user_role_model->insert_rel($userId, $roleId);

                if ($insertResult) {
                    $taskResult->IsSuccess = TRUE;
                    echo json_encode($taskResult);
                } else {
                    $taskResult->IsSuccess = FALSE;
                    $taskResult->Message = "編輯資料錯誤(一)";
                    echo json_encode($taskResult);
                }
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "編輯資料錯誤(二)";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "編輯資料錯誤(三)";
            echo json_encode($taskResult);
        }
    }

    /**
     * 刪除資料ajax
     */
    public function delete_data() {

        $userId = $_POST["id"];
        $taskResult = new TaskResult();

        $deleteResult = $this->User_model->delete_data($userId);

        if ($deleteResult) {

            $deleteRelResult = $this->Rel_user_role_model->delete_rel($userId);

            if ($deleteRelResult) {
                $taskResult->IsSuccess = true;
                echo json_encode($taskResult);
            } else {
                $taskResult->IsSuccess = false;
                $taskResult->Message = "刪除資料失敗(一)";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = false;
            $taskResult->Message = "刪除資料失敗(二)";
            echo json_encode($taskResult);
        }
    }

    /**
     * 儲存權限ajax
     */
    public function save_permission() {

        try {

            $taskResult = new TaskResult();

            $userId = $this->input->post("userId");
            $views = $this->input->post("view");
            $creates = $this->input->post("create");
            $edits = $this->input->post("edit");
            $deletes = $this->input->post("delete");

            $this->User_permission_model->ini_permission($userId);

            //處理檢視
            if (!empty($views)) {
                foreach ($views as $moduleId) {
                    $this->User_permission_model->update_permission("view", $userId, $moduleId);
                }
            }
            //處理新增
            if (!empty($creates)) {
                foreach ($creates as $moduleId) {
                    $this->User_permission_model->update_permission("create", $userId, $moduleId);
                }
            }
            //處理修改
            if (!empty($edits)) {
                foreach ($edits as $moduleId) {
                    $this->User_permission_model->update_permission("edit", $userId, $moduleId);
                }
            }
            //處理刪除
            if (!empty($deletes)) {
                foreach ($deletes as $moduleId) {
                    $this->User_permission_model->update_permission("delete", $userId, $moduleId);
                }
            }

            $taskResult->IsSuccess = true;
            echo json_encode($taskResult);
        } catch (Exception $e) {

            $taskResult = new TaskResult();
            $taskResult->IsSuccess = false;
            $taskResult->Message = $e->getMessage();
            echo json_encode($taskResult);
        }
    }

    /**
     * 管理者修改user pwd ajax
     */
    public function change_user_pwd() {

        $userId = $this->input->post("userId");
        $password = $this->input->post("password");
        $taskResult = new TaskResult();

        $updateResult = $this->User_model->edit_user_pwd($userId, md5($password));

        if ($updateResult) {

            $taskResult->IsSuccess = TRUE;
            echo json_encode($taskResult);
            
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "修改密碼錯誤";
            echo json_encode($taskResult);
        }
    }

}
