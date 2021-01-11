<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;
use think\Env;

/**
 * 云海出数任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskYunhai extends Backend
{
    
    /**
     * TaskYunhai模型对象
     * @var \app\admin\model\access\TaskYunhai
     */
    protected $model = null;
    private $statusArr = [
        1 => '待处理',
        2 => '处理中',
        3 => '处理中',
        4 => '转虚拟号中',
        5 => '处理完毕',
        6 => '已传输',
        7 => '已删除',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\TaskYunhai;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index(){
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $myWhere = ['y.status' => ['<>',7]];
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $taskSourceModel = new \app\admin\model\data_in\TaskSource();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->alias('y')->field('y.*,s.date')
                ->join([$taskSourceModel->getTable()=>'s'],'y.task_id = s.task_id','left')
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $this->model->alias('y')->field('y.*,s.date')
                ->join([$taskSourceModel->getTable()=>'s'],'y.task_id = s.task_id','left')
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->assignconfig('statusArr',$this->statusArr);
        return $this->view->fetch();

    }

    public function transfer($ids){
        $row = $this->model->get($ids);

        $args = [
            'fileName'=>$row['file_path'],
            'sourceNo'=>$row['source_no']
        ];
        $result = httpRequest(Env::get('transfer.url'),'post',json_encode($args),array('Content-type:application/json'));
        if($result == "success"){
            $data['status'] = 6;
            $data['transfer_time'] = date('Y-m-d H:i:s');
            $res = $this->model->save($data,['id'=>$ids]);
            return json(['status' => 1, 'info' => '传输成功', 'data' => '']);
        }
        $this->error('传输失败');
    }

    public function download($ids){
        $row = $this->model->get($ids);

        if ($row['status'] != 3 && $row['status'] != 4) {
            $this->error('当前出数未完成请稍后。');
        }
        $gzpath = explode("/",$row['file_path']);
        $gzfile = $gzpath[count($gzpath)-1];
        //告诉浏览器这是一个文件流格式的文件
        //Header ( "Content-type: application/octet-stream" );
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($row['file_path']));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $gzfile);
        //读取文件内容并直接输出到浏览器
        $fp = fopen($row['file_path'], 'rb');
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
