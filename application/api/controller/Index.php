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
        $params = $this->request->get();
        $params['post'] = $this->request->post();
        $params['api-file-download'] ='这是/api/index/download';
        Log::log($params);
    }
}
