<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Part_admin extends CI_Controller
{

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
            $this->load->model('openidbind_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
            // 設定目前網址，供認證後跳回
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $this->classname = "Part_admin";
            //進行認證
            $this->auth();
    }

    public function auth()
    {
        $login = $this->session->userdata('userlogin');
        $part = $this->parttb_model->query($login['partid']);
        $this->session->set_userdata('part', $part);
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if (empty($login)) {
            redirect('/Auth/chooseAuth');
        } elseif ($login['authpass'] == 0) {
            redirect('/Auth/chooseAuth');
        } elseif ($part->rootuid != $login['userid']) {
            redirect('/Auth/chooseAuth');
        }
    }
    public function index()
    {
        $data['function_name'] = "管理功能 - 處室管理員";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $data['newuser'] = $this->openidbind_model->queryBy('new', '1');
        $data['message'] = $this->session->flashdata('message');
        $data['h1'] = "使用者功能";
        $data['h1group'] = array (
                '/Part_admin/addMember'  =>  "新增一位組員",
                '/Part_admin/updateMember1'  =>  "修改組員資料",
        );
        $data['h2'] = "";
        $data['h2group'] = array (
                '' =>  ""
        );
        $data['h3'] = "單一登入功能";
        $data['h3group'] = array (
                '/Part_admin/showOiduser' =>  "顯示單一登入使用者",
                '/Part_admin/showBinduser' =>  "顯示單一登入使用者(已綁定)",
                '/Part_admin/showBanneduser' =>  "顯示被封鎖使用者",
        );
        // 載入 view
        $this->load->view('header', $data);
        $this->load->view('admin_index');
        $this->load->view('footer');
    }

    // 新增一位組員
    public function addMember()
    {
        $data['function_name'] = "新增組員";
        $data['function_key'] = $this->classname . "/addMember";
        $data['site'] = $this->title;
        $selectPart = $this->session->userdata('part');
        //$nowurl = $this->session->userdata('nowurl');
        $options = array(
            $selectPart->partid =>  $selectPart->partname
        );
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options
        );
        $data['username_data'] = array (
        'name'  =>  'username',
        'class'     =>  'form-control');
        $data['realname_data'] = array (
        'name'  =>  'realname',
        'class'     =>  'form-control');
        $data['userpass_data'] = array (
        'name'  =>  'userpass',
        'class'     =>  'form-control');
        $data['email_data'] = array (
        'name'  =>  'email',
        'class'     =>  'form-control');
        $data['userident_data'] = array (
        'name'  =>  'userident',
        'class'     =>  'form-control');
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '新增',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('username', '帳號', 'trim|required|alpha_dash|is_unique[usertb.username]');
        $this->form_validation->set_rules('realname', '真實姓名', 'trim|required');
        $this->form_validation->set_rules('userpass', '密碼', 'trim|required');
        $this->form_validation->set_rules('email', '電子郵件', 'trim|required|valid_email');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_addmember');
            $this->load->view('admin_addmember_end');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['partid'] = $this->input->post('partid');
            $formdata['username'] = $this->input->post('username', TRUE);
            $formdata['realname'] = $this->input->post('realname', TRUE);
            $formdata['userpass'] = $this->input->post('userpass', TRUE);
            $formdata['email'] = $this->input->post('email', TRUE);
            $formdata['userident'] = $this->input->post('userident', TRUE);
            $formdata['rootuid'] = $this->input->post('rootuid');
            /* 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryValue('pwdsalt');
            $ismd5 = $md5key;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }*/
            // 新設定之密碼改用 php 加密方式
            $formdata['userpass'] = password_hash($formdata['userpass'], PASSWORD_DEFAULT);
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
            if ($formdata['rootuid'] == 1) {
                $root = array(
                    'rootuid'   =>  $newid
                );
                $this->parttb_model->modify($formdata['partid'], $root);
            }
            // 動作結束，回選單
            redirect('/Part_admin');
        }
    }
        // 修改組員資料
    public function updateMember1()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "選擇組員處室";
        $data['function_key'] = $this->classname . "/updateMember1";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        //$nowurl = $this->session->userdata('nowurl');
        $selectPart = $this->session->userdata('part');
        $options = array(
            $selectPart->partid =>  $selectPart->partname
        );
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '選擇',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('partid', '處室', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
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
            if (!empty($part)) {
                $data['userlist'] = $this->usertb_model->queryMember($part);
            }
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_updatemember1');
            $this->load->view('admin_updatemember1_list');
            $this->load->view('footer');
        } else {
            $part = $this->session->userdata('partid');
            if (empty($part)) {
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
        $data['function_key'] = $this->classname . "/updateMember2/" . $id;
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        //$nowurl = $this->session->userdata('nowurl');
        if ($id != 0) {
            $this->session->set_userdata('updatemember', $id);
        }
        $uid = $this->session->userdata('updatemember');
        // 若沒有 userid 值，則跳回管理頁面
        if (empty($uid)) {
            redirect('/Part_admin');
        }
        $data['userdata'] = $this->usertb_model->query($uid);
        $data['partdata'] = $this->parttb_model->query($data['userdata']->partid)->rootuid;
        $selectPart = $this->session->userdata('part');
        $options = array(
            $selectPart->partid =>  $selectPart->partname
        );
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options,
        'selected'  =>  $data['userdata']->partid);
        $data['username_data'] = array (
        'name'  =>  'username',
        'class'     =>  'form-control',
        'value' =>  $data['userdata']->username);
        $data['realname_data'] = array (
        'name'  =>  'realname',
        'class'     =>  'form-control',
        'value' =>  $data['userdata']->realname);
        $data['userpass_data'] = array (
        'name'  =>  'userpass',
        'class'     =>  'form-control');
        $data['email_data'] = array (
        'name'  =>  'email',
        'class'     =>  'form-control',
        'value' =>  $data['userdata']->email);
        $data['userident_data'] = array (
        'name'  =>  'userident',
        'class'     =>  'form-control',
        'value' =>  $data['userdata']->userident);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '新增',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin/updateMember1" class="btn btn-primary" accesskey="h">回處室選單</a>';
        $data['button'] .= '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('username', '帳號', 'trim|required|alpha_dash|is_unique[usertb.username]');
        $this->form_validation->set_rules('realname', '真實姓名', 'trim|required');
        $this->form_validation->set_rules('userpass', '密碼', 'trim');
        $this->form_validation->set_rules('email', '電子郵件', 'trim|required|valid_email');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_addmember');
            $this->load->view('admin_updatemember2');
            $this->load->view('footer');
        } else {
            $uid = $this->session->userdata('updatemember');
            // 接收表單
            $formdata['partid'] = $this->input->post('partid');
            $formdata['username'] = $this->input->post('username', TRUE);
            $formdata['realname'] = $this->input->post('realname', TRUE);
            $formdata['userpass'] = $this->input->post('userpass', TRUE);
            $formdata['email'] = $this->input->post('email', TRUE);
            $formdata['userident'] = $this->input->post('userident', TRUE);
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
            if ($formdata['rootuid'] == 1) {
                $root = array(
                    'rootuid'   =>  $newid
                );
                $this->parttb_model->modify($formdata['partid'], $root);
            }
            // 判斷，若密碼欄位有填寫，則進行密碼變更
            if (!empty($formdata['userpass'])) {
            /* 判斷若有設定 sha1 加密字串，則密碼比對使用 sha1
            $md5key = $this->config_model->queryValue('pwdsalt');
            $ismd5 = $md5key;
            if (!empty($ismd5)) {
                $formdata['userpass'] = sha1($ismd5 . '$|@' . $formdata['userpass']);
            }*/
            // 新設定之密碼改用 php 加密方式
                $formdata['userpass'] = password_hash($formdata['userpass'], PASSWORD_DEFAULT);
                $userpass = array(
                'userpass'  =>  $formdata['userpass']
                );
                $this->usertb_model->modify($uid, $userpass);
            }
            //清除 updatemember
            $this->session->set_userdata('updatemember', "");
            // 動作結束，回選單
            redirect('/Part_admin');
        }
    }
    // 刪除組員資料
    public function deleteMember()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "刪除組員資料";
        $data['site'] = $this->title;
        // 從 session 取回要刪除的 userid
        $uid = $this->session->userdata('updatemember');
        // 若檢查不到 userid 則跳回處室選擇畫面
        if (empty($uid)) {
            redirect('/Part_admin/updateMember1');
        }
        $this->usertb_model->delete($uid);
        redirect('Admin');
    }
        // 停用組員資料
    public function disableMember()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "停用組員";
        $data['site'] = $this->title;
        // 從 session 取回要刪除的 userid
        $uid = $this->session->userdata('updatemember');
        // 若檢查不到 userid 則跳回處室選擇畫面
        if (!empty($uid)) {
            $this->load->helper('security');
            $disable = array (
            'partid'    =>  '0'
            );
            $rand = do_hash(rand(1000, 9999), 'md5');
        // 利用 password_hash 將產生的亂碼加密，成為無法登入的密碼
            $rand = password_hash($rand, PASSWORD_DEFAULT);
       
            $userpass = array(
                'userpass'  =>  $rand
            );
        //將使用者處室設定為 0，這個代號正常情形下不會使用，因而不會出現在任何處室
            $this->usertb_model->modify($uid, $disable);
        //設定一個亂數做為使用者密碼，使其無法登入
            $this->usertb_model->modify($uid, $userpass);
            redirect('Admin');
        } else {
            redirect('/Part_admin/updateMember1');
        }
    }
        // 啟用一位組員
    public function enableMember()
    {
        $data['function_name'] = "啟用一位組員";
        $data['function_key'] = $this->classname . "/enableMember";
        $data['site'] = $this->title;
        //$nowurl = $this->session->userdata('nowurl');
        //查詢被設定為停用的使用者
        $data['userdata'] = $this->usertb_model->queryMember('0');
        $selectPart = $this->session->userdata('part');
        $options = array(
            $selectPart->partid =>  $selectPart->partname
        );
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '啟用選擇的組員',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('userid', '組員', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_enablemember');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['partid'] = $this->input->post('partid');
            $formdata['userid'] = $this->input->post('userid');
            //$formdata['partident'] = $this->input->post('partident');
            if (!empty($formdata['userid'])) {
                $uid = $formdata['userid'];
                $usertb = array (
                    'partid'   =>  $formdata['partid']
                );
                $this->usertb_model->modify($uid, $usertb);
            }
            // 動作結束，回選單
            redirect('/Part_admin');
        }
    }
    
    // 確認單一登入使用者
    public function confirmNewuser($oid = 0)
    {
        $data['function_name'] = "確認單一登入使用者";
        $data['function_key'] = $this->classname . "/confirmNewuser";
            $data['site'] = $this->title;
            $data['classname'] = $this->classname;
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $options = $this->usertb_model->queryUser();
            $data['userid_data'] = array (
            'name'  =>  'userid',
            'class'     =>  'form-control',
            'options'   =>  $options
            );
            $selectPart = $this->session->userdata('part');
            $parts = array(
            $selectPart->partid =>  $selectPart->partname
            );
            $data['partid_data'] = array (
                'name'  =>  'partid',
                'class'     =>  'form-control',
                'options'   =>  $parts
            );
            $data['acc_data'] = array(
            'name' => 'use_same_account',
            'id'    =>  'use_same_account',
            'value'     =>  '1',
            'checked'   =>  false,
            'class'     =>  'form-control' );
            $data['but1'] = array (
            'name'  =>  'sent',
            'type'  =>  'submit',
            'content' =>  '修改',
            'class' =>  'btn btn-primary',
            'accesskey'   =>  's');
            $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        if ($oid > 0) {
            $data['newuser'] = $this->openidbind_model->query($oid);
            $this->session->set_userdata('oiduser', $data['newuser']);
        }
            // 表單驗證
        $this->form_validation->set_message('required', '{field}未選');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('new', '新建帳號', 'trim|required');
        $this->form_validation->set_rules('banned', '阻擋帳號', 'trim|required');
            // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_oid_confirmuser');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['new'] = $this->input->post('new');
            $formdata['userid'] = $this->input->post('userid');
            $formdata['use_same_account'] = $this->input->post('use_same_account');
            $formdata['partid'] = $this->input->post('partid');
            $formdata['banned'] = $this->input->post('banned');
            $oiduser = $this->session->userdata('oiduser');
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
                'userpass'    =>  password_hash(md5(rand(1000, 9999)), PASSWORD_DEFAULT),
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
                redirect('/Part_admin');
            } // 若非直接建立帳號，則採綁定帳號處理
            elseif ($formdata['userid'] != '') {
            // 處理本筆 oid 帳號
                $openidbind_data = array(
                'bind_userid'   =>  $formdata['userid'],
                'new'   =>  '0'
                );
                $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
                redirect('/Part_admin');
            } else {
            // 若非前兩種動作，則只處理確認或阻擋
            // 處理本筆 oid 帳號
                $openidbind_data = array(
                'new'   =>  $formdata['new'],
                'banned'   =>   $formdata['banned']
                );
                $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
                redirect('/Part_admin');
            }
        }
    }
    // 刪除 openid 使用者
    public function deleteOidUser($oid = 0)
    {
        if ($oid> 0) {
            $this->session->set_userdata('deleteuser', $oid);
        }
        $deleteid = $this->session->userdata('deleteuser');
        $user = $this->openidbind_model->querySingleName($deleteid);
        $this->openidbind_model->delete($deleteid);
        $message = "已刪除" . $user->fullname . "以單一登入申請的帳號";
        $this->session->set_flashdata('message', $message);
        redirect('/Part_admin');
    }
    // 顯示單一登入使用者
    public function showOiduser()
    {
        $data['function_name'] = "顯示單一登入使用者";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        $data['list'] = $this->openidbind_model->showUser();
        // 載入 View
        $this->load->view('header', $data);
        $this->load->view('admin_oid_showuser');
        $this->load->view('footer');
    }
    // 顯示單一登入使用者(已綁定)
    public function showBinduser()
    {
        $data['function_name'] = "顯示單一登入使用者(已綁定)";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        $data['list'] = $this->openidbind_model->showBindUser();
        // 載入 View
        $this->load->view('header', $data);
        $this->load->view('admin_oid_showuser');
        $this->load->view('footer');
    }
    // 顯示被封鎖使用者
    public function showBanneduser()
    {
        $data['function_name'] = "顯示被封鎖使用者";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Part_admin" class="btn btn-primary" accesskey="h">回管理選單</a>';
        $data['list'] = $this->openidbind_model->queryBy('banned', '1');
        // 載入 View
        $this->load->view('header', $data);
        $this->load->view('admin_oid_showuser');
        $this->load->view('footer');
    }
    // 登出
    public function logout($oid = 0)
    {
        $data['function_name'] = "登出";
        $data['function_key'] = $this->classname . "/logout";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        redirect('/Main');
    }
}
