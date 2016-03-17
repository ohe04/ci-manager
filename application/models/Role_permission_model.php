<?php

class Role_permission_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 初始化權限
     * @param type $roleId
     */
    public function ini_permission($roleId) {

        $data = array(
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0
        );

        $this->db->where('roleId', $roleId);
        $result = $this->db->update('role_permission', $data);

        if (!$result) {
            throw new Exception("更新資料庫錯誤");
        }
    }

    /**
     * 更新權限
     * @param type $field             欄位名稱
     * @param type $roleId         使用者id
     * @param type $moduleId     模組id
     */
    public function update_permission($field, $roleId, $moduleId) {

        $data = array(
            $field => 1
        );

        $this->db->where('roleId', $roleId);
        $this->db->where('moduleId', $moduleId);
        $result = $this->db->update('role_permission', $data);

        if (!$result) {
            throw new Exception("更新資料庫錯誤");
        }
    }

    /**
     * 判斷模組&使用者權限資料是否存在,不存在則新增
     * @param type $roleId
     */
    public function detect_permission($roleId) {

        $this->db->select('*');
        $this->db->from('module');
        $query = $this->db->get();
        $moduleResult = $query->result_array();

        foreach ($moduleResult as $item) {

            $moduleId = $item["id"];

            $queryResult = $this->query_module_role($roleId, $moduleId);

            if (!$queryResult) {
                $this->insert_data($roleId, $moduleId);
            }
        }
    }

    /**
     * 查詢模組跟 使用者是否存在資料
     * @param type $roleId      使用者ID
     * @param type $moduleId  模組ID
     * @return boolean
     */
    public function query_module_role($roleId, $moduleId) {

        $query = $this->db->get_where("role_permission", array('roleId' => $roleId, 'moduleId' => $moduleId));
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 儲存初始化資料
     * @param type $roleId
     * @param type $moduleId
     */
    public function insert_data($roleId, $moduleId) {

        $data = array(
            'roleId' => $roleId,
            'moduleId' => $moduleId,
            'view' => 0,
            'create' => 0,
            'edit' => 0,
            'delete' => 0,
        );

        $result = $this->db->insert("role_permission", $data);
    }

    public function get_permission_view($roleId) {

//        $this->db->select('role_permission.`view`,role_permission.`create`,role_permission.edit,role_permission.`delete`,module.`name`,module.`id`');
//        $this->db->from('module');
//        $this->db->join('role_permission', 'module.id = role_permission.moduleId');
//        $this->db->where('role_permission.roleId', $roleId);
//        $query = $this->db->get();
//        return $query->result_array();
     
        $classData = $this->Module_model->get_module_class();
        $dataArray = array(); //模組資料

        foreach ($classData as $item) {
            
            $className = $item["className"];
            $classId = $item["classId"];
            $moduleArray = array();
            
            $moduleData = $this->get_module_role_rel($classId, $roleId);
            
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
     * 取得模組跟角色權限資料表關聯資料
     * @param type $moduleId
     * @param type $userId
     */
    public function get_module_role_rel($classId, $roleId) {

        $this->db->select('role_permission.`view`,role_permission.`create`,role_permission.edit,role_permission.`delete`,module.`name`,module.`id`');
        $this->db->from('module');
        $this->db->join('role_permission', 'module.id = role_permission.moduleId');
        $this->db->where('module.classId', $classId);
        $this->db->where('role_permission.roleId', $roleId);      
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 取得角色在模組,是否有此操作權限
     * @param type $roleId          使用者Id
     * @param type $moduleId     模組Id
     * @param type $type             action動作
     * @return boolean
     */
    public function get_role_permission($roleId, $moduleId, $type) {

        $query = $this->db->get_where("role_permission", array('roleId' => $roleId, 'moduleId' => $moduleId, $type => "1"));
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

}
