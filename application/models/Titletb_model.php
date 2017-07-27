<?php

class Titletb_model extends CI_Model {
	
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
        	$this->db->from('titletb');
        	if ($id > 0) 
        	{
        		$this->db->where('tid', $id);
        	}
        	$this->db->order_by('tid','desc');
        	//$this->db->where();
        	$query = $this->db->get();
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
        //首頁查詢
        public function queryLimitHome($limit) 
        {

            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->order_by('posttime','desc');
            $this->db->limit($limit);
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                return $query->result();
            }
        }        
        //有限查詢
        public function queryLimit($limit, $offset) 
        {

            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->order_by('tid','desc');
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result();
        }
        //依條件查詢
        public function queryBy($cd1, $cd2) 
        {

            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->where($cd1, $cd2);
            $this->db->order_by('tid','desc');
            //$this->db->where();
            $query = $this->db->get();
            return $query->result();
        }
        //頁數計算
        public function countPage($pp1) 
        {
            $total = $this->db->count_all('titletb');
            $pages = ceil( $total / $pp1);
            $result = array (
                "total" => $total,
                "pages" => $pages
            );
            return $result;
        }
        // 增加點擊數
        public function addHit($tid)
        {
        	$this->db->select('hits');
        	$this->db->from('titletb');
       		$this->db->where('tid', $tid);
        	$query = $this->db->get();
            $hits = $query->row_array();
            $hits['hits'] = $hits['hits'] + 1;
            
            $this->db->where('tid', $tid);
            $this->db->update('titletb', $hits);

        }
        //設定最新一篇文章
        public function queryLastAnn() 
        {
            $this->db->select('tid');
            $this->db->from('titletb');
            $this->db->order_by('tid','desc');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->row();
        } 
        // 寫入公告標題對應，傳回 id 供內文使用
        public function writeTitle($data)
        {
            $this->db->insert('titletb', $data);
            return $this->db->insert_id();
        } 

}