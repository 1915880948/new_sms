<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;
use think\Env;
/**
 * 外部数据出库任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskOutsideTt extends Backend
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

    // 外部数据 头条
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $myWhere = ['source_no' => 'TOUTIAO'];
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
            //print_r($params );  die;
            $params['source_no'] = 'TOUTIAO';
            $params['type'] = implode(',',$params['type']);
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $data = [] ;
            foreach ( $params['is_idfa'] as $k=>$v){
                $data[$k]['channel'] = $params['channel'];
                $data[$k]['product'] = $params['product'];
                $data[$k]['startdate'] = $params['startdate'];
                $data[$k]['enddate'] = $params['enddate'];
                $data[$k]['is_idfa'] = $v;
                $data[$k]['source_no'] = $params['source_no'];
                $data[$k]['type'] = $params['type'];
                $data[$k]['creator'] = $params['creator'];
                $data[$k]['create_time'] = $params['create_time'];

            }
            $result = $this->model->saveAll($data);
            if( $result ){
                $this->success('添加成功！！');
            }
            $this->error('添加失败！！！');
        }

        return $this->view->fetch();
    }

    public function download($ids){
        $row = $this->model->get($ids);

        if ($row['status'] != 4) {
            $this->error('当前出库未完成请稍后！！！');
        }

        $zipname = 'result-' . time() . '.zip'; //最终生成的文件名
        $filepath =  '/shuming/upload/' . $zipname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        //重新生成文件
        $zip = new \ZipArchive();
        if ($zip->open($filepath, \ZipArchive::CREATE) !== TRUE) {
            $this->error('无法打开文件，或者文件创建失败');
        }
        $types = explode(",",$row['type']);
        foreach ($types as $val) {
            if (file_exists(Env::get('file.FILE_ROOT_DIR') . '/outside_output/'.$ids. '/' . $val . '.txt')) {
                $zip->addFile(Env::get('file.FILE_ROOT_DIR') . '/outside_output/'.$ids. '/' . $val . '.txt', $val . '.txt');
            }
        }
        $zip->close();//关闭
        if (!file_exists($filepath)) {
            $this->error('无法找到文件');//即使创建，仍有可能失败
        }
        //下载zip包，下载完删除压缩数据
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: " . filesize($filepath));
        header("Content-Disposition: attachment; filename=" . $zipname);
        readfile($filepath);
        unlink($filepath);
        ob_flush();
        flush();

    }


}
