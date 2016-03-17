<?php

class User_permission_model extends CI_Model {

    public function __construct() {
        $this->load->database();
        $this->load->model('Module_model');
    }

    /**
     * 初始化權限
     * @param type $userId
     */
    public function ini_permission($userId) {

        $data = array(
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        );

        $this->db->where('userId', $userId);
        $result = $this->db->update('user_permission', $data);

        if (!$result) {
            throw new Exception("更新資料庫錯誤");
        }
    }

    /**
     * 更新權限
     * @param type $field             欄位名稱
     * @param type $userId           使用者id
     * @param type $moduleId      模組id
     */
    public function update_permission($field, $userId, $moduleId) {

        $data = array(
            $field => 1
        );

        $this->db->where('userId', $userId);
        $this->db->where('moduleId', $moduleId);
        $result = $this->db->update('user_permission', $data);

        if (!$result) {
            throw new Exception("更新資料庫錯誤");
        }
    }

    /**
     * 判斷模組&使用者權限資料是否存在,不存在則新增
     * @param type $userId
     */
    public function detect_permission($userId) {

        $this->db->select('*');
        $this->db->from('module');
        $query = $this->db->get();
        $moduleResult = $query->result_array();

        foreach ($moduleResult as $item) {

            $moduleId = $item["id"];

            $queryResult = $this->query_module_user($userId, $moduleId);

            if (!$queryResult) {
                $this->insert_data($userId, $moduleId);
            }
        }
    }

    /**
     * 查詢模組跟 使用者是否存在資料
     * @param type $userId       使用者ID
     * @param type $moduleId  模組ID
     * @return boolean
     */
    public function query_module_user($userId, $moduleId) {

        $query = $this->db->get_where("user_permission", array('userId' => $userId, 'moduleId' => $moduleId));
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 儲存初始化資料
     * @param type $userId
     * @param type $moduleId
     */
    public function insert_data($userId, $moduleId) {

        $data = array(
            'userId' => $userId,
            'moduleId' => $moduleId,
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0,
        );

        $result = $this->db->insert("user_permission", $data);
    }

    public function get_permission_view($userId) {

//        $this->db->select('user_permission.`view`,user_permission.`create`,user_permission.edit,user_permission.`delete`,module.`name`,module.`id`');
//        $this->db->from('module');
//        $this->db->join('user_permission', 'module.id = user_permission.moduleId');
//        $this->db->where('user_permission.userId', $userId);
//        $query = $this->db->get();
//        return $query->result_array();

        $classData = $this->Module_model->get_module_class();
        $dataArray = array(); //模組資料

        foreach ($classData as $item) {
            
            $className = $item["className"];
            $classId = $item["classId"];
            $moduleArray = array();
            
            $moduleData = $this->get_module_user_rel($classId, $userId);
            
            if($moduleData){
                
                $tempModuleArray = array();
                
                foreach($moduleData as $moduleItem){
                    
                    $tempModuleArray = array("moduleName" => $moduleItem["name"],"moduleId" => $moduleItem["id"],"view" => $moduleItem["view"],"create" => $moduleItem["create"],"edit" => $moduleItem["edit"],"delete" => $moduleItem["delete"]);    
                    array_push($moduleArray, $tempModuleArray);                    
                }              
                
                $tempDataArray = array("className"=>$className,"classData" => $moduleArray);
                array_push($dataArray, $tempDataArray );                
            }                 
        }
        
        return $dataArray;
    }

    /**
     * 取得模組跟使用者權限資料表關聯資料
     * @param type $moduleId
     * @param type $userId
     */
    public function get_module_user_rel($classId, $userId) {

        $this->db->select('user_permission.`view`,user_permission.`create`,user_permission.edit,user_permission.`delete`,module.`name`,module.`id`');
        $this->db->from('module');
        $this->db->join('user_permission', 'module.id = user_permission.moduleId');
        $this->db->where('module.classId', $classId);
        $this->db->where('user_permission.userId', $userId);      
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 取得使用者在模組,是否有此操作權限
     * @param type $userId          使用者Id
     * @param type $moduleId     模組Id
     * @param type $type             action動作
     * @return boolean
     */
    public function get_user_permission($userId, $moduleId, $type) {

        $query = $this->db->get_where("user_permission", array('userId' => $userId, 'moduleId' => $moduleId, $type => "1"));
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

}
