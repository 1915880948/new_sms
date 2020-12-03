<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use Predis\Client;
use think\Env;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        $redis = new \Redis();
        $redis->connect('39.104.20.84', '6379');
//$auth = $redis->auth('');

//        $redis = new Client([
//            'host' => Env::get('redis3.host'),
//            'port' => Env::get('redis3.port'),
//            'password' => Env::get('redis3.password')
//        ]);
        print_r("Server is running: " . $redis->ping());
        print_r("Server is running: " . $redis->get('test-name'));
        phpinfo();
        exit;
        //$this->redirect('/admin.php/index/login');
        return $this->view->fetch();
    }

}
