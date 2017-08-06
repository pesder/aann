<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FeedAnn extends CI_Controller {

    public function __construct()
        {
            parent::__construct();
            $this->load->library('session');
            // 載入列表 model
            $this->load->model('titletb_model');
            $this->load->model('config_model');
            // 讀取網站名稱
            $this->title = $this->config_model->queryValue('myname');
			$this->site = $this->config_model->queryValue('myhost');

        }

	public function index()
	{

	}
	//  RoumenDamianoff 的使用範例
	public function feed($rss = 'atom')
{
    // creating rss feed with our most recent 20 posts in variable $post
	$posts = $this->titletb_model->joinFeedAnn(14);
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
        $slug = $this->site . "/index.php/Main/viewAnn/" . $post->tid;
		// set item's title, author, url, pubdate and description
        $feed->add($post->subject, $post->partname, $slug, $post->posttime, $post->comment,60);
    }

    // show your feed (options: 'atom' (recommended) or 'rss')
    $feed->render($rss);
	
}
}
