<?php

require_once 'application/lib/task_result.php';
require_once 'application/lib/user_data.php';

class Module extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Module_model');
        $this->load->model('Module_class_model');
        $this->load->library('session');
        $this->load->helper('url');

        $userData = unserialize($this->session->userdata("userData"));
        if (empty($userData)) {
            redirect("Site/login");
        }
    }

    public function index() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組", "action" => "");
        $data['resultDatas'] = $this->Module_model->get_modules();

        $this->load->view('templates/Header', $data);
        $this->load->view('module/Index', $data);
        $this->load->view('templates/Footer');
    }

    public function create() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組", "action" => "新增");
        $data['classDatas'] = $this->Module_class_model->get_module_class();

        $this->load->view('templates/Header', $data);
        $this->load->view('module/Create', $data);
        $this->load->view('templates/Footer');
    }

    public function edit($id) {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組", "action" => "編輯");
        $data['classDatas'] = $this->Module_class_model->get_module_class();
        $data['resultDatas'] = $this->Module_model->get_modules($id);

        $this->load->view('templates/Header', $data);
        $this->load->view('module/Edit', $data);
        $this->load->view('templates/Footer');
    }

    public function insert_data() {

        $name = $this->input->post("name");
        $code = $this->input->post("code");
        $classId = $this->input->post("classId");
        $index = $this->input->post("index");
        $taskResult = new TaskResult();

        //先判斷角色名稱是否存在
        $queryResult = $this->Module_model->query_module($name,$classId);

        if ($queryResult) {

            $insertResult = $this->Module_model->insert_data($name, $code, $classId, $index);

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

        $id = $this->input->post("id");
        $name = $this->input->post("name");
        $oldName = $this->input->post("oldName");
        $code = $this->input->post("code");
        $classId = $this->input->post("classId");
        $index = $this->input->post("index");
        $queryResult = TRUE;

        $taskResult = new TaskResult();

        if ($oldName != $name) {
            //判斷角色名稱是否存在
            $queryResult = $this->Module_model->query_module($name,$classId);
        }

        if ($queryResult) {

            $updateResult = $this->Module_model->update_data($id, $name, $code, $classId, $index);

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

        $id = $_POST["id"];
        $taskResult = new TaskResult();

        $deleteResult = $this->Module_model->delete_data($id);
        $this->Module_model->delete_user_permission($id);
        $this->Module_model->delete_role_permission($id);

        if ($deleteResult) {
            $taskResult->IsSuccess = TRUE;
            echo json_encode($taskResult);
        } else {
            $taskResult->IsSuccess = FALSE;
            $taskResult->Message = "刪除資料失敗";
            echo json_encode($taskResult);
        }
    }

}
