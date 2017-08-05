<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            
            // 載入列表 model
            $this->load->model('config_model');
            $this->load->model('usertb_model');
            $this->load->model('sessions_model');
            
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
            $this->admin = $this->config_model->queryValue('site_admin');
            $this->mail = $this->config_model->queryValue('site_mail');
            
            $this->smtphost = $this->config_model->queryValue('smtp_host');
            $this->smtpuser = $this->config_model->queryValue('smtp_user');
            $this->smtppass = $this->config_model->queryValue('smtp_pass');
            $this->smtpport = $this->config_model->queryValue('smtp_port');
            
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
    // 產生通關密碼並寄出密碼信
    public function requestPassword($id = 0)
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "重設密碼";
        $data['site'] = $this->title;
        if ($id != 0) {
            //$this->session->set_userdata('updatemember', $id);
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
        $expiredate = $dueday->getTimestamp();
        // 產生 session key
        $sessionkey = substr(md5("pass" . $uid . time()),0,32);
        // 產生 session 內容
        $session_content = array (
            'userid'    =>  $uid,
            'pass_code' =>  $pass_code
        );
        $reset_session = array (
            'session_key'   =>  $sessionkey,
            'session_expire'    =>  $expiredate,
            'session_value' =>  serialize($session_content)
        );

        // 設定郵件參數
        $this->load->library('email');
        $this->load->library('encrypt');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $this->smtphost;
        $config['smtp_port'] = $this->smtpport;
        $config['smtp_user'] = $this->smtpuser;
        $config['smtp_pass'] = $this->smtppass;
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['smtp_timeout'] = '30';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = FALSE;
        
        $this->email->initialize($config);
        
        
        // 準備郵件本文
        $message = "<h1>" . $this->title . "密碼重設郵件</h1>";
        $message .= "<p>" . $data['userdata']->realname . "，您好。這封信是由系統寄出，協助您重新設定使用者密碼的信件。</p>";
        $message .= "<p>電子郵件位址來自您在系統中留存的 e-mail 位址，如果您不是這個 e-mail 位址的擁有者，請忽略這封郵件。</p>";
        $message .= '<p>如果您的資料無誤，請點選這個連結<a href="{unwrap}' . config_item('base_url') ."/index.php/Reset/confirm/";
        $message .= $sessionkey . "/" . $pass_code . '{/unwrap}">';
        $message .= config_item('base_url') ."/index.php/Reset/confirm/" . $sessionkey . "/" . $pass_code;
        $message .= "</a>以瀏覽器完成密碼重設動作。</p>";
        $message .= "若您的電子郵件程式不支援超連結，也可以複製上列連結網址貼到瀏覽器使用。";

        // 準備寄信
        $this->email->from( $this->mail, $this->admin);
        $this->email->to($data['userdata']->email);

        $this->email->subject('密碼重設 - ' . $this->title);
        $this->email->message($message);

        if ($this->email->send()) {
        //if (TRUE) {
        // 寫入資料庫
        $this->sessions_model->add($reset_session);
        redirect('/Admin');
        } else
        {
            echo "Faile send email";
        }
    }

    // 檢查通關密碼並重設使用者密碼
    public function confirm($id,$pass)
    {
        
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "使用者重設密碼";
        $data['site'] = $this->title;
        
        // 刪除過期的 session
        $this->sessions_model->queryExpire();
        // 查詢 session
        $pass_session = $this->sessions_model->retriveSession($id);
        $pass_session['session_value'] = unserialize($pass_session['session_value']);
        $confirm_session = $pass_session['session_value'];
        if ($pass != $confirm_session['pass_code'])
        {
            $message = "認證失敗，使用的通關密碼有誤或重設密碼已逾期，請檢查您的連結或重新申請。";
            $this->session->set_flashdata('message', $message);
            redirect('/Reset/confirmDone');
        } else
        {
            $this->session->set_userdata('passid', $confirm_session['userid']);
            $uid = $this->session->userdata('passid');
            $data['userdata'] = $this->usertb_model->query($uid);
            $data['key'] = $pass_session['session_key'];
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('userpass', '密碼', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm');
			$this->load->view('footer');
            
        } else
        {
            
            // 接收表單
            $formdata['userpass'] = $this->input->post('userpass');
            $formdata['userid'] = $this->input->post('userid');
            $formdata['session_key'] = $this->input->post('session_key');
            $uid = $formdata['userid'];
                        // 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryValue('pwdsalt');
            $ismd5 = $md5key;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }
            
            $userpass = array(
                'userpass'  =>  $formdata['userpass']
            );
            // 修改資料庫
                $this->usertb_model->modify($uid, $userpass);
                $key = $this->session->userdata('sessionkey');
                // 刪除修改密碼要求
                $this->sessions_model->delete($formdata['session_key']);
            $message = "密碼重設已完成，您可以回到公告系統測試看看。";
            $this->session->set_flashdata('message', $message);
            redirect('/Reset/confirmDone');
        }
        }
    }
    // 使用者要求重設密碼
    public function userRequestPassword()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "使用者要求重設密碼";
        $data['site'] = $this->title;
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('username', '帳號', 'trim|required');
        $this->form_validation->set_rules('email', '電子信箱', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_request');
			$this->load->view('footer');
            
        } else
        {
            
            // 接收表單
            $formdata['username'] = $this->input->post('username');
            $formdata['email'] = $this->input->post('email');
            $result = $this->usertb_model->queryValue('username', $formdata['username']);
            if(empty($result) || ($result->email != $formdata['email']))
            {
            $message = "帳號或電子郵件不符，請再試一次。";
            $this->session->set_flashdata('message', $message);
            redirect('/Reset/confirmDone');
            } else {
                $this->session->set_userdata('updatemember', $result->userid);
                $message = "密碼重設已受理，請到電子郵件信箱收信。";
            $this->session->set_flashdata('message', $message);
            $this->requestPassword($result->userid);
            redirect('/Reset/confirmDone');
            }
        }
    }
    // 使用者重設密碼訊息
    public function confirmDone()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "使用者重設密碼訊息";
        $data['site'] = $this->title;
        $data['message'] = $this->session->flashdata('message');
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
    }
}
