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
            // 讀取網站名稱
            $title = $this->config_model->queryBy('configkey','myname');
        }

    public function index()
    {
        $data['site'] = $this->config_model->queryBy('configkey','myname');
        $data['list'] = $this->titletb_model->queryLimitHome();
        $data['pages'] =$this->titletb_model->countPage(50);
        print_r($data['site']);
        print_r($data['pages']);

        // 載入 view
        $this->load->view('header');
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['list']))
        {
            $this->load->view('main_nolist');
        }
        $this->load->view('main_index',$data);
        $this->load->view('footer');
    }
    public function viewAnn($id)
    {
        // 載入 anntb，為公告本文資料表
        $this->load->model('anntb_model');
        $this->load->model('usertb_model');
        // 查詢公告標題資訊與本文
        $data['site'] = $this->config_model->queryBy('configkey','myname');
        $data['head'] = $this->titletb_model->query($id);
        $data['body'] = $this->anntb_model->query($id);
        $this->titletb_model->addHit($id, $data['head']);

        // 載入 view
        $this->load->view('header');
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['body']))
        {
            $this->load->view('viewann_nolist');
        }
        $this->load->view('main_viewann',$data);
        $this->load->view('footer');    
    }
}
?>