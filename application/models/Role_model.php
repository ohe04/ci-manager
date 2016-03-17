<?php

class Role_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 取得角色
     * @param type $roleId  角色ID
     * @return type
     */
    public function get_roles($roleId = null) {

        if ($roleId == null) {
            $this->db->select('*');
            $this->db->from('role');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('role');
            $this->db->where('roleId', $roleId);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    /**
     * 查詢角色是否存在
     * @param type $name  角色名稱
     * @return boolean
     */
    public function query_role($name) {

        $query = $this->db->get_where("role", array('name' => $name));
        $result = $query->row_array();

        if ($result === NULL) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_data($name) {

        $data = array(
            'name' => $name
        );

        $result = $this->db->insert("role", $data);

        return $result;
    }

    public function update_data($roleId, $name) {

        $data = array(
            'name' => $name
        );

        $this->db->where('roleId', $roleId);
        $result = $this->db->update("role", $data);

        return $result;
    }

    public function delete_data($roleId) {

        $data = array(
            'roleId' => $roleId
        );
        
        $result = $this->db->delete("role", $data);

        return $result;
    }

}
