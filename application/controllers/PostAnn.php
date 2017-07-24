<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostAnn extends CI_Controller {

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
            $this->load->model('titletb_model');
            $this->load->model('anntb_model');
            $this->load->model('filetb_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
        }

    public function postAnnForm() 
    {
        $data['function_name'] = "發布公告表單";
        $login = $this->session->userdata('UserLogin');
        print_r($login);
        if(empty($login))
        {
            redirect('/Main');
        }
        elseif ($login['authpass'] == 0)
        {
            $data['message'] = $login['denyreason'];
			// 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('postann_postannform_deny');
			$this->load->view('footer');
            
        } elseif ($login['authpass'] == 1)
        {
            $data['user'] = $login;
            //開始載入表單
            // 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('postann_postannform_edit');
			$this->load->view('footer');
        }
        
    }
}
?>