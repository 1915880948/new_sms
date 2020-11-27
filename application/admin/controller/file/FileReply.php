<?php

namespace app\admin\controller\file;

use app\common\controller\Backend;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class FileReply extends Backend
{
    
    /**
     * FileReply模型对象
     * @var \app\admin\model\file\FileReply
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\file\FileReply;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        $creator = $this->auth->getUserInfo()['username'];
        $strMessage = "";
        $messageList = $this->model->where("creator = '" . $creator . "' and num = 0 ")->select();
        if($messageList){
            foreach ($messageList as $value) {
                $strMessage .= "任务id为：".$value['id'].",文件名称为：".$value['file_name'].'<br>';
            }
        }else{
            $strMessage = '没有需要下载的文件。<br>';
        }


        $this->view->assign("strMessage", $strMessage);
        return $this->view->fetch();
    }
    public function add()
    {
        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if( !$params['file_path'] ){
                $this->error('文件必须上传！');
            }else{
                $attachment = model("attachment");
                $file_name = $attachment->where("url='{$params['file_path']}'")->value("extparam");
                $file_name = json_decode($file_name,true);
                $params['file_name'] = $file_name['name'];
            }
            $count = $this->model->where("file_path = '".$params['file_path']."'")->count();
            if ($count){
                $this->error('文件已存在！');
            }
            $params['creator'] = $params['useror'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date("Y-m-d H:i:s");
            //根据所选通道确认价格
            $result = $this->model->save($params);
            if( !$result ){
                $this->success('任务创建失败！！');
            }
            $this->success('任务创建成功！！');
        }
        return $this->view->fetch();
    }

    public function download($ids){
        $fileTask = $this->model->get($ids);
        if( !$fileTask ){
            $this->error('需要下载的任务不存在');
        }

        $result = $this->model->where('id',$fileTask['id'])
            ->inc('num',1)
            ->update();
        $detailModel = new \app\admin\model\file\FileReplyDetail();
        $params['useror'] = $this->auth->getUserInfo()['username'];
        $params['create_time'] = date("Y-m-d H:i:s");
        $params['task_id'] = $ids;
        $detailModel->save($params);
        set_time_limit(0);
        $filepath = $fileTask['file_path'];
        $txtfile = urlencode($fileTask['file_name']);
        //告诉浏览器这是一个文件流格式的文件
        //Header ( "Content-type: application/octet-stream" );
        Header ( "Content-type: application/octet-stream" );
        //请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header ( "Accept-Length: " . filesize ( $filepath ) );
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header ( "Content-Disposition: attachment; filename=" .$txtfile );
        //读取文件内容并直接输出到浏览器
        /*$file = fopen ( $filepath.$filename, "rb" );
        echo fread ( $file, filesize ( $filepath.$filename ) );
        fclose ( $file );
        exit ();*/
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

    /**
     * 详情
     */
    public function detail()
    {
        $task_id = $this->request->get('task_id');
        $offset  = $this->request->get("offset");
        $limit   = $this->request->get("limit");
        if( $task_id ){
            $detailModel = new \app\admin\model\file\FileReplyDetail();
            $rows = $detailModel->where('task_id', $task_id)->order('id', 'desc')->limit($offset, $limit)->select();
            $total = $detailModel->where('task_id', $task_id)->order('id', 'desc')->count();

            return json(['total' => $total, "rows" => $rows]);
        }
        return $this->view->fetch();
    }
}
