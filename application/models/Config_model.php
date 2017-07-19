<?php

class Config_model extends CI_Model {
	
		public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();

                //連結資料庫
                $this->load->database();
        }

        //有限查詢
        public function query_limit($limit) 
        {

            $this->db->select('*');
            $this->db->from('config');
            $this->db->order_by('configkey','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryby($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('config');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('configkey','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }



}