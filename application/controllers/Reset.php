<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
			$this->load->library('email');
            // 載入列表 model
            $this->load->model('usertb_model');
            $this->load->model('sessions_model');

            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
            $this->admin = $this->config_model->queryBy('configkey','site_admin');
            $this->email = $this->config_model->queryBy('configkey','site_email');
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
		
	}

    public function requestPassword($id = 0)
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "重設密碼";
        $data['site'] = $this->title->configvalue;
        if ($id != 0) {
            $this->session->set_userdata('updatemember', $id);
        }
        $uid = $this->session->userdata('updatemember');
        // 若沒有 userid 值，則跳回管理頁面
        if (empty($uid))
        {
            redirect('/Admin');
        }
        $data['userdata'] = $this->usertb_model->query($uid);
        // 建立通關密碼
        $pass_code = md5(uniqid());
        // 設定逾期時間
        $dueday = new datetime(date('Y-m-d H:i:s', time()));
        $offset = '+' . 7 . "day";
        $dueday->modify($offset);
        $expiredate = $dueday->format('Y-m-d H:i:s');
        // 產生 session key
        $sessionkey = substr(md5("pass" . $uid),0,32);
        // 產生 session 內容
        $session_content = array (
            'userid'    =>  $uid,
            'pass_code' =>  $pass_code
        );
        $reset_session = array (
            'session_key'   =>  $sessionkey,
            'session_expire'    =>  $expiredate,
            'session_value' =>  $session_content
        );
        // 寫入資料庫
        $this->sessions_model->add($reset_session);
        // 設定郵件參數

        // 準備寄信
        $this->email->from($this->email->configvalue, $this->admin->configvalue);
        $this->email->to($data['userdata']->email);

        $this->email->subject('密碼重設 - ' . $this->title->configvalue);
        $this->email->message('Testing the email class.');

        $this->email->send();
    }
}
