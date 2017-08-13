<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('url');
            // 載入列表 model
            $this->load->model('parttb_model');
            $this->load->model('titletb_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
            // 讀取每頁顯示文章數
            $this->annpp = $this->config_model->queryValue('ann_perpage');
        }

    public function index()
    {
        $this->session->set_userdata('CurrentPage','1');
        $emptyuser = array (
            'authpass' => "",
            'denyreason' => ""
        );
        $this->session->set_userdata('userlogin', $emptyuser);
        $emptyoiduser = array (
            'oidpass' => "",
            'denyreason' => ""
        );
        $this->session->set_userdata('openid_user', $emptyoiduser);
        $emptyadmin = array (
            'adminauthpass' => "",
            'denyreason' => ""
        );
        $this->session->set_userdata('adminlogin', $emptyadmin);
        // 檢測如果顯示公告日數未設定則使用資料庫中的預設值
        if (empty($this->session->userdata('ann_list_days')))
        {
            $show_days = $this->config_model->queryValue('ann_list_days');
            $this->session->set_userdata('ann_list_days', $show_days);
        }
        
        $this->session->set_userdata('pp', $this->annpp);
        $this->session->set_userdata('selected_part', '');
        $this->session->set_userdata('serach_keyword', '');
        $data['function_name'] = "";
        $data['site'] = $this->title;
        $data['ann_list_days'] = $this->session->userdata('ann_list_days');
        //$data['list'] = $this->titletb_model->queryLimitHome($this->annpp, '0');
        $data['list'] = $this->titletb_model->joinSearch($this->annpp, '0');
        $data['pages'] = $this->session->userdata('TotalPages');
        //讀取目前所在頁
        $data['current'] = $this->session->userdata('CurrentPage');
        $data['options'] = $this->parttb_model->queryList();


        // 載入 view
        $this->load->view('header',$data);
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['list']))
        {
            $this->load->view('main_nolist');
        }
        $this->load->view('main_index');
        $this->load->view('main_index_bott_home');
        $this->load->view('footer');
    }
        // 下一頁功能
        public function goPage($page)
    {
        $this->session->set_userdata('CurrentPage', $page);
        //判斷，第1頁與其他頁的偏移量不同
        if ($page == 1) {
            $gooffset = 0;
        } else {
            $gooffset = 1 + ($page - 1) * $this->annpp;
        }
        $data['function_name'] = "第 $page 頁";
        $data['site'] = $this->title;
        $data['ann_list_days'] = $this->session->userdata('ann_list_days');
        $data['list'] = $this->titletb_model->joinSearch($this->annpp, $gooffset);
        $data['pages'] = $this->session->userdata('TotalPages');
        $data['current'] = $this->session->userdata('CurrentPage');
        $data['options'] = $this->parttb_model->queryList();


        // 載入 view
        $this->load->view('header',$data);
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['list']))
        {
            $this->load->view('main_nolist');
        }
        $this->load->view('main_index');
        // 判斷目前所在頁面，使用對應的導覽列
        if ($page == 1){
            $this->load->view('main_index_bott_home');
        } elseif ($page < $this->session->userdata('TotalPages')['pages']){
            $this->load->view('main_index_bott_mid');
        } else {
            $this->load->view('main_index_bott_end');
        }
        $this->load->view('footer');
    }
    // 設定顯示日數功能
        public function setDays()
    {
        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('ann_list_days', '顯示日數', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            $this->index();
        } else
        {
            $data['site'] = $this->title;
            $formdata['ann_list_days'] = $this->input->post('ann_list_days');
            $this->session->set_userdata('ann_list_days', $formdata['ann_list_days']);
            $this->showList('1');
        }
    }
    // 處室選擇功能
        public function selectPart()
    {
        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('partid', '處室選擇', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            $this->index();
        } else
        {
            $data['site'] = $this->title;
            $formdata['partid'] = $this->input->post('partid');
            if (!empty($formdata['partid'])) {
            $this->session->set_userdata('selected_part', $formdata['partid']);
            $this->showList('1');
            }
        }
    }
    // 搜尋功能
        public function searchKeyword()
    {
        
        // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('search', '搜尋內容', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            $this->index();
        } else
        {
            $data['site'] = $this->title;
            $formdata['search'] = $this->input->post('search');
            $this->session->set_userdata('serach_keyword', $formdata['search']);
            $this->showList('1');
        }
    }
    public function showList($page)
    {
        $this->session->set_userdata('CurrentPage', $page);
        //判斷，第1頁與其他頁的偏移量不同
        if ($page == 1) {
            $gooffset = 0;
        } else {
            $gooffset = 1 + ($page - 1) * $this->annpp;
        }
        
        $data['function_name'] = "第 $page 頁";
        $data['site'] = $this->title;
        $data['ann_list_days'] = $this->session->userdata('ann_list_days');
        $data['list'] = $this->titletb_model->joinSearch($this->annpp, $gooffset);
        $data['pages'] = $this->session->userdata('TotalPages');
        $data['current'] = $this->session->userdata('CurrentPage');
        $data['options'] = $this->parttb_model->queryList();
        
        
        // 載入 view
        $this->load->view('header',$data);
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['list']))
        {
            $this->load->view('main_nolist');
        }
        $this->load->view('main_index');
        // 判斷目前所在頁面，使用對應的導覽列
        if ($page == 1){
            $this->load->view('main_index_bott_home');
        } elseif ($page < $this->session->userdata('TotalPages')['pages']){
            $this->load->view('main_index_bott_mid');
        } else {
            $this->load->view('main_index_bott_end');
        }
        $this->load->view('footer');  
        
    }
    public function viewAnn($id)
    {
        
        $data['function_name'] = "瀏覽公告";
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        // 載入 anntb，為公告本文資料表
        $this->load->model('anntb_model');
        $this->load->model('usertb_model');
        $this->load->model('filetb_model');
        // 查詢公告標題資訊與本文
        $data['site'] = $this->title;
        $data['head'] = $this->titletb_model->query($id);
        $data['body'] = $this->anntb_model->query($id);
        $data['user'] = $this->usertb_model->queryBy('userid',$data['body']->userid);
        //$data['realname'] = $this->usertb_model->querySingleName($data['body']->userid);
        //讀取目前所在頁
        $data['current'] = $this->session->userdata('CurrentPage');
        //增加點閱計數
        $this->titletb_model->addHit($id, $data['head']);
        // 遮蔽 IP 資料
        if(filter_var($data['body']->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
            {
               $data['body']->ip = preg_replace('/(?!\d{1,3}\.\d{1,3}\.)\d/', '?', $data['body']->ip);
            } elseif (filter_var($data['body']->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
            {
               $data['body']->ip = preg_replace('/(?!\d{1,4}\:)\d/', '?', $data['body']->ip);
            }
        //判斷 URL
        if (empty($data['body']->url)) {
            $data['hasurl'] = "無";
            $data['annurl'] = [];
        } else
        {
            $data['hasurl'] = "有";
            $data['annurl'] = explode(" ", $data['body']->url);
            $data['annurlreadable'] = $data['annurl'];
            foreach ($data['annurlreadable'] as $index => $name) 
            {
                $urlhascomment = strpos($name, "!");
                if ($urlhascomment === false)
                {
                    $data['annurlreadable'][$index] = $name;
                } else
                {
                    $newurl = explode("!", $name);
                    $data['annurl'][$index] = $newurl[0];
                    $data['annurlreadable'][$index] = $newurl[1];
                }
            }
        }
        //判斷附件
        if (empty($data['body']->filename)) {
            $data['hasfile'] = "無";
            $data['annfile'] = [];
        } else
        {
            $data['hasfile'] = "有";
            $data['annfile'] = explode(" ", $data['body']->filename);
            //利用上面陣列複製出一個查詢檔名用陣列
            $data['annfilereadable'] = $data['annfile'];
            $data['filenotthere'] = [];
            foreach ($data['annfilereadable'] as $index => $name) 
            {
                $query = $this->filetb_model->mathFile($data['head']->partid,$data['body']->userid,$name);
                if (empty($query))
                {
                    $filelocation = "./files/" . $data['head']->partid . "/" . $data['body']->userid . "/" . $name;
                    if (is_file($filelocation))
                    {
                        $data['annfilereadable'][$index] = $name;
                        $data['filenotthere'][$index] = 1;
                    } else
                    {
                        $data['annfilereadable'][$index] = $name . "(檔案遺失，無法正常下載)";
                        $data['filenotthere'][$index] = 0;
                    }
                } else 
                {
                    $filelocation = "./files/" . $data['head']->partid . "/" . $data['body']->userid . "/" . $name;
                    if (is_file($filelocation))
                    {
                        $data['annfilereadable'][$index] = $query->origname;
                        $data['filenotthere'][$index] = 1;
                    } else
                    {
                        $data['annfilereadable'][$index] = $query->origname . "(檔案遺失，無法正常下載)";
                        $data['filenotthere'][$index] = 0;
                    }
                    
                }
            }
        }
        // 檢查是否為內部公告
        $login = $this->session->userdata('userlogin');
        $oidlogin = $this->session->userdata('openid_user');
        $islocal = $this->session->tempdata('readlocal');
        if (($data['head']->local == 'yes') && ($islocal != 1)) {
            $this->warnLocal();
        } elseif (($data['head']->local == 'no') || ($islocal = 1)) {
        // 載入 view
        $this->load->view('header',$data);
        // 檢查是否存在 list ，若無則顯示相關資訊
        if(empty($data['body']))
        {
            $this->load->view('viewann_nolist');
        }
        $this->load->view('main_viewann');
        // 有網址則載入相關 view
        if (!empty($data['body']->url))
        {
            $this->load->view('main_viewann_url');
        }
        // 有附件則載入相關 view
        if (!empty($data['body']->filename))
        {
            $this->load->view('main_viewann_file');
        }
        $this->load->view('main_viewann_end');
        $this->load->view('footer');   
        } 
    }
    // 內部文件警告
    public function warnLocal()
    {
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        $message = "本文為內部文件，必須為本系統內有帳號的使用者或單一登入帳號才能觀看，請";
        $message .= '<a href="' . config_item('base_url') . '/index.php/Auth/postAnnAuth" class="btn btn-primary">登入本系統帳號</a>';
        $message .= "或是";
        $message .= '<a href="' . config_item('base_url') . '/index.php/Openid/get_ylc" class="btn btn-primary">登入單一登入帳號</a>';
        $message .= "以取得觀看權限。";
        $this->session->set_flashdata('message', $message);
        $data['site'] = $this->title;
        $data['message'] = $this->session->flashdata('message');
        // 載入 view
			$this->load->view('header',$data);
			$this->load->view('reset_confirm_message');
			$this->load->view('footer');
    }
    public function download($pid,$uid,$filename = NULL) 
    {
        // load download helder
        $this->load->helper('url');
        $this->load->helper('download');
        // read file contents
        $data = file_get_contents(base_url('/files/' . $pid . '/' . $uid . '/' .$filename));
        force_download($filename, $data);
    }
}
?>