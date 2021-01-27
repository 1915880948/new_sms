<?php

namespace app\admin\controller\modeling;

use app\common\controller\Backend;
use think\Env;

/**
 * U2提交取数和粗筛管理
 *
 * @icon fa fa-circle-o
 */
class U2SubmitTask extends Backend
{
    
    /**
     * U2SubmitTask模型对象
     * @var \app\admin\model\modeling\U2SubmitTask
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\modeling\U2SubmitTask;

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
            $myWhere = [];
            if( isset($params['source_no']) ){
                $myWhere['source_no'] = $params['source_no'];
            }
            if( isset($params['type']) ){
                $myWhere['type'] = $params['type'];
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


    public function download($ids=null){
        $row = $this->model->where(['id'=>['in',$ids]])->find();
        if( !$row ){
            $this->error('参数错误：ids');
        }
        $filepath =  Env::get('file.FILE_ROOT_DIR') . $row['file_path'];
        $filename = $row['file_name'];
        $excelfile = urlencode($row['file_name']);
        //告诉浏览器这是一个文件流格式的文件
        //Header ( "Content-type: application/octet-stream" );
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filepath));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $excelfile);
        $fp = fopen($filepath, 'rb');
        // 设置指针位置
        fseek($fp, 0);

        // 开启缓冲区
        ob_start();
        // 分段读取文件
        while (!feof($fp)) {
            $chunk_size = 1024 * 1024 * 50; // 50M
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
