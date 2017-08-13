<?php

class Openidbind_model extends CI_Model {
	
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
        	$this->db->from('openidbind');
        	if ($id > 0) 
        	{
        		$this->db->where('oid', $id);
        	}
        	$this->db->order_by('oid','desc');
        	//$this->db->where();
        	$query = $this->db->get();
        	$date = $query->result();
        	// 回傳
        	return $query->row();
        }
        //有限查詢
        public function queryLimit($limit) 
        {

            $this->db->select('*');
            $this->db->from('openidbind');
            $this->db->order_by('oid','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('openidbind');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('oid','desc');
            //$this->db->where();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            }
        }
        //查詢核准使用者
        public function showUser() 
        {
            $this->db->select('*');
            $this->db->from('openidbind');
            $this->db->where('bind_userid', '');
            $this->db->where('new', '0');
            $this->db->where('banned', '0');
            $this->db->order_by('oid','desc');
            //$this->db->where();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            }
        }
        //查詢綁定使用者
        public function showBindUser() 
        {
            $this->db->select('*');
            $this->db->from('openidbind');
            $this->db->where('bind_userid IS NOT NULL');
            $this->db->where('bind_userid !=', '0');
            $this->db->order_by('oid','desc');
            //$this->db->where();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            }
        }
        // 檢查使用者
        public function checkUser($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('openidbind');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('oid','desc');
            //$this->db->where();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        //單一姓名查詢
        public function querySingleName($id) 
        {
            $this->db->select('fullname');
            $this->db->from('openidbind');
            $this->db->where('oid', $id);
            $query = $this->db->get();
            return $query->row();
        }
        //查詢處室名稱清單
        public function queryMember($pid) 
        {
        	$this->db->select('*');
        	$this->db->from('openidbind');
            $this->db->where('bind_userid', $pid);
        	$this->db->order_by('oid','asc');
        	$query = $this->db->get();
            $data = [];
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row)
                {
                    $data[$row['oid']] = $row['realname'] . " (" . $row['username'] . ")";
                }
            }
            return $data;
        }

        // 寫入
        public function add($data)
        {
            $this->db->insert('openidbind', $data);
            return $this->db->insert_id();
        }
        // 修改
        public function modify($uid, $data)
        {
            $this->db->where('oid', $uid);
            $this->db->update('openidbind', $data);
        }
        // 刪除
        public function delete($uid)
        {
            $this->db->where('oid', $uid);
            $this->db->delete('openidbind');
        }

}