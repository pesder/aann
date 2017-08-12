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
            $this->load->model('parttb_model');
            $this->load->model('openidbind_model');
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
        $data['newuser'] = $this->openidbind_model->queryBy('new', '1');
        print_r($data['newuser']);
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
    // 確認單一登入使用者
    public function confirmNewuser($oid = 0)
    {
        $data['function_name'] = "確認單一登入使用者";
            $data['site'] = $this->title;
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $data['options'] = $this->usertb_model->queryUser();
            $data['parts'] = $this->parttb_model->queryList();
        if ($oid > 0) {
            $data['newuser'] = $this->openidbind_model->query($oid);
            $this->session->set_userdata('oiduser', $data['newuser']);
        }
            // 表單驗證
		$this->form_validation->set_message('required','{field}未選');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('new', '新建帳號', 'trim|required');
        $this->form_validation->set_rules('banned', '阻擋帳號', 'trim|required');
            // 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('oidmanage_confirmuser');
			$this->load->view('footer');
        } else {
            // 接收表單
            $formdata['new'] = $this->input->post['new'];
            $formdata['userid'] = $this->input->post['userid'];
            $formdata['use_same_account'] = $this->input->post['use_same_account'];
            $formdata['partid'] = $this->input->post['partid'];
            $formdata['banned'] = $this->input->post['banned'];
            // 優先處理以此設定新建帳號動作
            if ($formdata['use_same_account'] == 1) {
                if ($formdata['partid'] == '') {
                    $formdata['partid'] = '0';
                }
                $newuser = $this->session->userdata('oiduser');
                //準備寫入 usertb
            $usertb = array (
                'partid'    =>  $formdata['partid'],
                'username'    =>  $oiduser->openid_id . "-oid",
                'realname'    =>  $oiduser->fullname,
                'userpass'    =>  password_hash(do_hash(rand(1000,9999), 'md5'), PASSWORD_DEFAULT),
                'email'    =>  $oiduser->email,
                'userident'    =>  'openid 匯入帳號-' . $oiduser->fullname . "(" . $oiduser->openid_id . ")"
            );
            $newid = $this->usertb_model->add($usertb);
            // 處理本筆 oid 帳號
            $openidbind_data = array(
                'bind_userid'   =>  $newid,
                'new'   =>  '0'
            );
            $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
            redirect('/OidManage');
            }
            // 若非直接建立帳號，則採綁定帳號處理
            if ($formdata['userid'] != '') {
            // 處理本筆 oid 帳號
            $openidbind_data = array(
                'bind_userid'   =>  $formdata['userid'],
                'new'   =>  '0'
            );
            $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
            redirect('/OidManage');
            }
            // 若非前兩種動作，則只處理確認或阻擋
            // 處理本筆 oid 帳號
            $openidbind_data = array(
                'new'   =>  $formdata['new'],
                'banned'   =>   $formdata['banned']
            );
            $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
            redirect('/OidManage');
        }
    }

	
}
