<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;
use think\Env;

/**
 * 外部撞库任务管理
 *
 * @icon fa fa-circle-o
 */
class Hit extends Backend
{
    
    /**
     * Hit模型对象
     * @var \app\admin\model\access\Hit
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\Hit;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    //下载
    public function download($ids){

        $where = 'task_id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if (empty($fetchList)) {
            $this->error('请选择需要下载的数据。','',15);
        } else {
            $filename = Env::get('file.FILE_ROOT_DIR') . '/taikang/' . $ids . '/'.$ids.'.txt';
            $txtfile = urlencode($fetchList['down_file_path']);
            //告诉浏览器这是一个文件流格式的文件
            //Header ( "Content-type: application/octet-stream" );
            Header ( "Content-type: application/octet-stream" );
            //请求范围的度量单位
            Header ( "Accept-Ranges: bytes" );
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header ( "Accept-Length: " . filesize ( $filename ) );
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header ( "Content-Disposition: attachment; filename=" .$txtfile );
            //读取文件内容并直接输出到浏览器
            $file = fopen ( $filename, "rb" );
            echo fread ( $file, filesize ( $filename ) );
            fclose ( $file );
            exit ();
        }
    }

    //黑名单下载
    public function black_download($ids){

        $where = 'task_id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if (empty($fetchList)) {
            $this->error('请选择需要下载的数据。','',15);
        } else {
            $filename = Env::get('file.FILE_ROOT_DIR') . '/taikang/black/' . $ids . '/'.$ids.'.txt';
            $txtfile = urlencode('black_'.$fetchList['down_file_path']);
            //告诉浏览器这是一个文件流格式的文件
            //Header ( "Content-type: application/octet-stream" );
            Header ( "Content-type: application/octet-stream" );
            //请求范围的度量单位
            Header ( "Accept-Ranges: bytes" );
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header ( "Accept-Length: " . filesize ( $filename ) );
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header ( "Content-Disposition: attachment; filename=" .$txtfile );
            //读取文件内容并直接输出到浏览器
            $file = fopen ( $filename, "rb" );
            echo fread ( $file, filesize ( $filename ) );
            fclose ( $file );
            exit ();
        }
    }
}
