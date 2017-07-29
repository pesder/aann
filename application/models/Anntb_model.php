<?php

class Anntb_model extends CI_Model {
	
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
        	$this->db->from('anntb');
        	if ($id > 0) 
        	{
        		$this->db->where('tid', $id);
        	}
        	$this->db->order_by('tid','desc');
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
            $this->db->from('anntb');
            $this->db->order_by('tid','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('anntb');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('tid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }
        // 寫入公告本文對應
        public function writeAnn($data)
        {
            $this->db->insert('anntb', $data);
        }
        // 修改
        public function modify($tid, $data)
        {
            $this->db->where('tid', $tid);
            $this->db->update('anntb', $data);
        }
        // 刪除
        public function delete($tid)
        {
            $this->db->where('tid', $tid);
            $this->db->delete('anntb');
        }

}