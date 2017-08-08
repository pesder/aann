<?php

class Titletb_model extends CI_Model {
	
		public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                $this->load->library('session');

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
            $result = $query->row();
            return $result->posttime;
        } 
        //有限查詢給 feed
        public function feedAnn($limitdays) 
        {
            
            $dueday = new datetime(date('Y-m-d H:i:s', time()));
            $offset = '-' . $limitdays . "day";
            $dueday->modify($offset);
            $querydate = $dueday->format('Y-m-d H:i:s');
            
            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->where('posttime >=', $querydate);
            $this->db->order_by('tid','desc');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
         //有限查詢給 feed
        public function joinFeedAnn($limitdays) 
        {
            
            $dueday = new datetime(date('Y-m-d H:i:s', time()));
            $offset = '-' . $limitdays . "day";
            $dueday->modify($offset);
            $querydate = $dueday->format('Y-m-d H:i:s');
            
            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->join('anntb', 'anntb.tid = titletb.tid');
            $this->db->where('posttime >=', $querydate);
            $this->db->order_by('posttime','desc');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
        //同時查詢 titletb anntb
        public function joinSearch($limit, $annoffset) 
        { 
            //取得搜尋日數
            $limitdays = $this->session->userdata('ann_days');
            $dueday = new datetime(date('Y-m-d H:i:s', time()));
            $offset = '-' . $limitdays . "day";
            $dueday->modify($offset);
            $querydate = $dueday->format('Y-m-d H:i:s');
            // 取得處室或搜尋狀況
            $part = $this->session->userdata('selected_part');
            $search = $this->session->userdata('serach_keyword');
            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->join('anntb', 'anntb.tid = titletb.tid');
            if (!empty($part))
            {
                $this->db->where('partid', $part);
            }
            if (!empty($search))
            {
            $this->db->like('subject', $search);
            $this->db->or_like('comment', $search);
            }
            $this->db->where('posttime >=', $querydate);
            $this->db->order_by('posttime','desc');
            $this->db->limit($limit, $annoffset);
            $query = $this->db->get();
            $result = $query->result();
            
            //計算總頁數，並以 session 回傳數值
            $this->db->select('*');
            $this->db->from('titletb');
            $this->db->join('anntb', 'anntb.tid = titletb.tid');
            if (!empty($part))
            {
                $this->db->where('partid', $part);
            }
            if (!empty($search))
            {
            $this->db->like('subject', $search);
            $this->db->or_like('comment', $search);
            }
            $this->db->where('posttime >=', $querydate);
            $total = $this->db->count_all_results();
            $pp1 = $limit;
            $pages = ceil( $total / $pp1);
            $totalpages = array (
                "total" => $total,
                "pages" => $pages
            );
            $this->session->set_userdata('TotalPages', $totalpages);
            return $result;
        }
        // 寫入公告標題對應，傳回 id 供內文使用
        public function writeTitle($data)
        {
            $this->db->insert('titletb', $data);
            return $this->db->insert_id();
        } 
        // 更新公告標題
        public function modify($tid, $data)
        {
            $this->db->where('tid', $tid);
            $this->db->update('titletb', $data);
        }
        // 刪除
        public function delete($tid)
        {
            $this->db->where('tid', $tid);
            $this->db->delete('titletb');
        }
        // 刪除處室全部貼文
        public function destory($partid)
        {
            $this->db->where('partid', $partid);
            $this->db->delete('titletb');
        }
}