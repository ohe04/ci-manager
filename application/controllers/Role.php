<?php

require_once 'application/lib/task_result.php';
require_once 'application/lib/user_data.php';

class Role extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Role_model');
        $this->load->model('Rel_user_role_model');
        $this->load->model('Role_permission_model');
        $this->load->model('Module_model');
        $this->load->library('session');
        $this->load->helper('url');

        $userData = unserialize($this->session->userdata("userData"));
        if (empty($userData)) {
            redirect("Site/login");
        }
    }

    public function index() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統角色", "action" => "");
        $data['resultDatas'] = $this->Role_model->get_roles();

        $this->load->view('templates/Header', $data);
        $this->load->view('role/Index', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 新增角色action
     */
    public function create() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統角色", "action" => "新增");

        $this->load->view('templates/Header', $data);
        $this->load->view('role/Create', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 編輯角色action
     */
    public function edit($roleId) {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統角色", "action" => "編輯");
        $data['resultDatas'] = $this->Role_model->get_roles($roleId);

        $this->load->view('templates/Header', $data);
        $this->load->view('role/Edit', $data);
        $this->load->view('templates/Footer');
    }

    public function permission($roleId = "") {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統角色", "action" => "編輯使用者權限");

        $this->Role_permission_model->detect_permission($roleId);
        $data["resultData"] = $this->Role_permission_model->get_permission_view($roleId);
        $data["roleId"] = $roleId;

        $this->load->view('templates/Header', $data);
        $this->load->view('role/Permission', $data);
        $this->load->view('templates/Footer');
    }

    /**
     * 新增角色ajax
     */
    public function insert_data() {

        $name = $this->input->post("name");
        $taskResult = new TaskResult();

        //先判斷角色名稱是否存在
        $queryResult = $this->Role_model->query_role($name);

        if ($queryResult) {

            $insertResult = $this->Role_model->insert_data($name);

            if ($insertResult) {
                $taskResult->IsSuccess = TRUE;
                echo json_encode($taskResult);
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "新增資料錯誤";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "此名稱已經存在";
            echo json_encode($taskResult);
        }
    }

    /**
     * 編輯角色ajax
     */
    public function edit_data() {

        $roleId = $this->input->post("roleId");
        $name = $this->input->post("name");
        $taskResult = new TaskResult();

        //先判斷角色名稱是否存在
        $queryResult = $this->Role_model->query_role($name);

        if ($queryResult) {

            $updateResult = $this->Role_model->update_data($roleId, $name);

            if ($updateResult) {
                $taskResult->IsSuccess = TRUE;
                echo json_encode($taskResult);
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "編輯資料錯誤";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "此名稱已經存在";
            echo json_encode($taskResult);
        }
    }

    public function delete_data() {

        $roleId = $_POST["id"];
        $taskResult = new TaskResult();

        $queryResult = $this->Rel_user_role_model->query_by_roleId($roleId);

        if (!$queryResult) {

            $deleteResult = $this->Role_model->delete_data($roleId);

            if ($deleteResult) {
                $taskResult->IsSuccess = TRUE;
                echo json_encode($taskResult);
            } else {
                $taskResult->IsSuccess = FALSE;
                $taskResult->Message = "刪除資料失敗";
                echo json_encode($taskResult);
            }
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "此角色有使用者，無法刪除";
            echo json_encode($taskResult);
        }
    }

    /**
     * 儲存權限ajax
     */
    public function save_permission() {

        try {

            $taskResult = new TaskResult();

            $roleId = $this->input->post("roleId");
            $views = $this->input->post("view");
            $creates = $this->input->post("create");
            $edits = $this->input->post("edit");
            $deletes = $this->input->post("delete");

            $this->Role_permission_model->ini_permission($roleId);

            //處理檢視
            if (!empty($views)) {
                foreach ($views as $moduleId) {
                    $this->Role_permission_model->update_permission("view", $roleId, $moduleId);
                }
            }
            //處理新增
            if (!empty($creates)) {
                foreach ($creates as $moduleId) {
                    $this->Role_permission_model->update_permission("create", $roleId, $moduleId);
                }
            }
            //處理修改
            if (!empty($edits)) {
                foreach ($edits as $moduleId) {
                    $this->Role_permission_model->update_permission("edit", $roleId, $moduleId);
                }
            }
            //處理刪除
            if (!empty($deletes)) {
                foreach ($deletes as $moduleId) {
                    $this->Role_permission_model->update_permission("delete", $roleId, $moduleId);
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

}
