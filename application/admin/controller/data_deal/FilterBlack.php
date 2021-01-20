<?php

namespace app\admin\controller\data_deal;

use app\common\controller\Backend;
use think\Env;

/**
 * 过滤管理
 *
 * @icon fa fa-circle-o
 */
class FilterBlack extends Backend
{
    
    /**
     * FilterBlack模型对象
     * @var \app\admin\model\data_deal\FilterBlack
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\data_deal\FilterBlack;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['status'] = ['<>',5];
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            if( !(isset($params['is_filter']) && $params['is_filter']==1)){
                $myWhere['output_pid_task_id'] = 0;
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    //下载
    public function download($ids){

        $where = 'id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if ($fetchList['status'] != 3) {
            $this->error('当前任务未完成请稍后。');
        } else {
            $filepath = Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/';
            $filename = $ids.'.txt';
            $outfile = explode('.',$fetchList['source_name']);
            $txtfile = urlencode($outfile[0]."-".$fetchList['total_num']."条.txt");
            //告诉浏览器这是一个文件流格式的文件
            //Header ( "Content-type: application/octet-stream" );
            Header ( "Content-type: application/octet-stream" );
            //请求范围的度量单位
            Header ( "Accept-Ranges: bytes" );
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header ( "Accept-Length: " . filesize ( $filepath.$filename ) );
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header ( "Content-Disposition: attachment; filename=" .$txtfile );
            //读取文件内容并直接输出到浏览器
            $fp = fopen($filepath.$filename, 'rb');
            // 设置指针位置
            fseek($fp, 0);

            // 开启缓冲区
            ob_start();
            // 分段读取文件
            while (!feof($fp)) {
                $chunk_size = 1024 * 1024 * 25; // 50M
                echo fread($fp, $chunk_size);
                ob_flush(); // 刷新PHP缓冲区到Web服务器
                flush(); // 刷新Web服务器缓冲区到浏览器
                sleep(1); // 每1秒 下载 50M
            }
            // 关闭缓冲区
            ob_end_clean();

            fclose($fp);
            exit();
        }
    }
}
