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
        }

    public function index()
    {
        $data['function_name'] = "管理功能";
        $data['site'] = $this->title->configvalue;
        $urlpath = '/Admin';
        $this->session->set_userdata('nowurl', $urlpath);
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if(empty($login))
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 0)
        {
            $data['message'] = $login['denyreason'];
			// 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('admin_deny');
			$this->load->view('footer');
            
        } elseif ($login['adminauthpass'] == 1)
        {
            echo "登入完成";
            $data['h1'] = "使用者功能";
            $data['h1group'] = array (
                '/Admin/createPart' =>  "建立處室",
                '/Admin/updatePart1' =>  "修改處室"
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
    }
    // 建立處室
    public function createPart()
    {
        $urlpath = '/Admin';
        $this->session->set_userdata('nowurl', $urlpath);
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if($login['adminauthpass'] != 1)
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 1)
        {
        $data['function_name'] = "建立處室";
        $data['site'] = $this->title->configvalue;
        $nowurl = $this->session->userdata('nowurl');
        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partname', '中文名稱', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header-jquery',$data);
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
    }
    // 修改處室
    public function updatePart1()
    {

        $urlpath = '/Admin';
        $this->session->set_userdata('nowurl', $urlpath);
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if($login['adminauthpass'] != 1)
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 1)
        {
            $data['function_name'] = "選擇要修改處室";
            $data['site'] = $this->title->configvalue;
            $data['options'] = $this->parttb_model->queryList();
            // 表單驗證
		$this->form_validation->set_message('required','{field}未選');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partid', '處室', 'trim|required');
            // 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
			// 載入 view
			$this->load->view('header-jquery',$data);
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
    }
        public function updatePart2($partid = 0)
    {

        $urlpath = '/Admin';
        $this->session->set_userdata('nowurl', $urlpath);
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if($login['adminauthpass'] != 1)
        {
            redirect('/Auth/adminAuth');
        }
        elseif ($login['adminauthpass'] == 1)
        {
            $data['function_name'] = "修改處室";
            $data['site'] = $this->title->configvalue;
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
			$this->load->view('header-jquery',$data);
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
    }
}
?>