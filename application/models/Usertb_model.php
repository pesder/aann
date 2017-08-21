<?php

class Usertb_model extends CI_Model
{
    
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();

        //連結資料庫
        $this->load->database();
    }

        //查詢
    public function query($id = 0)
    {

        $this->db->select('*');
        $this->db->from('usertb');
        if ($id > 0) {
            $this->db->where('userid', $id);
        }
        $this->db->order_by('userid', 'desc');
        //$this->db->where();
        $query = $this->db->get();
        $date = $query->result();
        // 回傳
        if ($id > 0) {
            return $query->row();
        } else {
            return $query->result();
        }
    }
        //有限查詢
    public function query_limit($limit)
    {

        $this->db->select('*');
        $this->db->from('usertb');
        $this->db->order_by('userid', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
        
        //依條件查詢
    public function query_by($cd1, $cd2)
    {

        $this->db->select('*');
        $this->db->from('usertb');
        $this->db->where($cd1, $cd2);
        $this->db->order_by('userid', 'desc');
        //$this->db->where();
        $query = $this->db->get();
        return $query->row();
    }
        //依查詢密碼
    public function match_password($cd1, $cd2)
    {

        $this->db->select('*');
        $this->db->from('usertb');
        $this->db->where($cd1, $cd2);
        $this->db->order_by('userid', 'desc');
        //$this->db->where();
        $query = $this->db->get();
        return $query->row_array();
    }
        //單一姓名查詢
    public function query_single_name($id)
    {
        $this->db->select('realname');
        $this->db->from('usertb');
        $this->db->where('userid', $id);
        $query = $this->db->get();
        return $query->row();
    }
        //查詢處室名稱清單
    public function query_member($pid)
    {
        $this->db->select('*');
        $this->db->from('usertb');
        $this->db->where('partid', $pid);
        $this->db->order_by('userid', 'asc');
        $query = $this->db->get();
        $data = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['userid']] = $row['realname'] . " (" . $row['username'] . ")";
            }
        }
        return $data;
    }
        //查詢使用者清單
    public function query_user()
    {
        // 列出已停用使用者
        $this->db->select('userid');
        $this->db->from('usertb');
        $this->db->where('partid', '0');
        $query = $this->db->get();
        $except = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $index => $row) {
                $except[] = $row['userid'];
            }
        }
        // 列出已綁定使用者
        $this->db->select('bind_userid');
        $this->db->from('openidbind');
        $this->db->where('bind_userid IS NOT NULL');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $index => $row) {
                $except[] = $row['bind_userid'];
            }
        }
        // 查詢使用者資料，排除已停用或已綁定之使用者
        $this->db->select('*');
        $this->db->from('usertb');
        $this->db->where_not_in('userid', $except);
        $this->db->order_by('partid', 'asc');
        $query = $this->db->get();
        $data = array('' => '請選擇使用者' );
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[$row['userid']] = $row['realname'] . " (" . $row['username'] . ")";
            }
        }
        return $data;
    }

        // 寫入
    public function add($data)
    {
        $this->db->insert('usertb', $data);
        return $this->db->insert_id();
    }
        // 修改
    public function modify($uid, $data)
    {
        $this->db->where('userid', $uid);
        $this->db->update('usertb', $data);
    }
        // 刪除
    public function delete($uid)
    {
        $this->db->where('userid', $uid);
        $this->db->delete('usertb');
    }
        // 刪除全處室使用者
    public function destory($partid)
    {
        $this->db->where('partid', $partid);
        $this->db->delete('usertb');
    }
}
