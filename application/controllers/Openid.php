<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Openid extends CI_Controller {

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
        $retrive_data = $this->input->get(NULL, TRUE);
        if (!empty($retrive_data))
        {
            print_r($retrive_data);
            // 判斷類別為教職員或學生
            $retrive_data['openid_ext2_value_titleStr'] = (strpos($retrive_data['openid_ext2_value_titleStr'], "學生") !== false) ? "student" : "teacher";
            $return_data = array (
                'fullname'  =>  $retrive_data['openid_ext1_fullname'],
                'email'  =>  $retrive_data['openid_ext1_email'],
                'job'  =>  $retrive_data['openid_ext2_value_titleStr'],
                'school'  =>  $retrive_data['openid_ext2_value_sid'],
                'openid_id'  =>  $retrive_data['openid_identity'],
            );
            print_r($return_data);
            
        } else
        {
        $this->load->library('oid_ylc');
        $conty = "ylc";
        $openid_identity = "http://openid.ylc.edu.tw";
        $openid = new Oid_ylc(config_item('base_url'));
        if (!$openid->mode) {
            $openid->identity = $openid_identity;
            $openid->required = array('contact/email', 'namePerson/friendly', 'namePerson');
            $openid->optional = array('axschema/person/guid', 'axschema/school/titleStr', 'axschema/school/id', 'tw/person/guid', 'tw/isas/roles');
            header('Location: ' . $openid->authUrl());

        } else {
            //$user_profile = $openid->getAttributes();
            /*
            if ($user_profile) {
                $myts = MyTextsanitizer::getInstance();

                $SchoolCode = $myts->addSlashes($user_profile['axschema/school/id']);

                if (strtoupper($user_profile['contact/email']) == "NA" or empty($user_profile['contact/email'])) {
                    $uname = substr($user_profile['axschema/person/guid'], 0, 6) . "_{$conty}";
                    $email = "{$uname}@{$SchoolCode}.{$conty}.edu.tw";
                } else {

                    $user_profile['contact/email'] = trim($user_profile['contact/email']);
                    $the_id                        = explode("@", $user_profile['contact/email']);
                    $uname                         = trim($the_id[0]) . "_" . $conty;
                    $email                         = $user_profile['contact/email'];
                }
                //$uid = $user['id'];
                $name = $myts->addSlashes($user_profile['namePerson']);

                $JobName = (strpos($user_profile['axschema/school/titleStr'], "學生") !== false) ? "student" : "teacher";
            }
            print_r($user_profile);
            */
        }
        }
	}
}
