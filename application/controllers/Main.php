<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            // 載入列表 model
            $this->load->model('parttb_model');
            $this->load->model('titletb_model');
            $this->load->model('config_model');
        }

    public function index()
    {
        $data['site'] = $this->config_model->queryby('configkey','myname');
        $data['list'] = $this->titletb_model->query_limit_home();
        print_r($data['site']);

        // 載入 view
        $this->load->view('header');
        // 檢查是否存在 tool_type ，若無則顯示相關資訊
        if(empty($data['list']))
        {
            $this->load->view('main_nolist');
        }
        $this->load->view('main_index',$data);
        $this->load->view('footer');
    }
}
?>