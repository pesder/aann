<?php

class Filetb_model extends CI_Model {
	
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
        	$this->db->from('filetb');
        	if ($id > 0) 
        	{
        		$this->db->where('fid', $id);
        	}
        	$this->db->order_by('fid','desc');
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
            $this->db->from('filetb');
            $this->db->order_by('fid','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('filetb');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('fid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }
        //搜尋檔案名稱對應
        public function mathFile($pid,$uid,$filename)
        {
            $this->db->select('*');
            $this->db->from('filetb');
            //$this->db->like($filename);
            $this->db->where('partid',$pid);
            $this->db->where('userid',$uid);
            $this->db->where('filelist',$filename);
            $query = $this->db->get();
            return $query->row();
        }

}