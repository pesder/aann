<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostAnn extends CI_Controller {

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
            $this->load->model('titletb_model');
            $this->load->model('anntb_model');
            $this->load->model('filetb_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
        }

    public function postAnnForm() 
    {
        $data['function_name'] = "發布公告表單";
        $data['site'] = $this->title->configvalue;
        $login = $this->session->userdata('UserLogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if(empty($login))
        {
            redirect('/Auth/postAnnAuth');
        }
        elseif ($login['authpass'] == 0)
        {
            $data['message'] = $login['denyreason'];
			// 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('postann_postannform_deny');
			$this->load->view('footer');
            
        } elseif ($login['authpass'] == 1)
        {
                    // 表單驗證
		$this->form_validation->set_message('required','{field}未填');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		$this->form_validation->set_rules('title', '標題', 'trim|required');
		$this->form_validation->set_rules('comment', '內容', 'trim|required');
		// 表單判斷
		if($this->form_validation->run() == FALSE) 
		{
            $typelist = array (
                "1.1" => "普通",
                "2.1" => "重要",
                "3.1" => "急件"
            );
            $data['urlnum'] = $this->config_model->queryBy('configkey','urlnum');
            $data['ulfilenum'] = $this->config_model->queryBy('configkey','ulfilenum');
            $data['annday'] = $this->config_model->queryBy('configkey','annday');
            $data['user'] = $login;
            $data['typelist'] = $typelist;
            //開始載入表單
            // 載入 view
			$this->load->view('header-jquery',$data);
			$this->load->view('postann_postannform_edit');
            $this->load->view('postann_postannform_edit_file');
            $this->load->view('postann_postannform_edit_url');
            $this->load->view('postann_postannform_edit_date');
            $this->load->view('postann_postannform_edit_bott');
			$this->load->view('footer');

		} else
        {
            $data['urlnum'] = $this->config_model->queryBy('configkey','urlnum');
            $data['ulfilenum'] = $this->config_model->queryBy('configkey','ulfilenum');
            $data['annday'] = $this->config_model->queryBy('configkey','annday');
            
            $data['user'] = $login;
            // 接收表單
            // 先接收標題、內文
            $formdata['type'] = $this->input->post('type');
            $formdata['title'] = $this->input->post('title');
            $formdata['comment'] = $this->input->post('comment');
            $formdata['annday'] = $this->input->post('dueday');
            $formdata['serial'] = $this->input->post('serial');
            $pid = $login['partid'];
            $uid = $login['userid'];
            $data['partname'] = $this->parttb_model->queryPartname($pid);
            $filelist = "";
            $urllist = "";

            //處理附件
            $config['upload_path']          = './files/' . $pid . "/" . $uid . "/";
            $config['allowed_types']        = '*';
            $config['overwrite']            = true;
            $config['max_size']             = '10240';
            //$config['encrypt_name']         = true;

            foreach($_FILES as $key => $value) {
                // 檢測是否有上傳檔案，將檔名拆解後，設定原始名稱及數字化名稱
                if (!empty($_FILES[$key]["name"]))
                {
                    $filename_ar = explode(".", $_FILES[$key]["name"]);
                    $filename_ext = $filename_ar[count($filename_ar) - 1];
                    $file_index = substr($key, -1);
                    $newfilename = time() . $file_index . "." . $filename_ext;
                    $config['file_name'] = $newfilename;

                }
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
                //開始上傳檔案動作
                if (!empty($value['name'])) {
                    if (!$this->upload->do_upload($key)) {
                        $data["error"] = $this->upload->display_errors();
      
                    } else {
                        $fInfo = $this->upload->data();
                        // 將轉換好的檔名寫入 filetb 資料庫
                        $addfilelist = array (
                           'partid'    => $pid,
                            'userid'    => $uid,
                            'filelist'  => $newfilename,
                            'origname'  => $_FILES[$key]["name"]
                        );
                        $this->filetb_model->writeFile($addfilelist);
                        $filelist = $filelist . $newfilename . " ";
                    }
                }
            }

            // 收集 URL
            for ($i = 0; $i < $data['urlnum']->configvalue; $i++)
            {
                $j = $i + 1;
                $url = "url" . $j;
                $inurl = "url" . $j;
                if (!empty($this->input->post($inurl)))
                {
                    $formdata[$url] = $this->input->post($inurl);
                    $urllist = $urllist . $formdata[$url] . " ";
                }
                
            }
            echo date("Y-m-d H:i:s");
            // 寫入主要資籵庫
            $titletb = array (
                'partid'    =>  $pid,
                'partname'  =>  $data['partname']['partname'],
                'subject'   =>  $formdata['title'],
                'posttime'  =>  date("Y-m-d H:i:s"),
                'overtime'  =>  $formdata['annday'],
                'type'      =>  $formdata['type']
            );
            $tid = $this->titletb_model->writeTitle($titletb);
            $anntb = array (
                'tid'   =>  $tid,
                'userid'    =>  $uid,
                'ip'        =>  $this->input->ip_address(),
                'filename'  =>  rtrim($filelist),
                'url'       =>  rtrim($urllist),
                'comment'   =>  $formdata['comment']
            );
            $this->anntb_model->writeAnn($anntb);
            // 寫入完資料庫，判斷是否為連續公告
            if ($formdata['serial'] == 0)
            {
                $this->session->set_userdata('UserLogin', "");
                redirect('/Main');
            } elseif ($formdata['serial'] == 1)
            {
                redirect('/PostAnn/postAnnForm');
            }
        }
            
        }
        
    }

}
?>