<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        $redis = new \Redis;
        $redis->connect('39.104.20.84', '6379');
//$auth = $redis->auth('');
        print_r("Server is running: " . $redis->ping());
        phpinfo();
        exit;
        //$this->redirect('/admin.php/index/login');
        return $this->view->fetch();
    }

}
