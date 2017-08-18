<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
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
            $this->classname = "Admin";
            //進行認證
            $this->auth();
    }

    public function auth()
    {
        $login = $this->session->userdata('adminlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if (empty($login)) {
            redirect('/Auth/adminAuth');
        } elseif ($login['adminauthpass'] == 0) {
            redirect('/Auth/adminAuth');
        }
    }
    public function index()
    {
        $data['function_name'] = "管理功能";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $data['newuser'] = $this->openidbind_model->queryBy('new', '1');
        $data['message'] = $this->session->flashdata('message');
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
        $data['h3'] = "單一登入功能";
        $data['h3group'] = array (
                '/Admin/showOiduser' =>  "顯示單一登入使用者",
                '/Admin/showBinduser' =>  "顯示單一登入使用者(已綁定)",
                '/Admin/showBanneduser' =>  "顯示被封鎖使用者",
        );
        // 載入 view
        $this->load->view('header', $data);
        $this->load->view('admin_index');
        $this->load->view('footer');
    }
    // 建立處室
    public function createPart()
    {
        $data['function_name'] = "建立處室";
        $data['function_key'] = $this->classname . "/createPart";
        $data['site'] = $this->title;
        //$nowurl = $this->session->userdata('nowurl');
        $data['pid_data'] = array (
        'name'  =>  'pid',
        'class'     =>  'form-control');
        $data['partname_data'] = array (
        'name'  =>  'partname',
        'class'     =>  'form-control');
        $data['partident_data'] = array (
        'name'  =>  'partident',
        'class'     =>  'form-control');
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-floppy-save"></span> 新增',
        'class' =>  'btn btn-primary',
        'accesskey'     =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('partname', '中文名稱', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_createpart');
            $this->load->view('admin_updatepart_end');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['pid'] = $this->input->post('pid');
            $formdata['partname'] = $this->input->post('partname', TRUE);
            $formdata['partident'] = $this->input->post('partident', TRUE);
            if (!empty($formdata['pid'])) {
                $parttb = array (
                    'pid'   =>  $formdata['pid'],
                    'partname'  =>  $formdata['partname'],
                    'partident' =>  $formdata['partident']
                );
                $partid = $this->parttb_model->add_id($parttb);
            } elseif (empty($formdata['pid'])) {
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
            $partpath = './files/' . $partid;
            $oldmask = umask(0);
            mkdir($partpath, 0777);
            umask($oldmask);
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
    // 修改處室
    public function updatePart1()
    {
            $data['function_name'] = "選擇要修改處室";
            $data['function_key'] = $this->classname . "/updatePart1";
            $data['site'] = $this->title;
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            $options = $this->parttb_model->queryList();
                
        $data['partid_data'] = array (
            'name'  =>  'partid',
            'class'     =>  'form-control',
            'options'   =>  $options);
            $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-ok"></span> 選擇',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
            // 表單驗證
        $this->form_validation->set_message('required', '{field}未選');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('partid', '處室', 'trim|required');
            // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_updatepart1');
            
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['partid'] = $this->input->post('partid', TRUE);
            $this->session->set_userdata("modifypartid", $formdata['partid']);
            // 跳到下一頁
            redirect('/Admin/updatePart2');
        }
    }
    public function updatePart2($partid = 0)
    {
        $data['function_name'] = "修改處室";
        $data['function_key'] = $this->classname . "/updatePart2/" . $partid;
        $data['site'] = $this->title;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $sessionpartid = $this->session->userdata('modifypartid');
            
        if (empty($sessionpartid)) {
            redirect('/Admin');
        } else {
            $data['parttb'] = $this->parttb_model->query($sessionpartid);
        }
        $data['pid_data'] = array (
        'name'  =>  'pid',
        'class'     =>  'form-control',
        'value' =>  $data['parttb']->pid);
        $data['partname_data'] = array (
        'name'  =>  'partname',
        'class'     =>  'form-control',
        'value' =>  $data['parttb']->partname);
        $data['partident_data'] = array (
        'name'  =>  'partident',
        'class'     =>  'form-control',
        'value' =>  $data['parttb']->partident);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-floppy-save"></span> 儲存',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('pid', '處室簡碼', 'trim|required');
        $this->form_validation->set_rules('partname', '中文名稱', 'trim|required');
        
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_createpart');
            $this->load->view('admin_updatepart2');
            $this->load->view('admin_updatepart_end');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['pid'] = $this->input->post('pid');
            $formdata['partname'] = $this->input->post('partname', TRUE);
            $formdata['partident'] = $this->input->post('partident', TRUE);
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
    // 刪除處室
    public function deletePart()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['function_name'] = "刪除處室";
        $data['site'] = $this->title;
        // 從 session 取回要刪除的 partid
        $sessionpartid = $this->session->userdata('modifypartid');
        // 若檢查不到 partid 則跳回處室選擇畫面
        if (empty($sessionpartid)) {
            redirect('/Admin/updatePart1');
        }
        $this->load->model('titletb_model');
        $this->load->model('anntb_model');
        $this->load->model('filetb_model');
        // 刪除處室
        $this->parttb_model->delete($sessionpartid);
        // 刪除處室所有附件
        $this->load->helper('file');
        $partpath = './files/' . $sessionpartid . "/";
        delete_files($partpath, TRUE);
        $this->filetb_model->delete($sessionpartid);
        // 刪除使用者貼文本體
        $userlist = $this->usertb_model->queryMember($sessionpartid);
        foreach ($userlist as $key => $name) {
            $this->anntb_model->destory($key);
        }
        // 刪除處室所有使用者
        $this->usertb_model->destory($sessionpartid);
        // 刪除處室所有貼文標題
        $this->titletb_model->destory($sessionpartid);
        redirect('Admin');
    }

    // 新增一位組員
    public function addMember()
    {
        $data['function_name'] = "新增組員";
        $data['function_key'] = $this->classname . "/addMember";
        $data['site'] = $this->title;
        //$nowurl = $this->session->userdata('nowurl');
        $options = $this->parttb_model->queryList();
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
        'content' =>  '<span class="glyphicon glyphicon-floppy-save"></span> 儲存',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
            redirect('/Admin');
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
        $options = $this->parttb_model->queryList();
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-ok"></span> 選擇',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
            redirect('/Admin');
        }
        $data['userdata'] = $this->usertb_model->query($uid);
        $data['partdata'] = $this->parttb_model->query($data['userdata']->partid)->rootuid;
        $options = $this->parttb_model->queryList();
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
        'content' =>  '<span class="glyphicon glyphicon-pencil"></span>  修改',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin/updateMember1" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-wrench"></span> 回處室選單</a> ';
        $data['button'] .= '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        // 表單驗證
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
            redirect('/Admin');
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
            redirect('/Admin/updateMember1');
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
            redirect('/Admin/updateMember1');
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
        $options = $this->parttb_model->queryList();
        $data['partid_data'] = array (
        'name'  =>  'partid',
        'class'     =>  'form-control',
        'options'   =>  $options);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-ok-circle"></span> 啟用選擇的組員',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
            redirect('/Admin');
        }
    }
        // 變更網站設定
    public function updateSite()
    {
        $data['function_name'] = "變更網站設定";
        $data['site'] = $this->title;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-save-file"></span> 更新',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        //查詢用到的設定值
        $data['settings'] = $this->config_model->queryBy('cat', 2);
        $data['settings2'] = $this->config_model->queryBy('cat', 1);
        $data['settings3'] = $this->config_model->queryBy('cat', -1);
        
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('myname', '網站名稱', 'trim|required');
        $this->form_validation->set_rules('myhost', '網址', 'trim|required');
        $this->form_validation->set_rules('site_admin', '管理者', 'trim|required');
        $this->form_validation->set_rules('site_mail', '電子郵件', 'trim|required');
        $this->form_validation->set_rules('adminuser', '超級使用者', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_updatesite');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['myname'] = $this->input->post('myname', TRUE);
            $formdata['myhost'] = $this->input->post('myhost', TRUE);
            $formdata['site_admin'] = $this->input->post('site_admin', TRUE);
            $formdata['site_mail'] = $this->input->post('site_mail', TRUE);
            $formdata['adminuser'] = $this->input->post('adminuser', TRUE);
            $formdata['adminpass'] = $this->input->post('adminpass', TRUE);
            if (!empty($formdata['adminpass'])) {
                $pass = $formdata['adminpass'];
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                $config = array (
                    'configvalue'   =>  $pass
                );
                $this->config_model->modify($config, "adminpass");
            }
            $update = array  (
                array (
                    'configkey' =>  'myname',
                    'configvalue'   =>  $formdata['myname']
                ),
                array (
                    'configkey' =>  'myhost',
                    'configvalue'   =>  $formdata['myhost']
                ),
                array (
                    'configkey' =>  'site_admin',
                    'configvalue'   =>  $formdata['site_admin']
                ),
                array (
                    'configkey' =>  'site_mail',
                    'configvalue'   =>  $formdata['site_mail']
                ),
                array (
                    'configkey' =>  'adminuser',
                    'configvalue'   =>  $formdata['adminuser']
                )
            );
            $this->config_model->modifyMulti($update, 'configkey');
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
    // 變更公告設定
    public function updateAnn()
    {
        $data['function_name'] = "變更公告設定";
        $data['site'] = $this->title;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-save-file"></span> 更新',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        //查詢用到的設定值
        $data['settings'] = $this->config_model->queryBy('cat', 3);
        
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('uploadable', '可用副檔名', 'trim|required');
        $this->form_validation->set_rules('ann_perpage', '每頁公告數', 'trim|required');
        $this->form_validation->set_rules('annday', '公告天數', 'trim|required');
        $this->form_validation->set_rules('ulfilenum', '附件數量', 'trim|required');
        $this->form_validation->set_rules('urlnum', '網址數量', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_updateann');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['uploadable'] = $this->input->post('uploadable', TRUE);
            $formdata['ann_perpage'] = $this->input->post('ann_perpage', TRUE);
            $formdata['annday'] = $this->input->post('annday', TRUE);
            $formdata['ulfilenum'] = $this->input->post('ulfilenum', TRUE);
            $formdata['urlnum'] = $this->input->post('urlnum', TRUE);
            $formdata['schoolnumber'] = $this->input->post('schoolnumber', TRUE);

            $update = array  (
                array (
                    'configkey' =>  'uploadable',
                    'configvalue'   =>  $formdata['uploadable']
                ),
                array (
                    'configkey' =>  'ann_perpage',
                    'configvalue'   =>  $formdata['ann_perpage']
                ),
                array (
                    'configkey' =>  'annday',
                    'configvalue'   =>  $formdata['annday']
                ),
                array (
                    'configkey' =>  'ulfilenum',
                    'configvalue'   =>  $formdata['ulfilenum']
                ),
                array (
                    'configkey' =>  'urlnum',
                    'configvalue'   =>  $formdata['urlnum']
                ),
                array (
                    'configkey' =>  'schoolnumber',
                    'configvalue'   =>  $formdata['schoolnumber']
                )
            );
            $this->config_model->modifyMulti($update, 'configkey');
            // 動作結束，回選單
            redirect('/Admin');
        }
    }
    // 變更郵件設定
    public function updateSMTP()
    {
        $data['function_name'] = "變更郵件設定";
        $data['site'] = $this->title;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['but1'] = array (
        'name'  =>  'sent',
        'type'  =>  'submit',
        'content' =>  '<span class="glyphicon glyphicon-save-file"></span> 更新',
        'class' =>  'btn btn-primary',
        'accesskey'   =>  's');
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
        //查詢用到的設定值
        $data['settings'] = $this->config_model->queryBy('cat', 4);
        
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('smtp_host', '主機', 'trim|required');
        $this->form_validation->set_rules('smtp_port', '連接埠', 'trim|required');
        $this->form_validation->set_rules('smtp_user', '帳號', 'trim|required');
        $this->form_validation->set_rules('smtp_pass', '密碼', 'trim|required');
        
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            // 載入 view
            $this->load->view('header', $data);
            $this->load->view('admin_updatesmtp');
            $this->load->view('footer');
        } else {
            // 接收表單
            $formdata['smtp_host'] = $this->input->post('smtp_host', TRUE);
            $formdata['smtp_port'] = $this->input->post('smtp_port', TRUE);
            $formdata['smtp_user'] = $this->input->post('smtp_user', TRUE);
            $formdata['smtp_pass'] = $this->input->post('smtp_pass', TRUE);

            $update = array  (
                array (
                    'configkey' =>  'smtp_host',
                    'configvalue'   =>  $formdata['smtp_host']
                ),
                array (
                    'configkey' =>  'smtp_port',
                    'configvalue'   =>  $formdata['smtp_port']
                ),
                array (
                    'configkey' =>  'smtp_user',
                    'configvalue'   =>  $formdata['smtp_user']
                ),
                array (
                    'configkey' =>  'smtp_pass',
                    'configvalue'   =>  $formdata['smtp_pass']
                )
            );
            $this->config_model->modifyMulti($update, 'configkey');
            // 動作結束，回選單
            redirect('/Admin');
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
            $parts = $this->parttb_model->queryList();
            $data['partid_data'] = array (
                'name'  =>  'partid',
                'class'     =>  'form-control',
                'options'   =>  $parts
            );
            $data['acc_data'] = array(
            'name' => 'use_same_account',
            'id'    =>  'use_same_account',
            'value'     =>  '1',
            'checked'   =>  FALSE,
            'class'     =>  'form-control' );
            $data['but1'] = array (
            'name'  =>  'sent',
            'type'  =>  'submit',
            'content' =>  '修改',
            'class' =>  'btn btn-primary',
            'accesskey'     =>  's');
            $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
                redirect('/Admin');
            } // 若非直接建立帳號，則採綁定帳號處理
            elseif ($formdata['userid'] != '') {
            // 處理本筆 oid 帳號
                $openidbind_data = array(
                'bind_userid'   =>  $formdata['userid'],
                'new'   =>  '0'
                );
                $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
                redirect('/Admin');
            } else {
            // 若非前兩種動作，則只處理確認或阻擋
            // 處理本筆 oid 帳號
                $openidbind_data = array(
                'new'   =>  $formdata['new'],
                'banned'   =>   $formdata['banned']
                );
                $this->openidbind_model->modify($oiduser->oid, $openidbind_data);
                redirect('/Admin');
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
        redirect('/Admin');
    }
    // 顯示單一登入使用者
    public function showOiduser()
    {
        $data['function_name'] = "顯示單一登入使用者";
        $data['site'] = $this->title;
        $data['classname'] = $this->classname;
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
        $data['button'] = '<a href="' . config_item('base_url') . '/index.php/Admin" class="btn btn-primary" accesskey="h"><span class="glyphicon glyphicon-cog"></span> 回管理選單</a>';
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
