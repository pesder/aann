<?php

class Usertb_model extends CI_Model {
	
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
        	if ($id > 0) 
        	{
        		$this->db->where('userid', $id);
        	}
        	$this->db->order_by('userid','desc');
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
            $this->db->from('usertb');
            $this->db->order_by('userid','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('usertb');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('userid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->row_array();
        }
        //單一姓名查詢
        public function querySingleName($id) 
        {
            $this->db->select('realname');
            $this->db->from('usertb');
            $this->db->where('userid', $id);
            $query = $this->db->get();
            return $query->row();
        }


}