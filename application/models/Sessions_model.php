<?php

class Sessions_model extends CI_Model
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
        $this->db->from('sessions');
        if ($id > 0) {
            $this->db->where('session_key', $id);
        }
        $this->db->order_by('session_key', 'desc');
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
        $this->db->from('sessions');
        $this->db->order_by('session_key', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
        //依條件查詢
    public function query_by($cd1, $cd2)
    {

        $this->db->select('*');
        $this->db->from('sessions');
        $this->db->where($cd1, $cd2);
        $this->db->order_by('session_key', 'desc');
        //$this->db->where();
        $query = $this->db->get();
        return $query->result();
    }
         //查詢過期 sessions
    public function query_expire()
    {
        $dueday = new datetime(date('Y-m-d H:i:s', time()));
        $querydate = $dueday->getTimestamp();
        $this->db->select('*');
        $this->db->from('sessions');
        $this->db->where('session_expire <', $querydate);
        $this->db->order_by('session_key', 'desc');
        //$this->db->where();
        $query = $this->db->get();
        $result = $query->result();
        $data = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $index => $key) {
                $data[$index] = $key;
            }
        }
        // 刪除過期 session
        foreach ($data as $index => $key) {
            $this->delete($data[$index]['session_key']);
        }
    }
         //取回 sessions
    public function retrive_session($id)
    {
        $this->db->select('*');
        $this->db->from('sessions');
        $this->db->where('session_key', $id);
        $this->db->order_by('session_key', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        $data = [];
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $index => $key) {
                foreach ($key as $index2 => $value) {
                    $data[$index2] = $value;
                }
            }
        }
        return $data;
    }
        // 寫入
    public function add($data)
    {
        $this->db->insert('sessions', $data);
    }

        // 刪除
    public function delete($key)
    {
        $this->db->where('session_key', $key);
        $this->db->delete('sessions');
    }
}
