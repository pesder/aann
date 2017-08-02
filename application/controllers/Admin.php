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
            // 設定目前網址，供認證後跳回
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            //進行認證
            $this->auth();
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
        $data['function_name'] = "管理功能";
        $data['site'] = $this->title->configvalue;
        $data['h1'] = "使用者功能";
        $data['h1group'] = array (
                '/Admin/createPart' =>  "新增一個處室",
                '/Admin/updatePart1' =>  "修改處室資料",
                '/Admin/addMember'  =>  "新增一位組員",
                '/Admin/updateMember1'  =>  "修改組員資料"
        );
        $data['h2'] = "網站功能";
        $data['h2group'] = array (
                '/Admin/changeSiteName' =>  "網站名稱"
        );
        // 載入 view
        $this->load->view('header',$data);
        $this->load->view('admin_index');
        $this->load->view('footer');
    }
    // 建立處室
    public function createPart()
    {
        $data['function_name'] = "建立處室";
        $data['site'] = $this->title->configvalue;
        //$nowurl = $this->session->userdata('nowurl');
        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partname', '中文名稱', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_createpart');
			$this->load->view('footer');
		}
		else
		{
            // 接收表單
			$formdata['pid'] = $this->input->post('pid');
            $formdata['partname'] = $this->input->post('partname');
            $formdata['partident'] = $this->input->post('partident');
            if (!empty($formdata['pid']))
            {
                $parttb = array (
                    'pid'   =>  $formdata['pid'],
                    'partname'  =>  $formdata['partname'],
                    'partident' =>  $formdata['partident']
                );
                $this->parttb_model->add($parttb);
            } elseif (empty($formdata['pid']))
            {
                $parttb = array (
                    'partname'  =>  $formdata['partname'],
                    'partident' =>  $formdata['partident']
                );
                $partid = $this->parttb_model->add_id($parttb);
                $newpid = "c" . $partid;
                $modify = array (
                    'pid'   =>  $newpid
                );
                $this->parttb_model->modify($partid, $modify);
            }
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
    // 修改處室
    public function updatePart1()
    {
            $data['function_name'] = "選擇要修改處室";
            $data['site'] = $this->title->configvalue;
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $data['options'] = $this->parttb_model->queryList();
            // 表單驗證
		$this->form_validation->set_message('required','{field}未選');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partid', '處室', 'trim|required');
            // 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_updatepart1');
			$this->load->view('footer');
		}
		else
		{
            // 接收表單
			$formdata['partid'] = $this->input->post('partid');
            $this->session->set_userdata("modifypartid", $formdata['partid']);
            // 跳到下一頁
            redirect('/Admin/updatePart2');
        }
    }
        public function updatePart2($partid = 0)
    {
            $data['function_name'] = "修改處室";
            $data['site'] = $this->title->configvalue;
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $sessionpartid = $this->session->userdata('modifypartid');
            if (empty($sessionpartid))
            {
                redirect('/Admin');
            } else
            {
                $data['parttb'] = $this->parttb_model->query($sessionpartid);
            }

        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('pid', '處室簡碼', 'trim|required');
        $this->form_validation->set_rules('partname', '中文名稱', 'trim|required');
        
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_updatepart2');
			$this->load->view('footer');
		}
		else
		{
            // 接收表單
			$formdata['pid'] = $this->input->post('pid');
            $formdata['partname'] = $this->input->post('partname');
            $formdata['partident'] = $this->input->post('partident');
            $parttb = array(
                    'pid'   =>  $formdata['pid'],
                    'partname'  =>  $formdata['partname'],
                    'partident' =>  $formdata['partident']
            );
            print_r($parttb);
            $this->parttb_model->modify($sessionpartid, $parttb);
            redirect('/Admin');
            $this->session->set_userdata("modifypartid", "");
        }
    }
    // 新增一位組員
    public function addMember()
    {
        $data['function_name'] = "新增組員";
        $data['site'] = $this->title->configvalue;
        //$nowurl = $this->session->userdata('nowurl');
        $data['options'] = $this->parttb_model->queryList();        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('username', '帳號', 'trim|required');
        $this->form_validation->set_rules('realname', '真實姓名', 'trim|required');
        $this->form_validation->set_rules('userpass', '密碼', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_addmember');
			$this->load->view('footer');
		}
		else
		{
            // 接收表單
            $formdata['partid'] = $this->input->post('partid');
            $formdata['username'] = $this->input->post('username');
            $formdata['realname'] = $this->input->post('realname');
            $formdata['userpass'] = $this->input->post('userpass');
            $formdata['email'] = $this->input->post('email');
            $formdata['userident'] = $this->input->post('userident');
            $formdata['rootuid'] = $this->input->post('rootuid');
            // 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryBy('configkey','pwdsalt');
            $ismd5 = $md5key->configvalue;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }
            //準備寫入 usertb
            $usertb = array (
                'partid'    =>  $formdata['partid'],
                'username'    =>  $formdata['username'],
                'realname'    =>  $formdata['realname'],
                'userpass'    =>  $formdata['userpass'],
                'email'    =>  $formdata['email'],
                'userident'    =>  $formdata['userident']
            );
            $newid = $this->usertb_model->add($usertb);
            // 設定處室管理者
            if($formdata['rootuid'] == 1)
            {
                $root = array(
                    'rootuid'   =>  $newid
                );
                $this->parttb_model->modify($formdata['partid'], $root);
            }
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
        // 修改組員資料
    public function updateMember1()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "選擇組員處室";
        $data['site'] = $this->title->configvalue;
        //$nowurl = $this->session->userdata('nowurl');
        $data['options'] = $this->parttb_model->queryList();        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partid', '處室', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			$part = $this->session->userdata('partid');
            if (empty($part)) {
                $data['message'] = "尚未選擇處室";
            } else {
                $data['userlist'] = $this->usertb_model->queryMember($part);
                $this->session->set_userdata('partid', "");
                if (empty($data['userlist'])) {
                    $data['message'] = "找不到成員";
                } else {
                    $data['message'] = "請點選成員進入修改";
                }
            }
            if (!empty($part))
            {
                $data['userlist'] = $this->usertb_model->queryMember($part);
            }
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_updatemember1');
            $this->load->view('admin_updatemember1_list');
			$this->load->view('footer');
		}
		else
		{
            $part = $this->session->userdata('partid');
            if(empty($part)) 
            {
                $inputpart = $this->input->post('partid');
                $this->session->set_userdata('partid', $inputpart);
            }
            redirect($urlpath);
        }
    }
            // 修改組員資料
    public function updateMember2($id = 0)
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "修改組員資料";
        $data['site'] = $this->title->configvalue;
        //$nowurl = $this->session->userdata('nowurl');
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
        $data['partdata'] = $this->parttb_model->query($data['userdata']->partid)->rootuid;
        $data['options'] = $this->parttb_model->queryList();        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partid', '處室', 'trim|required');
        $this->form_validation->set_rules('username', '帳號', 'trim|required');
        $this->form_validation->set_rules('realname', '真實姓名', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            // 載入 view
			$this->load->view('header',$data);
			$this->load->view('admin_updatemember2');
			$this->load->view('footer');
            
        } else
        {
            $uid = $this->session->userdata('updatemember');
            // 接收表單
            $formdata['partid'] = $this->input->post('partid');
            $formdata['username'] = $this->input->post('username');
            $formdata['realname'] = $this->input->post('realname');
            $formdata['userpass'] = $this->input->post('userpass');
            $formdata['email'] = $this->input->post('email');
            $formdata['userident'] = $this->input->post('userident');
            $formdata['rootuid'] = $this->input->post('rootuid');

            //準備寫入 usertb
            $usertb = array (
                'partid'    =>  $formdata['partid'],
                'username'    =>  $formdata['username'],
                'realname'    =>  $formdata['realname'],
                'email'    =>  $formdata['email'],
                'userident'    =>  $formdata['userident']
            );
            $this->usertb_model->modify($uid, $usertb);
            $newid = $uid;
            // 設定處室管理者
            if($formdata['rootuid'] == 1)
            {
                $root = array(
                    'rootuid'   =>  $newid
                );
                $this->parttb_model->modify($formdata['partid'], $root);
            }
            // 判斷，若密碼欄位有填寫，則進行密碼變更
            if (!empty($formdata['userpass']))
            {
            // 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryBy('configkey','pwdsalt');
            $ismd5 = $md5key->configvalue;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }
            $userpass = array(
                'userpass'  =>  $formdata['userpass']
            );
                $this->usertb_model->modify($uid, $userpass);
            }
            print_r($usertb);
            //清除 updatemember
            $this->session->set_userdata('updatemember', "");
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
}
?>