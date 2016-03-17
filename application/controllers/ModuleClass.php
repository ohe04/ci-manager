<?php

require_once 'application/lib/task_result.php';
require_once 'application/lib/user_data.php';

class ModuleClass extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Module_class_model');
        $this->load->model('Module_model');
        $this->load->library('session');
        $this->load->helper('url');

        $userData = unserialize($this->session->userdata("userData"));
        if (empty($userData)) {
            redirect("Site/login");
        }
    }

    public function index() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組分類", "action" => "");
        $data['resultDatas'] = $this->Module_class_model->get_module_class();

        $this->load->view('templates/Header', $data);
        $this->load->view('moduleClass/Index', $data);
        $this->load->view('templates/Footer');
    }

    public function create() {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組分類", "action" => "新增");
        $data['resultDatas'] = $this->Module_class_model->get_module_class();

        $this->load->view('templates/Header', $data);
        $this->load->view('moduleClass/Create', $data);
        $this->load->view('templates/Footer');
    }

    public function edit($id) {

        $data["breadcrumb"] = array("title" => "系統管理", "name" => "系統模組", "action" => "修改");
        $data['resultData'] = $this->Module_class_model->get_module_class($id);

        $this->load->view('templates/Header', $data);
        $this->load->view('moduleClass/Edit', $data);
        $this->load->view('templates/Footer');
    }

    public function insert_data() {

        $className = $this->input->post("className");
        $classCode = $this->input->post("classCode");
        $classIndex = $this->input->post("classIndex");
        $taskResult = new TaskResult();

        //先判斷模組分類名稱是否存在
        $queryResult = $this->Module_class_model->query_module_class($className);

        if ($queryResult) {

            $insertResult = $this->Module_class_model->insert_data($className, $classIndex, $classCode);

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

    public function edit_data() {

        $classId = $this->input->post("classId");
        $className = $this->input->post("className");
        $oldClassName = $this->input->post("oldClassName");
        $classCode = $this->input->post("classCode");
        $classIndex = $this->input->post("classIndex");
        $taskResult = new TaskResult();
        $queryResult = TRUE;

        if ($oldClassName != $className) {
            //判斷模組分類名稱是否存在
            $queryResult = $this->Module_class_model->query_module_class($className);
        }

        if ($queryResult) {

            $insertResult = $this->Module_class_model->update_data($classId, $className, $classIndex, $classCode);

            if ($insertResult) {
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

        $classId = $_POST["id"];
        $taskResult = new TaskResult();

        $queryResult = $this->Module_class_model->query_module($classId);

        if ($queryResult) {

            $deleteResult = $this->Module_class_model->delete_data($classId);

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
            $taskResult->Message = "此分類有模組資料無法刪除";
            echo json_encode($taskResult);
        }
    }

}
