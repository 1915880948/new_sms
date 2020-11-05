<?php

namespace app\admin\controller\basic;

use app\common\controller\Backend;

/**
 * sheet管理
 *
 * @icon fa fa-circle-o
 */
class Sheet extends Backend
{
    
    /**
     * Sheet模型对象
     * @var \app\admin\model\basic\Sheet
     */
    protected $model = null;
    private $vendorArr = [
        0 => '中国电信',
        1 => '中国移动',
        2 => '中国联通',
        3 => '通用',
    ];

    private $statusArr = [
        1=>'CRM平台通道',
        2=>'外部平台通道',
        3=>'小沃HTTP通道',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\basic\Sheet;
        $this->assign('vendorArr',$this->vendorArr);
        $this->assign('statusArr',$this->statusArr);

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
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
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

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['create_time'] = date("Y-m-d H:i:s");
                $result = $this->model->allowField(true)->save($params);
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('vendorArr',$this->vendorArr);
        $this->view->assign('statusArr',$this->statusArr);
        return $this->view->fetch();
    }

}
