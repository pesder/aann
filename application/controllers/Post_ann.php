<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post_ann extends CI_Controller
{

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
            $this->load->model('titletb_model');
            $this->load->model('anntb_model');
            $this->load->model('filetb_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
            $this->classname = "Post_ann";
            // 設定目前網址，供認證後跳回
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
    }
    public function auth()
    {
        $login = $this->session->userdata('userlogin');
        //從 session 判斷登入狀態，未經登入回到密碼輸入畫面，登入錯誤則顯示訊息
        if (empty($login)) {
            redirect('/Auth/chooseAuth');
        } elseif ($login['authpass'] == 0) {
            redirect('/Auth/chooseAuth');
        }
    }
    public function postAnnForm()
    {
        $data['function_name'] = "發布公告表單";
        $data['function_key'] = $this->classname . "/postAnnForm";
        $data['site'] = $this->title;
        
        $login = $this->session->userdata('userlogin');
        $urlpath = current_url();
        $this->session->set_userdata('nowurl', $urlpath);
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('title', '標題', 'trim|required');
        $this->form_validation->set_rules('comment', '內容', 'trim|required');
        $this->form_validation->set_rules('dueday', '內容', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            $typelist = array (
                "1.1" => "普通",
                "2.1" => "重要",
                "3.1" => "急件"
            );
            $data['urlnum'] = $this->config_model->queryValue('urlnum');
            $data['ulfilenum'] = $this->config_model->queryValue('ulfilenum');
            $annday = $this->config_model->queryValue('annday');
            $data['user'] = $login;
            $data['type_data'] = array (
                'name'  =>  'type',
                'class'     =>  'form-control',
                'options' => $typelist);
            $serial = array ('0' => '否', '1' => '是');
            $data['serialpost_data'] = array (
                    'name'  =>  'serial',
                    'class'     =>  'form-control',
                    'options'   => $serial
                );
            $local = array ('no' => '否', 'yes' => '是');
            $data['local_data'] = array (
                    'name'  =>  'local',
                    'class'     =>  'form-control',
                    'options'   => $local
                );
            $data['title_data'] = array (
                'name'  =>  'title',
                'id'    =>  'title',
                'class'     =>  'form-control col-sm-8'
                );
            $data['comment_data'] = array (
                'name'  =>  'comment',
                'id'    =>  'comment',
                'class'     =>  'form-control'
                );
            $dueday = new datetime(date('Y-m-d', time()));
            $offset = '+' . "$annday" . "day";
            $dueday->modify($offset);
            $data['date_data'] = array (
                'name'  =>  'dueday',
                'id'    =>  'datepicker',
                'class' =>  'form-control',
                'value' =>  $dueday->format('Y-m-d')
                );
            $data['but1'] = array (
                'name'  =>  'sent',
                'type'  =>  'submit',
                'content' =>  '送出公告',
                'class' =>  'btn btn-primary',
                'accesskey'     =>  's');
            //開始載入表單
            $this->auth();
            // 載入 view
            $this->load->view('header-jquery', $data);
            $this->load->view('postann_postannform_edit');
            $this->load->view('postann_postannform_edit_file');
            $this->load->view('postann_postannform_edit_url');
            $this->load->view('postann_postannform_edit_date');
            $this->load->view('postann_postannform_edit_bott');
            $this->load->view('footer');
        } else {
            $data['urlnum'] = $this->config_model->queryValue('urlnum');
            $data['ulfilenum'] = $this->config_model->queryValue('ulfilenum');
            $data['annday'] = $this->config_model->queryValue('annday');
            $data['fileuploadable'] = $this->config_model->queryValue('uploadable');
            
            $data['user'] = $login;
            // 接收表單
            // 先接收標題、內文
            $formdata['type'] = $this->input->post('type');
            $formdata['title'] = $this->input->post('title', TRUE);
            $formdata['comment'] = $this->input->post('comment', TRUE);
            $formdata['annday'] = $this->input->post('dueday', TRUE);
            $formdata['serial'] = $this->input->post('serial');
            $formdata['local'] = $this->input->post('local');
            $pid = $login['partid'];
            $uid = $login['userid'];
            $data['partname'] = $this->parttb_model->queryPartname($pid);
            $filelist = "";
            $urllist = "";

            //處理附件
            $config['upload_path']          = './files/' . $pid . "/" . $uid . "/";
            $config['allowed_types']        = $data['fileuploadable'];
            $config['overwrite']            = TRUE;
            $config['max_size']             = '10240';
            //$config['encrypt_name']         = TRUE;

            foreach ($_FILES as $key => $value) {
                // 檢測是否有上傳檔案，將檔名拆解後，設定原始名稱及數字化名稱
                if (!empty($_FILES[$key]["name"])) {
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
            for ($i = 0; $i < $data['urlnum']; $i++) {
                $j = $i + 1;
                $url = "url" . $j;
                $inurl = "url" . $j;
                // 若欄位有填寫，則將欄位內容收入url列表，同時利用 str_replace 去除多餘空白避免影響判讀
                if (!empty($this->input->post($inurl))) {
                    $formdata[$url] = str_replace(' ', '', $this->input->post($inurl, TRUE));
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
                'type'      =>  $formdata['type'],
                'local'     =>  $formdata['local']
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
            if ($formdata['serial'] == 0) {
                redirect('/Main');
            } elseif ($formdata['serial'] == 1) {
                redirect('/Post_ann/postAnnForm');
            }
        }
    }

    public function modify($tid)
    {
        $data['function_name'] = "編輯公告";
        $data['function_key'] = $this->classname . "/modify/" . $tid;
        $data['site'] = $this->title;
        
        $data['head'] = $this->titletb_model->query($tid);
        $data['body'] = $this->anntb_model->query($tid);
        $pid = $data['head']->partid;
        $uid = $data['body']->userid;
        $urlpath = '/Post_ann/modify/' . $tid;
        $this->session->set_userdata('nowurl', $urlpath);
        // 表單驗證
        $this->form_validation->set_message('required', '{field}未填');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('title', '標題', 'trim|required');
        $this->form_validation->set_rules('comment', '內容', 'trim|required');
        $this->form_validation->set_rules('dueday', '內容', 'trim|required');
        // 表單判斷
        if ($this->form_validation->run() == FALSE) {
            $typelist = array (
                "1.1" => "普通",
                "2.1" => "重要",
                "3.1" => "急件"
            );
            $login = $this->session->userdata('userlogin');
            $data['urlnum'] = $this->config_model->queryValue('urlnum');
            $data['ulfilenum'] = $this->config_model->queryValue('ulfilenum');
            $data['user'] = $login;
            // 載入 titletb anntb
            $data['head'] = $this->titletb_model->query($tid);
            $data['body'] = $this->anntb_model->query($tid);
            // 載入前次公告數值
            $data['type_data'] = array (
                'name'  =>  'type',
                'class'     =>  'form-control',
                'options' => $typelist,
                'value' =>  $data['head']->type);
            $serial = array ('0' => '否', '1' => '是');
            $data['serialpost_data'] = array (
                    'name'  =>  'serial',
                    'class'     =>  'form-control',
                    'options'   => $serial
                );
            $local = array ('no' => '否', 'yes' => '是');
            $data['local_data'] = array (
                    'name'  =>  'local',
                    'class'     =>  'form-control',
                    'options'   => $local,
                    'selected' =>  $data['head']->local
                );
            $data['title_data'] = array (
                'name'  =>  'title',
                'id'    =>  'title',
                'class'     =>  'form-control col-sm-8',
                'value' =>  $data['head']->subject
                );
            $data['comment_data'] = array (
                'name'  =>  'comment',
                'id'    =>  'comment',
                'class'     =>  'form-control',
                'value' =>  $data['body']->comment
                );
            $overtime = strtotime($data['head']->overtime);
            $dueday = date('Y-m-d', $overtime);
            $data['date_data'] = array (
                'name'  =>  'dueday',
                'id'    =>  'datepicker',
                'class' =>  'form-control',
                'value' =>  $dueday
                );
            $data['but1'] = array (
                'name'  =>  'sent',
                'type'  =>  'submit',
                'content' =>  '更新公告',
                'class' =>  'btn btn-primary',
                'accesskey'     =>  's');
            
            //判斷 URL
            if (empty($data['body']->url)) {
                $data['hasurl'] = "無";
                $data['annurl'] = [];
            } else {
                $data['hasurl'] = "有";
                $data['annurl'] = explode(" ", $data['body']->url);
                //編輯介面無需判斷是否有網址註解
            }
            //判斷附件
            $file_empty = $data['ulfilenum'];
            if (empty($data['body']->filename)) {
                $data['hasfile'] = "無";
                $data['annfile'] = [];
                $data['file_empty'] = $file_empty;
            } else {
                $data['hasfile'] = "有";
                $data['annfile'] = explode(" ", $data['body']->filename);
                $data['file_empty'] = $file_empty - count($data['annfile']);
                //利用上面陣列複製出一個查詢檔名用陣列
                $data['annfilereadable'] = $data['annfile'];
                $data['filenotthere'] = [];
                foreach ($data['annfilereadable'] as $index => $name) {
                    $query = $this->filetb_model->mathFile($data['head']->partid, $data['body']->userid, $name);
                    if (empty($query)) {
                        $filelocation = "./files/" . $data['head']->partid . "/" . $data['body']->userid . "/" . $name;
                        if (is_file($filelocation)) {
                            $data['annfilereadable'][$index] = $name;
                            $data['filenotthere'][$index] = 1;
                        } else {
                            $data['annfilereadable'][$index] = $name . "(檔案遺失，無法正常下載)";
                            $data['filenotthere'][$index] = 0;
                        }
                    } else {
                        $filelocation = "./files/" . $data['head']->partid . "/" . $data['body']->userid . "/" . $name;
                        if (is_file($filelocation)) {
                            $data['annfilereadable'][$index] = $query->origname;
                            $data['filenotthere'][$index] = 1;
                        } else {
                            $data['annfilereadable'][$index] = $query->origname . "(檔案遺失，無法正常下載)";
                            $data['filenotthere'][$index] = 0;
                        }
                    }
                }
            }
            //開始載入表單
            $this->auth();
            $login = $this->session->userdata('userlogin');
            // 檢查認證者是否為原始發文者
            if ($login['userid'] == $uid && $login['partid'] == $pid) {
            // 載入 view
                $this->load->view('header-jquery', $data);
                $this->load->view('postann_postannform_edit');
            // 有附件則載入相關 view
                if (!empty($data['body']->filename)) {
                    $this->load->view('postann_modify_file_hasfile');
                } else {
                    $this->load->view('postann_modify_file');
                }
                $this->load->view('postann_modify_url');
                $this->load->view('postann_modify_date');
                $this->load->view('postann_postannform_edit_bott');
                $this->load->view('footer');
            } else {
            // 提示身分認證須為原發文者
                $data['message'] = "必須是原始公告者才能修改公告";
            // 載入 view
                $this->load->view('header-jquery', $data);
                $this->load->view('postann_postannform_deny');
                $this->load->view('footer');
            }
        } else {
            $data['urlnum'] = $this->config_model->queryValue('urlnum');
            $data['ulfilenum'] = $this->config_model->queryValue('ulfilenum');
            $data['fileuploadable'] = $this->config_model->queryValue('uploadable');
            $data['user'] = $login;
            // 載入 titletb anntb
            $data['head'] = $this->titletb_model->query($tid);
            $data['body'] = $this->anntb_model->query($tid);
            // 接收表單
            // 先接收標題、內文
            $formdata['type'] = $this->input->post('type');
            $formdata['title'] = $this->input->post('title', TRUE);
            $formdata['comment'] = $this->input->post('comment', TRUE);
            $formdata['annday'] = $this->input->post('dueday', TRUE);
            $data['partname'] = $this->parttb_model->queryPartname($pid);
            $urllist = "";

            //處理附件
            $config['upload_path']          = './files/' . $pid . "/" . $uid . "/";
            $config['allowed_types']        = $data['fileuploadable'];
            $config['overwrite']            = TRUE;
            $config['max_size']             = '10240';
            //$config['encrypt_name']         = TRUE;
            //取回現有的檔案列表
            $filelist = $data['body']->filename;
            if (!empty($filelist)) {
                $filelist = $filelist . " ";
            }
            foreach ($_FILES as $key => $value) {
                // 檢測是否有上傳檔案，將檔名拆解後，設定原始名稱及數字化名稱
                if (!empty($_FILES[$key]["name"])) {
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
            for ($i = 0; $i < $data['urlnum']; $i++) {
                $j = $i + 1;
                $url = "url" . $j;
                $inurl = "url" . $j;
                // 若欄位有填寫，則將欄位內容收入url列表，同時利用 str_replace 去除多餘空白避免影響判讀
                if (!empty($this->input->post($inurl))) {
                    $formdata[$url] = str_replace(' ', '', $this->input->post($inurl, TRUE));
                    $urllist = $urllist . $formdata[$url] . " ";
                }
            }
            $firsttime = $data['head']->posttime;
            $newposttime = date("Y-m-d H:i:s");
            // 寫入主要資籵庫
            $titletb = array (
                'partid'    =>  $pid,
                'partname'  =>  $data['partname']['partname'],
                'subject'   =>  $formdata['title'],
                'posttime'  =>  $newposttime,
                'firsttime' =>  $firsttime,
                'overtime'  =>  $formdata['annday'],
                'type'      =>  $formdata['type']
            );
            $this->titletb_model->modify($tid, $titletb);
            $anntb = array (
                'userid'    =>  $uid,
                'ip'        =>  $this->input->ip_address(),
                'filename'  =>  rtrim($filelist),
                'url'       =>  rtrim($urllist),
                'comment'   =>  $formdata['comment']
            );
            $this->anntb_model->modify($tid, $anntb);
            // 寫入完資料庫，判斷是否為連續公告
            redirect('/Main');
        }
    }
    // 刪除公告功能
    public function deleteAnn($tid, $pid, $uid)
    {
        $data['site'] = $this->title;
        $login = $this->session->userdata('userlogin');
        $urlpath = '/Post_ann/deleteAnn/' . $tid . "/" . $pid . "/" . $uid;
        $this->session->set_userdata('nowurl', $urlpath);
            $this->auth();
            $login = $this->session->userdata('userlogin');
            // 檢查認證者是否為原始發文者
        if ($login['userid'] == $uid && $login['partid'] == $pid) {
            $this->load->helper('file');
            // 載入 anntb
            $data['body'] = $this->anntb_model->query($tid);
            // 取出檔案列表
            $data['annfile'] = explode(" ", $data['body']->filename);
            foreach ($data['annfile'] as $index => $filename) {
                // 進行檔案刪除動作
                $filepath = "./files/" . $pid . "/" . $uid . "/" . $filename;
                delete_files($filepath);
                // 刪除 filetb 中檔名對應資料
                $filenamedel = array (
                    'partid'    =>  $pid,
                    'userid'    =>  $uid,
                    'filelist'  =>  $filename
                );
                $this->filetb_model->deleteFile($filenamedel);
            }
            // 刪除資料庫
            $this->anntb_model->delete($tid);
            $this->titletb_model->delete($tid);
            // 跳回首頁
            redirect('Main/');
        } else {
            // 提示身分認證須為原發文者
            $data['message'] = "必須是原始公告者才能刪除公告";
            // 載入 view
            $this->load->view('header-jquery', $data);
            $this->load->view('postann_postannform_deny');
            $this->load->view('footer');
        }
    }
    // 附件檔案刪除功能
    public function deleteFile($tid, $pid, $uid, $filename)
    {
        $this->load->helper('file');
        $data['body'] = $this->anntb_model->query($tid);
        $oldfilename = $data['body']->filename .  " ";
        $delfilename = $filename . " ";
        //利用取代將要刪除的檔案名稱從字串中移除
        $newfilename = str_replace($delfilename, "", $oldfilename);
        //移除結尾空白避免判讀檔案錯誤
        $newfilename = rtrim($newfilename);
        $data = array (
            'filename'  =>  $newfilename
        );
        // 條改 anntb 檔案列表
        $this->anntb_model->modify($tid, $data);
        // 進行檔案刪除動作
        $filepath = "./files/" . $pid . "/" . $uid . "/" . $filename;
        delete_files($filepath);
        // 刪除 filetb 中檔名對應資料
        $filenamedel = array (
            'partid'    =>  $pid,
            'userid'    =>  $uid,
            'filelist'  =>  $filename
        );
        $this->filetb_model->deleteFile($filenamedel);
        // 跳回公告編輯畫面
        $returnmodify = "PostAnn/modify/" . $tid . "/" . $pid . "/" . $uid;
        redirect($returnmodify);
    }
}
