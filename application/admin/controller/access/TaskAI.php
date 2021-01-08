<?php

namespace app\admin\controller\access;

use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use app\common\model\Attachment;
use think\Env;

/**
 * AI取数任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskAI extends Backend
{
    
    /**
     * TaskAI模型对象
     * @var \app\admin\model\access\TaskAI
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\TaskAI;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function add()
    {
        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            //$result = $this->model->save($params);
            if( !$result ){
                $this->success('任务创建失败！！');
            }
            $this->success('任务创建成功！！');
        }
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if (!$params['auc']) {
                $this->error('请输入临界值！！');
            }
            $params['status'] = 6;
            unset($params['content']);
            $result = $row->allowField(true)->save($params);
            if( $result ){
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $row['content'] = '12112211';//file_get_contents($row['auc_path']);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    public function detail($ids=null){

        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['task_id'] = $ids;
            $taskAIDetailModel = new \app\admin\model\access\TaskAIDetail();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $taskAIDetailModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $taskAIDetailModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('ids',$ids);
        return $this->view->fetch();
    }

    public function download($ids){
        $row = $this->model->get($ids);
        if ($row['status'] < 5) {
            $this->error('当前出库未完成请稍后。');
        }
        $filename = $row['ai_path'];
        $outfile = basename($filename);
        $outfile = explode(',',$outfile);
        $txtfile = urlencode($outfile[0]."-".$row['source_data_num']."条.txt");
        //告诉浏览器这是一个文件流格式的文件
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filename));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $txtfile);
        $fp = fopen($filename, 'rb');
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
