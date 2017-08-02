<?php

class Parttb_model extends CI_Model {
	
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
        	$this->db->from('parttb');
        	if ($id > 0) 
        	{
        		$this->db->where('partid', $id);
        	}
        	$this->db->order_by('partid','asc');
        	//$this->db->where();
        	$query = $this->db->get();
        	$date = $query->result();
        	// 回傳
        	if ($id > 0)
        	{
        		return $query->row();
        	}
        	else
        	{
        		return $query->result();
        	}
        }
        //有限查詢
        public function queryLimit($limit) 
        {

            $this->db->select('*');
            $this->db->from('parttb');
            $this->db->order_by('partid','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('parttb');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('partid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }
        //查詢部門名稠
        public function queryPartname($cd1) 
        {

            $this->db->select('partname');
            $this->db->from('parttb');
            $this->db->where('partid', $cd1);
            $this->db->order_by('partid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->row_array();
        }
        //查詢處室名稱清單
        public function queryList() 
        {
        	$this->db->select('*');
        	$this->db->from('parttb');
        	$this->db->order_by('partid','asc');
        	$query = $this->db->get();
            $data = [];
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row)
                {
                    $data[$row['partid']] = $row['pid'] . "." . $row['partname'];
                }
            }
                return $data;
        }
        // 寫入處室資料
        public function add($data)
        {
            $this->db->insert('parttb', $data);
        }
       // 寫入處室資料 - 傳回 id
        public function add_id($data)
        {
            $this->db->insert('parttb', $data);
            return $this->db->insert_id();
        }
        // 更新處室
        public function modify($partid, $data)
        {
            $this->db->where('partid', $partid);
            $this->db->update('parttb', $data);
        }
        // 刪除處室
        public function delete($partid)
        {
            $this->db->where('partid', $partid);
            $this->db->delete('parttb');
        }
}