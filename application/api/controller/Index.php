<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Log;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }

    public function download(){
        $server = $_SERVER['REQUEST_URI'];

        $params = $this->request->get();
        $params['server'] = $this->convertUrlArray($server);
        $params['post'] = trim(strrchr($server, '?'),'?');
        //$params['api-file-download'] ='这是/api/index/download';
        print_r($params);
        //Log::log($params);
    }

    function convertUrlArray($query){
        $query =  trim(strrchr($query, '?'),'?');
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
}
