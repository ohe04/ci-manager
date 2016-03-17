<?php

class Module_class_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 取得module類別資料
     * @param type $id   module id
     * @return type
     */
    public function get_module_class($id = null) {

        if ($id == null) {
            $this->db->select('*');
            $this->db->from('module_class');
            $this->db->order_by("classIndex", "ASC");
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('module_class');
            $this->db->where('classId', $id);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    /**
     * 查詢module類別是否存在
     * @param type $name  分類名稱
     * @return boolean  true:不存在 false:存在
     */
    public function query_module_class($className) {

        $query = $this->db->get_where("module_class", array('className' => $className));
        $result = $query->row_array();

        if ($result === NULL) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 查詢module是否使用此模組分類
     * @param type $classId 模組分類id
     * @return boolean true:無 false:有使用
     */
    public function query_module($classId) {

        $query = $this->db->get_where("module", array('classId' => $classId));
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
    public function insert_data($className, $classIndex, $classCode) {

        $data = array(
            'className' => $className,
            'classIndex' => $classIndex,
            'classCode' => $classCode
        );

        $result = $this->db->insert("module_class", $data);

        return $result;
    }

    /**
     * 更新資料
     * @param type $classId 資料id
     * @param type $className 分類名稱
     * @param type $classIndex 分類排序
     * @param type $classCode 符號代號
     * @return type boolean 結果
     */
    public function update_data($classId, $className, $classIndex, $classCode) {

        $data = array(
            'className' => $className,
            'classIndex' => $classIndex,
            'classCode' => $classCode
        );

        $this->db->where('classId', $classId);
        $result = $this->db->update("module_class", $data);

        return $result;
    }

    /**
     * 刪除資料
     * @param type $classId 模組類別id
     * @return type
     */
    public function delete_data($classId) {

        $data = array(
            'classId' => $classId
        );

        $result = $this->db->delete("module_class", $data);

        return $result;
    }

}
