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
            $user_profile = $openid->getAttributes();
            // die(var_export($user_profile));
            /*
            array (
            'axschema/person/guid' => 'ab1a1b5769b6886f40e8e1b2c349e36b470f76b51d3ed8e9e784843fa0ca9355',
            'axschema/school/titleStr' => '[{"id":"A00015","title":["編制人員"]}]',
            'axschema/school/id' => 'A00015',
            'contact/email' => '5348221@ylc.edu.tw',
            'namePerson' => '測試帳號',
            )

            array (
            'axschema/person/guid' => 'ab1a1b5769b6886f40e8e1b2c349e36b470f76b51d3ed8e9e784843fa0ca9355',
            'axschema/school/titleStr' => '[{"id":"094659","title":["教師"]}]',
            'axschema/school/id' => '094659',
            'contact/email' => '5348221@ylc.edu.tw',
            'namePerson' => '測試帳號',
            )
            //南投
            array ( 'contact/email' => ' t03238@mail.edu.tw', 'namePerson' => '劉坤榮', )

            array (
            'axschema/person/guid' => '3ba3a257aec87686e0b52bb81c4ca338d04dd25205358e8730d5fa87cfdf2bff',
            'axschema/school/titleStr' => '[{\\"id\\":\\"084719a1\\",\\"title\\":[\\"教師\\"]},{\\"id\\":\\"084716a1\\",\\"title\\":[\\"教師\\"]}]',
            'axschema/school/id' => '084719a1',
            'contact/email' => 'kk@k.2.k',
            'namePerson' => '測試一',
            )

            //彰化
            array (
            'axschema/person/guid' => '4208f4152cb215d19edfa78d4e85ae2ccee65497ed68af69e5ca7641510d3af6',
            'axschema/school/titleStr' => '[{"id":"074628","title":["教師","學校管理者"]}]',
            'axschema/school/id' => '074628',
            'contact/email' => 'NA',
            'namePerson' => '測試',
            )

            //花蓮縣
            array (
            'axschema/person/guid' => '43f51043286df35cbe4d227ef429bdddc87f717d083892a2f71984c89b98e52c',
            'axschema/school/titleStr' => '[{"titles":["教師"],"sid":"154512"}]',
            'axschema/school/id' => '154512',
            'contact/email' => 'ooo@gmail.com',
            'namePerson' => '蕭老師',
            )
             */
            // Login or logout url will be needed depending on current user state.
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

                //搜尋有無相同username資料
                //login_xoops($uname, $name, $email, $SchoolCode, $JobName);
            }
        }
		
	}
    
}
