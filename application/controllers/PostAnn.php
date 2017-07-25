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
        $data['site'] = $this->title->configvalue;
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
                    // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('title', '標題', 'trim|required');
		$this->form_validation->set_rules('comment', '內容', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            $typelist = array (
                "1.1" => "普通",
                "2.1" => "重要",
                "3.1" => "急件"
            );
            $data['urlnum'] = $this->config_model->queryBy('configkey','urlnum');
            $data['ulfilenum'] = $this->config_model->queryBy('configkey','ulfilenum');
            $data['annday'] = $this->config_model->queryBy('configkey','annday');
            $data['user'] = $login;
            $data['typelist'] = $typelist;
            //開始載入表單
            // 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('postann_postannform_edit');
            $this->load->view('postann_postannform_edit_file');
            $this->load->view('postann_postannform_edit_url');
            $this->load->view('postann_postannform_edit_date');
            $this->load->view('postann_postannform_edit_bott');
			$this->load->view('footer');

		} else
        {

        }
        }
        
    }
}
?>