<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Openid extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        $this->load->helper('url');
    }
    public function index()
    {
        echo "Back!";
    }

    public function get_ylc()
    {
        $retrive_data = $this->input->get(null, TRUE);
        if ( ! empty($retrive_data)) 
        {
            // 判斷類別為教職員或學生
            $retrive_data['openid_ext2_value_titleStr'] = (strpos($retrive_data['openid_ext2_value_titleStr'], "學生") !== FALSE) ? "student" : "teacher";
            // 擷取單一登入使用者帳號
            $strip_o = array('http://', '.openid.ylc.edu.tw/');
            $strip_r = array('','');
            $retrive_data['openid_identity'] = str_replace($strip_o, $strip_r, $retrive_data['openid_identity']);
            // 準備要使用的單一登入相關資訊
            $return_data = array (
                'fullname'  =>  $retrive_data['openid_ext1_fullname'],
                'email'  =>  $retrive_data['openid_ext1_email'],
                'job'  =>  $retrive_data['openid_ext2_value_titleStr'],
                'school'  =>  $retrive_data['openid_ext2_value_sid'],
                'openid_id'  =>  $retrive_data['openid_identity'],
            );
            // 資訊存入 session
            $this->session->set_userdata('openid_user', $return_data);
            // 導向驗證畫面
            redirect('/Auth/openid_auth');
        } 
        else 
        {
            $this->load->library('oid_ylc');
            $conty = "ylc";
            $openid_identity = "http://openid.ylc.edu.tw";
            $openid = new Oid_ylc(config_item('base_url'));
        
            if ( ! $openid->mode) 
            {
                $openid->identity = $openid_identity;
                $openid->required = array('contact/email', 'namePerson/friendly', 'namePerson');
                $openid->optional = array('axschema/person/guid', 'axschema/school/titleStr', 'axschema/school/id', 'tw/person/guid', 'tw/isas/roles');
                header('Location: ' . $openid->authUrl());
            } 
            else 
            {
            }
        }
    }
}
