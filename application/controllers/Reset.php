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
            $this->title = $this->config_model->queryBy('configkey','myname');
            $this->admin = $this->config_model->queryBy('configkey','site_admin');
            $this->email = $this->config_model->queryBy('configkey','site_email');
            $this->smtphost = $this->config_model->queryBy('configkey','smtp_host');
            $this->smtpuser = $this->config_model->queryBy('configkey','smtp_user');
            $this->smtppass = $this->config_model->queryBy('configkey','smtp_pass');
            $this->smtpport = $this->config_model->queryBy('configkey','smtp_port');
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
        $data['site'] = $this->title->configvalue;
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
        $sessionkey = substr(md5("pass" . $uid),0,32);
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
        $config['smtp_host'] = $this->smtphost->configvalue;
        $config['smtp_user'] = $this->smtpuser->configvalue;
        $config['smtp_pass'] = $this->smtppass->configvalue;
        $config['smtp_port'] = $this->smtpport->configvalue;
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = FALSE;

        //$this->email->initialize($config);
        
        $this->load->library('email');
        // 準備郵件本文
        $message = "<h1>" . $this->title->configvalue . "密碼重設郵件</h1>";
        $message .= "<p>" . $data['userdata']->realname . "，您好。這封信是由系統寄出，協助您重新設定使用者密碼的信件。</p>";
        $message .= "<p>電子郵件位址來自您在系統中留存的 e-mail 位址，如果您不是這個 e-mail 位址的擁有者，請忽略這封郵件。</p>";
        $message .= '<p>如果您的資料無誤，請點選這個連結<a href="{unwrap}' . config_item('base_url') ."/index.php/Reset/confirm/";
        $message .= $sessionkey . "/" . $pass_code . '{/unwrap}">';
        $message .= config_item('base_url') ."/index.php/Reset/confirm/" . $sessionkey . "/" . $pass_code;
        $message .= "</a>以瀏覽器完成密碼重設動作。</p>";
        $message .= "若您的電子郵件程式不支援超連結，也可以複製上列連結網址貼到瀏覽器使用。";
        // 準備寄信
        $this->email->from($this->email->configvalue, $this->admin->configvalue);
        $this->email->to($data['userdata']->email);

        $this->email->subject('密碼重設 - ' . $this->title->configvalue);
        $this->email->message($message);

        if ($this->email->send()) {
        // 寫入資料庫
        $this->sessions_model->add($reset_session);
        } else
        {
            echo "Faile send email";
        }
    }

    // 產生通關密碼並寄出密碼信
    public function confirm($id,$pass)
    {
        
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "使用者重設密碼";
        $data['site'] = $this->title->configvalue;
        echo time();
    }
}
