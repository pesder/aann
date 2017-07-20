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
        $data['realname'] = $this->usertb_model->querySingleName($data['body']->userid);
        $this->titletb_model->addHit($id, $data['head']);
        // 遮蔽 IP 資料
        if(filter_var($data['body']->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            {
               $data['body']->ip = preg_replace('/(?!\d{1,3}\.\d{1,3}\.)\d/', '?', $data['body']->ip);
            } elseif (filter_var($data['body']->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            {
               $data['body']->ip = preg_replace('/(?!\d{1,4}\:)\d/', '?', $data['body']->ip);
            }
        //判斷 URL
        if (empty($data['body']->url)) {
            $data['hasurl'] = "無";
        } else
        {
            $data['hasurl'] = "有";
            $data['annurl'] = explode(" ", $data['body']->url);
        }
        //判斷附件
        if (empty($data['body']->filename)) {
            $data['hasfile'] = "無";
        } else
        {
            $data['hasfile'] = "有";
            $data['annfile'] = explode(" ", $data['body']->filename);
        }
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
    public function download($filename = NULL,$pid,$uid) 
    {
        // load download helder
        $this->load->helper('url');
        $this->load->helper('download');
        // read file contents
        $data = file_get_contents(base_url('/files/' . $pid . '/' . $uid . '/' .$filename));
        force_download($filename, $data);
    }
}
?>