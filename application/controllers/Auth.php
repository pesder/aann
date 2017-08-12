<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->library('form_validation');
            $this->load->helper('form');
            $this->load->helper('url');
            
            // 載入列表 model
            $this->load->model('usertb_model');
            $this->load->model('parttb_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
        }

    public function postAnnAuth()
    {
        $data['function_name'] = "發布公告檢驗帳號";
        $data['site'] = $this->title;
        //取得單位資料
        $data['part'] = $this->parttb_model->query();
        $nowurl = $this->session->userdata('nowurl');
        
        // 宣告陣列，利用 foreach 將查詢結果轉為陣列用於下接選單
        $data['partlist'] = $this->parttb_model->queryList();
        $result = $this->session->userdata('userlogin');
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('username', '使用者帳號', 'trim|required');
		$this->form_validation->set_rules('userpass', '密碼', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			$data['message'] = $result['denyreason'];
            // 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('auth_postannauth');
            if ($result['authpass'] == 0 )
            {
                $this->load->view('admin_deny');
            }
			$this->load->view('footer');
		}
		else
		{
			// 接收表單
            $formdata['partid'] = $this->input->post('partid');
			$formdata['username'] = $this->input->post('username');
			$formdata['userpass'] = $this->input->post('userpass');
            // 使用 php 加密者則留存一份原使輸入資料
            $php_crypt_password = $formdata['userpass'];
            $denyreason = "";
			// 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryValue('pwdsalt');
            $ismd5 = $md5key;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }
            //取得使用者資料
            $data['user'] = $this->usertb_model->matchPassword('username', $formdata['username']);
            if ($formdata['partid'] != $data['user']['partid']) {
                $denyreason = "您無法發布這個單位的公告。";
            }
			// 比對密碼
            if ((password_verify($php_crypt_password, $data['user']['userpass']) || ($formdata['userpass'] == $data['user']['userpass'])) && $formdata['partid'] == $data['user']['partid'])
            {
                $result = array (
                    'authpass' => "1",
                    'denyreason' => $denyreason,
                    'partid' => $data['user']['partid'],
                    'username' => $data['user']['username'],
                    'realname' => $data['user']['realname'],
                    'userid'    =>  $data['user']['userid'],
                    'readlocal' =>  "1"
                );
                $this->session->set_userdata('userlogin', $result);
            } else 
            {
                $denyreason = $denyreason . "帳號或密碼有誤，請再試一次";
                $result = array (
                    'authpass' => "0",
                    'denyreason' => $denyreason,
                    'partid' => "",
                    'username' => "",
                    'realname' => "",
                    'userid'    =>  "",
                    'readlocal' =>  ""
                );
                $this->session->set_userdata('userlogin', $result);
            }

			// 回首頁
            if (empty($nowurl))
            {
                redirect('/PostAnn/postAnnForm');
            } else
            {
                redirect($nowurl);
            }
			
		}
    }
    public function adminAuth()
    {
        $data['function_name'] = "超級總管檢驗帳號";
        $data['site'] = $this->title;
        $nowurl = $this->session->userdata('nowurl');
        $result = $this->session->userdata('adminlogin');
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('username', '使用者帳號', 'trim|required');
		$this->form_validation->set_rules('userpass', '密碼', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			$data['message'] = $result['denyreason'];
            // 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('auth_adminauth');
            if ($result['adminauthpass'] == 0 )
            {
                $this->load->view('admin_deny');
            }
			$this->load->view('footer');
		}
		else
		{
			// 接收表單
			$formdata['username'] = $this->input->post('username');
			$formdata['userpass'] = $this->input->post('userpass');
            $denyreason = "";
            // 超級使用者帳號驗證時自動在前面加入 "="
            $adminuser = $this->config_model->queryValue('adminuser');
            $adminuser = "=" . $adminuser;
            $adminpass = $this->config_model->queryValue('adminpass');
			// 比對密碼
            if (password_verify($formdata['userpass'], $adminpass) && $formdata['username'] == $adminuser)
            {
                $result = array (
                    'adminauthpass' => "1",
                    'denyreason' => $denyreason
                );
                $this->session->set_userdata('adminlogin', $result);
            } else 
            {
                $denyreason = $denyreason . "帳號或密碼有誤，請再試一次";
                $result = array (
                    'adminauthpass' => "0",
                    'denyreason' => $denyreason
                );
                $this->session->set_userdata('adminlogin', $result);
            }

			// 回首頁
            if (empty($nowurl))
            {
                redirect('/PostAnn/adminAuth');
            } else
            {
                redirect($nowurl);
            }
			
		}
    }
    public function openidAuth()
    {
        $data['function_name'] = "單一登入檢驗帳號";
        $data['site'] = $this->title;
        $nowurl = $this->session->userdata('nowurl');
        $oid_login = $this->session->userdata('openid_user');
        //載入 openid 帳號管理資料庫
        $this->load->model('openidbind_model');
        $schoolnumber = $this->config_model->queryValue('schoolnumber');
        // 檢查是否為本系統使用者，若不是則回到首頁
        if (($oid_login['school'] != $schoolnumber) || ($oid_login['job'] == 'student'))
        {
            $message = "您透過單一認證所驗證的單位與本系統所屬不同，請確認您認證所屬單位為何，或洽單一認證管理單位。";
            $data['message'] = $message;
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
        } else {
        // 查詢資料庫是否有相關資料
        $olduser = $this->openidbind_model->checkUser('openid_id', $oid_login['openid_id']);
        // 檢查是否曾登入過本系統，若無則提供登錄功能
        if (empty($olduser)) {
            $message = "您是第一次以" . $oid_login->fullname . "的身分登入，目前尚無法提供完整功能。系統會先記錄此次登入要求，等系統管理者完成設定後就可以使用完整公告功能。";
            // 將此次認證寫入資料庫
            $newuser_data = array(
                'openid_id' => $oid_login['openid_id'],
                'fullname'  =>  $oid_login['fullname'],
                'email'     =>  $oid_login['email'],
                'school_number' =>  $oid_login['school'],
                'job'       =>  $oid_login['job'] );
            $this->openidbind_model->add($newuser_data);
            $data['message'] = $message;
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
        } elseif ($olduser->new == '1') {
            $message = "您的資料已建立，但使用者尚未設定相關資料，請稍候系統管理者確認。";
            $data['message'] = $message;
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
        } elseif ($olduser->banned == '1') {
            $message = "系統發生錯誤，暫時無法讓您以這個身分登入，抱歉。";
            $data['message'] = $message;
            // 清除認證資料
            $this->session->set_userdata('openid_user', '');
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
        }
        // 通過前列檢查後，如有綁定帳號，則將綁定帳號的身分寫入 session
        if (!empty($olduser->bind_userid)) {
            $annuser = $this->usertb_model->query($olduser->bind_userid);
            $result = array (
                    'authpass' => "1",
                    'denyreason' => '',
                    'partid' => $annuser->partid,
                    'username' => $annuser->username,
                    'realname' => $annuser->realname,
                    'userid'    =>  $annuser->userid
                );
        $this->session->set_userdata('userlogin', $result);
        // 登錄為通過單一登入身分
        $oid_login['oidpass'] = "1";
        $oid_login['readlocal'] = "1";
        }
        }
        
        // 回首頁
        if (empty($nowurl))
        {
            redirect('/Main');
        } else
        {
            redirect($nowurl);
        }
        
    }
}
?>