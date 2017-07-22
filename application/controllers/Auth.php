<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            // 載入列表 model
            $this->load->model('usertb_model');
            $this->load->model('parttb_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
        }

    public function postAnnAuth()
    {
        $data['function_name'] = "發布公告檢驗帳號";
        $data['site'] = $this->title->configvalue;
        //取得單位資料
        $data['part'] = $this->parttb_model->query();
        //取得使用者資料
        $data['user'] = $this->usertb_model->query();
        // 宣告陣列，利用 foreach 將查詢結果轉為陣列用於下接選單
        $options = [];
        foreach ($data['part'] as $index => $list)
        {
            $options[$list->partid] = $list->pid . "." .  $list->partname;
        }
        $data['partlist'] = $options;
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('username', '使用者帳號', 'trim|required');
		$this->form_validation->set_rules('userpass', '密碼', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('auth_postannauth');
			$this->load->view('footer');
		}
		else
		{
			// 接收表單
            $formdata['partid'] = $this->input->post('partid');
			$formdata['username'] = $this->input->post('username');
			$formdata['userpass'] = $this->input->post('userpass');
			// 判斷若有設定 md5 加密字串，則密碼比對使用 md5
            $ismd5 = $this->config_model->queryBy('configkey','pwdsalt');
            if (!empty($ismd5)) {
                $formdata['userpass'] = md5($ismd5 . $formdata['userpass']);
            }

			$this->repair_list_model->add($formdata);

			// 回首頁
			redirect('/lists');
		};
    }
}
?>