<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            // 載入列表 model
            $this->load->model('usertb_model');
            $this->load->model('parttb_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
        }

    public function index()
    {
        $data['function_name'] = "管理功能";
        $data['site'] = $this->title->configvalue;
        $urlpath = '/Admin';
        $this->session->set_userdata('NowURL', $urlpath);
        $login = $this->session->userdata('UserLogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if(empty($login))
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 0)
        {
            $data['message'] = $login['denyreason'];
			// 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('admin_deny');
			$this->load->view('footer');
            
        } elseif ($login['adminauthpass'] == 1)
        {
            echo "登入完成";
        // 載入 view
        $this->load->view('header',$data);
        $this->load->view('admin_index');
        $this->load->view('footer');
        }
    }
}
?>