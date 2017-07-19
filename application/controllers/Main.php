<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            // 載入列表 model
            $this->load->model('parttb_model');
            $this->load->model('titletb_model');
            $this->load->model('config_model');
        }

    public function index()
    {
        $data['site'] = $this->config_model->queryby('configkey','myname');
        $data['list'] = $this->titletb_model->query( '6');
        print_r($data['site']);
        echo "<br>";
        print_r($data['list']);
        //$data['lists'] = $this->titletb_model->query_limit(25);
        // 載入 view
        $this->load->view('header');
        //$this->load->view('lists_index',$data);
        $this->load->view('footer');
    }
}
?>