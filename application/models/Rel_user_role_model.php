<?php

class Rel_user_role_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 新增帳號跟腳色關聯資料
     * @param type $userId   使用者ID
     * @param type $roleId   角色ID
     * @return type
     */
    public function insert_rel($userId, $roleId) {

        $data = array(
            'userId' => $userId,
            'roleId' => $roleId
        );

        $result = $this->db->insert("rel_user_role", $data);
        return $result;
    }

    /**
     * 取得使用者跟角色關聯資料
     * @param type $userId  使用者ID
     * @return type               結果
     */
    public function get_rel($userId) {

        $query = $this->db->get_where("rel_user_role", array('userId' => $userId));

        $result = $query->row_array();

        return $result;
    }

    /**
     * 刪除使用者跟角色關聯資料
     * @param type $userId  使用者ID
     * @return type               結果
     */
    public function delete_rel($userId) {

        $data = array(
            'userId' => $userId
        );

        $result = $this->db->delete('rel_user_role', $data);

        return $result;
    }

    /**
     * 查詢系統角色是否有資料
     * @param type $roleId  角色ID
     * @return boolean
     */
    public function query_by_roleId($roleId) {

        $query = $this->db->get_where("rel_user_role", array('roleId' => $roleId));
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

}
