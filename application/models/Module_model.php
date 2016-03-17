<?php

class Module_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Role_permission_model');
        $this->load->model('User_permission_model');
        $this->load->model('Rel_user_role_model');
        $this->load->driver('cache');
    }

    /**
     * 取得module資料
     * @param type $id   module id
     * @return type
     */
    public function get_modules($id = null) {

        if ($id == null) {
            $this->db->select('module.id,module.`name`,module.code,module_class.className,module.index');
            $this->db->from('module');
            $this->db->join('module_class', 'module.classId = module_class.classId');
            $this->db->order_by("className", "ASC");
            $this->db->order_by("index", "ASC");
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->select('module.id,module.`name`,module.code,module_class.className,module_class.classId,module.index');
            $this->db->from('module');
            $this->db->join('module_class', 'module.classId = module_class.classId');
            $this->db->where('id', $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    /**
     * 查詢module名稱是否存在
     * @param type $name  module名稱
     * @return boolean  true:不存在 false:存在
     */
    public function query_module($name, $classId) {

        $query = $this->db->get_where("module", array('name' => $name, 'classId' => $classId));
        $result = $query->row_array();

        if ($result === NULL) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 新增資料
     * @param type $name
     * @return type
     */
    public function insert_data($name, $code, $classId, $index) {

        $data = array(
            'name' => $name,
            'code' => $code,
            'classId' => $classId,
            'index' => $index
        );

        $result = $this->db->insert("module", $data);

        return $result;
    }

    public function update_data($id, $name, $code, $classId, $index) {

        $data = array(
            'name' => $name,
            'code' => $code,
            'classId' => $classId,
            'index' => $index
        );

        $this->db->where('id', $id);
        $result = $this->db->update("module", $data);

        return $result;
    }

    public function delete_data($id) {

        $data = array(
            'id' => $id
        );

        $result = $this->db->delete("module", $data);

        return $result;
    }

    /**
     * 刪除使用者權限所有與此模組有關權限
     * @param type $id
     */
    public function delete_user_permission($id) {

        $data = array(
            'moduleId' => $id
        );

        $result = $this->db->delete("user_permission", $data);

        return $result;
    }

    /**
     * 刪除角色權限所有與此模組有關權限
     * @param type $id
     */
    public function delete_role_permission($id) {

        $data = array(
            'moduleId' => $id
        );

        $result = $this->db->delete("role_permission", $data);

        return $result;
    }

    /**
     * 取得moduleId
     * @param type $moduleCode
     * @return type
     */
    public function get_moduleId($moduleCode) {

        $this->db->select('*');
        $this->db->from('module');
        $this->db->where('code', $moduleCode);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * 取得權限下的模組
     * 
     * =====array結構說明=====
     * 
     * $myarray = array(
     *             array(
     *                  "className" => "測試",
     *                  "classCode" => "tag",
     *                  "classData" => array(
     *                      array(
     *                          "moduleName" => "檔案上傳",
     *                          "moduleCode" => "upload"
     *                   )
     *              ))
     *          );    
     * 
     */
    public function get_permission_modules() {

        $userData = unserialize($this->session->userdata("userData"));

        //身分為管理員則抓取所有模組
        if ($userData->isAdmin) {

            $menuData = $this->get_permission_module_work(false, $userData->userId);
            return $menuData;
        } else {
            $menuData = $this->get_permission_module_work(true, $userData->userId);
            return $menuData;
        }
    }

    /**
     * 取得menu的module資料
     * @param boolean $validate　true or false
     * @param type $userId 使用者id
     * @return array
     */
    public function get_permission_module_work($validate, $userId) {

        $classData = $this->get_module_class();
        $menuArray = array(); //選單資料

        foreach ($classData as $item) {

            $className = $item["className"];
            $classCode = $item["classCode"];
            $classId = $item["classId"];
            $moduleArray = array();
            $flag = false; //是否加入menu array旗標

            $moduleData = $this->get_module_by_classId($classId);

            //該分類有module資料
            if ($moduleData) {

                $tempModuleArray = array();

                foreach ($moduleData as $resultItem) {

                    $tempModuleArray = array("moduleName" => $resultItem["name"], "moduleCode" => $resultItem["code"]);

                    //是否驗證
                    if ($validate) {

                        if ($this->validate_perssion($userId, $resultItem["id"])) {
                            $flag = true;
                            array_push($moduleArray, $tempModuleArray);
                        }
                    } else {
                        $flag = true;
                        array_push($moduleArray, $tempModuleArray);
                    }
                }

                if ($flag) {
                    $tempData = array("className" => $className, "classCode" => $classCode, "classData" => $moduleArray);
                    $menuArray[] = $tempData;
                }
            }
        }

        return $menuArray;
    }

    /**
     * 取得使用者是否有查看此module權限
     * @param type $userId
     * @param type $moduleId
     * @return boolean
     */
    public function validate_perssion($userId, $moduleId) {

        //取得roleId
        $query = $this->Rel_user_role_model->get_rel($userId);
        $roleId = $query["roleId"];

        //判斷使用者權限
        $userPermission = $this->User_permission_model->get_user_permission($userId, $moduleId, "view");
        if ($userPermission) {
            return true;
        }

        //判斷角色權限
        $rolePermission = $this->Role_permission_model->get_role_permission($roleId, $moduleId, "view");
        if ($rolePermission) {
            return true;
        }

        return false;
    }

    /**
     * 取得模組分類資料
     * @return type
     */
    public function get_module_class() {
        $this->db->select('*');
        $this->db->from('module_class');
        $this->db->order_by("classIndex", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 取得該模組分類的模組
     * @param type $classId 模組分類ID
     * @return type
     */
    public function get_module_by_classId($classId) {

        $this->db->select('module.`name`,module.code,module.id');
        $this->db->from('module');
        $this->db->where('classId', $classId);
        $this->db->order_by("index", "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

}
