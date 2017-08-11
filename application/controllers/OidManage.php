<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OidManage extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            // 載入列表 model
            $this->load->model('usertb_model');
            $this->load->model('openbind_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
            // 設定目前網址，供認證後跳回
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
               
        }
    public function auth()
    {
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if(empty($login))
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 0)
        {
            redirect('/Auth/adminAuth');
        }
    }
    public function index()
    {
        $data['function_name'] = "OpenID管理功能";
        $data['site'] = $this->title;
        $data['newuser'] = $this->openidbind_model->query_By('new', '1');
        $data['h1'] = "使用者功能";
        $data['h1group'] = array (
                '/Admin/createPart' =>  "新增一個處室",
                '/Admin/updatePart1' =>  "修改處室資料",
                '/Admin/addMember'  =>  "新增一位組員",
                '/Admin/updateMember1'  =>  "修改組員資料",
                '/Admin/enableMember'   => "啟用一位組員"
        );
        $data['h2'] = "網站功能";
        $data['h2group'] = array (
                '/Admin/updateSite' =>  "變更網站設定",
                '/Admin/updateAnn' =>  "變更公告設定",
                '/Admin/updateSMTP' =>  "變更郵件設定",
        );
        //進行認證
        $this->auth();
        // 載入 view
        $this->load->view('header',$data);
        $this->load->view('oidmanage_index');
        $this->load->view('footer');
    }

	
}
