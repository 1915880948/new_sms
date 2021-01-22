<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;
use think\Env;

/**
 * 外部数据出库任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskOutside extends Backend
{
    
    /**
     * TaskOutside模型对象
     * @var \app\admin\model\access\TaskOutside
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\TaskOutside;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    // 外部数据 快手
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $myWhere = ['source_no' => 'KUAISHOU'];
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
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

    public function add(){
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            $params['source_no'] = 'KUAISHOU';
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $result = $this->model->save($params);
            if( $result ){
                $this->success('添加成功！！');
            }
            $this->error('添加失败！！！');
        }
        $row = $this->model->where(['source_no'=>'KUAISHOU'])->order('task_id','desc')->find();

        $this->assign('row',$row);
        return $this->view->fetch();
    }

    public function download($ids){
        $row = $this->model->get($ids);

        if ($row['status'] != 4) {
            $this->error('当前出库未完成请稍后！！！');
        }

        $filepath = Env::get('file.FILE_ROOT_DIR') . '/outside_output/';
        $filename = $ids . '.txt';
        $txtfile = urlencode($row['source_no'] . "-" . $row['total_number'] . "-" . $row['number'] . ".txt");
        //告诉浏览器这是一个文件流格式的文件
        //Header ( "Content-type: application/octet-stream" );
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filepath . $filename));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $txtfile);
        //读取文件内容并直接输出到浏览器
        /*$file = fopen($filepath . $filename, "rb");
        echo fread($file, filesize($filepath . $filename));
        fclose($file);
        exit ();*/
        $fp = fopen($filepath . $filename, 'rb');
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
