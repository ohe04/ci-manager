<?php

class Site_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * 判斷帳號密碼
     * @param type $account    帳號
     * @param type $password 密碼
     * @return boolean  結果
     */
    public function get_user($account, $password) {

        $query = $this->db->get_where("users", array('account' => $account, 'password' => $password, "isDelete" => "0"));

        $result = $query->row_array();

        return $result;
    }

    /**
     * 判斷是否為管理者
     * @param type $account  帳號
     * @return boolean            結果
     */
    public function isAdmin($account) {

        $this->db->select('users.account');
        $this->db->from('users');
        $this->db->join('rel_user_role', 'users.userId = rel_user_role.userId', 'left');
        $this->db->join('role', 'role.roleId = rel_user_role.roleId');
        $this->db->where('role.name', "管理者");
        $this->db->where('users.account', $account);
        $query = $this->db->get();
        $result = $query->row_array();

        if ($result === NULL) {
            return false;
        } else {
            return true;
        }
    }

}
