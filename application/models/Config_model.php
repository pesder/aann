<?php

class Config_model extends CI_Model {
	
		public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();

                //連結資料庫
                $this->load->database();
        }

        //查詢
        public function query() 
        {

            $this->db->select('*');
            $this->db->from('config');
            $this->db->order_by('configkey','desc');
            $query = $this->db->get();
            return $query->result();
        }
        //有限查詢
        public function queryLimit($limit) 
        {

            $this->db->select('*');
            $this->db->from('config');
            $this->db->order_by('configkey','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('config');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('sort','asc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }
        //查詢處室名稱清單
        public function queryValue($key) 
        {
        	$this->db->select('*');
        	$this->db->from('config');
        	$this->db->where('configkey', $key);
        	$query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row)
                {
                    $data = $row['configvalue'];
                }
            }
                return $data;
        }
        // 修改
        public function modify($data, $crit)
        {
            $this->db->where('configkey', $crit);
            $this->db->update('config', $data);
        }
        // 修改多個值
        public function modifyMulti($data, $crit)
        {
            $this->db->update_batch('config', $data, $crit);
        }

}