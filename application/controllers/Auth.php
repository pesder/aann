<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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

    public function postAnnAuth()
    {
        $data['function_name'] = "發布公告檢驗帳號";
        $data['site'] = $this->title->configvalue;
        //取得單位資料
        $data['part'] = $this->parttb_model->query();
        
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
            $denyreason = "";
			// 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryBy('configkey','pwdsalt');
            $ismd5 = $md5key->configvalue;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }
            //取得使用者資料
            $data['user'] = $this->usertb_model->matchPassword('username', $formdata['username']);
            if ($formdata['partid'] != $data['user']['partid']) {
                $denyreason = "您無法發布這個單位的公告。";
            }
			// 比對密碼
            if ($formdata['userpass'] == $data['user']['userpass'] && $formdata['partid'] == $data['user']['partid'])
            {
                echo "You Pass!";
                $result = array (
                    'authpass' => "1",
                    'denyreason' => $denyreason,
                    'partid' => $data['user']['partid'],
                    'username' => $data['user']['username'],
                    'realname' => $data['user']['realname']
                );
                $this->session->set_userdata('UserLogin', $result);
            } else 
            {
                $denyreason = $denyreason . "帳號或密碼有誤，請再試一次";
                $result = array (
                    'authpass' => "0",
                    'denyreason' => $denyreason,
                    'partid' => "",
                    'username' => "",
                    'realname' => ""
                );
                $this->session->set_userdata('UserLogin', $result);
                echo "You can NOT Pass!!";
            }

			// 回首頁
			redirect('/PostAnn/postAnnForm');
		};
    }
}
?>