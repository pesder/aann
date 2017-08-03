<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
			$this->load->helper('email');
            // 載入列表 model
            $this->load->model('usertb_model');

            // 讀取網站名稱
            $this->title = $this->config_model->queryBy('configkey','myname');
            // 設定目前網址，供認證後跳回
            $urlpath = current_url();
            $this->session->set_userdata('nowurl', $urlpath);
            
        }

	public function index()
	{
		
	}
}
