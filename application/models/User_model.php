<?php

class User_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 取得使用者
     * @param type $id  使用者ID
     * @return type        使用者資料
     */
    function get_users($userId = null) {

        if ($userId == null) {
            $this->db->select('users.userId,users.account,users.isDelete,role.`name`');
            $this->db->from('users');
            $this->db->join('rel_user_role', 'rel_user_role.userId = users.userId');
            $this->db->join('role', 'role.roleId = rel_user_role.roleId');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $this->db->select('users.userId,users.account,users.isDelete,role.`name`');
            $this->db->from('users');
            $this->db->join('rel_user_role', 'rel_user_role.userId = users.userId');
            $this->db->join('role', 'role.roleId = rel_user_role.roleId');
            $this->db->where('users.userId', $userId);
            $query = $this->db->get();
            return $query->row_array();
        }
    }

    /**
     * 查詢使用者是否存在
     * @param type $account
     * @param type $pwd
     * @return boolean
     */
    public function query_user($account, $pwd = null) {

        if ($pwd == null) {
            $query = $this->db->get_where("users", array('account' => $account));
        } else {
            $query = $this->db->get_where("users", array('account' => $account, 'password' => $pwd));
        }

        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 修改密碼
     * @param type $account  帳號
     * @param type $pwd       密碼
     * @return type                 結果
     */
    public function change_password($account, $pwd) {

        $data = array(
            'password' => $pwd
        );

        $this->db->where('account', $account);
        $result = $this->db->update('users', $data);
        return $result;
    }

    /**
     * 新增帳號
     * @param type $account     
     * @param type $password
     * @param type $isDelete
     * @return type
     */
    public function insert_data($account, $password, $isDelete) {

        date_default_timezone_set("Asia/Taipei");

        $data = array(
            'account' => $account,
            'password' => $password,
            'isDelete' => (int) $isDelete,
            'cDate' => date("Y-m-d H:i:s")
        );

        $result = $this->db->insert("users", $data);
        return $result;
    }

    /**
     * 編輯帳號資料
     * @param type $userId
     * @param type $password
     * @param type $isDelete
     * @return type
     */
    public function edit_data($userId, $isDelete) {

        $data = array(
            'isDelete' => (int) $isDelete
        );

        $this->db->where('userId', $userId);

        $result = $this->db->update('users', $data);

        return $result;
    }

    /**
     * 刪除資料
     * @param type $id 刪除的索引
     * @return type    操作結果
     */
    function delete_data($userId) {

        $data = array(
            'userId' => $userId
        );

        $result = $this->db->delete('users', $data);

        return $result;
    }

    public function edit_user_pwd($userId, $password) {

        $data = array(
            'password' => $password
        );

        $this->db->where('userId', $userId);

        $result = $this->db->update('users', $data);

        return $result;
    }

}
