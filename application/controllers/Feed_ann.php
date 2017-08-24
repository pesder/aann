<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feed_ann extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        // 載入列表 model
        $this->load->model('titletb_model');
        $this->load->model('config_model');
        // 讀取網站名稱
        $this->title = $this->config_model->query_value('myname');
        $this->site = $this->config_model->query_value('myhost');
    }

    public function index()
    {
    }
    //  RoumenDamianoff 的使用範例
    public function feed($rss = 'atom')
    {
    // creating rss feed with our most recent 20 posts in variable $post
        $posts = $this->titletb_model->join_feed_ann(14);
    // first load the library
        $this->load->library('feed');

    // create new instance
        $feed = new Feed();

    // set your feed's title, description, link, pubdate and language
        $feed->title = $this->title;
        $feed->description = $this->title . "訂閱";
        $feed->link = $this->site;
        $feed->lang = 'zh-tw';
        $feed->pubdate = $posts[0]->posttime; // date of your last update (in this example create date of your latest post)

    // add posts to the feed
        foreach ($posts as $post) 
        {
            $slug = $this->site . "/index.php/Main/view_ann/" . $post->tid;
            if ($post->local === "yes") 
            {
                $post->comment = "內部公告，需登入才能閱讀。";
            }
            // set item's title, author, url, pubdate and description
            $feed->add($this->security->xss_clean($post->subject), $this->security->xss_clean($post->partname), $slug, $post->posttime, html_escape($this->security->xss_clean(mb_substr($post->comment, 0, 90, "UTF-8"))));
        }

    // show your feed (options: 'atom' (recommended) or 'rss')
        $feed->render($rss);
    }
}
