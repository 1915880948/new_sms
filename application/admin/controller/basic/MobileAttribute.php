<?php

namespace app\admin\controller\basic;

use app\common\controller\Backend;

/**
 * 号段管理
 *
 * @icon fa fa-circle-o
 */
class MobileAttribute extends Backend
{
    
    /**
     * MobileAttribute模型对象
     * @var \app\admin\model\basic\MobileAttribute
     */
    protected $model = null;
    private $vendorArr = [
        0 => '中国电信',
        1 => '中国移动',
        2 => '中国联通',
        3 => '通用',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\basic\MobileAttribute;
        $this->assign('vendorArr',$this->vendorArr);
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

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


        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['update_time'] = date("Y-m-d H:i:s");
                $result = $row->allowField(true)->save($params);
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $this->view->assign('vendorArr',$this->vendorArr);
        return $this->view->fetch();
    }



    public function searchList(){
        $model = new \app\admin\model\basic\Province();
        $provenceList = $model->field('province_id,province_name')->select();
    }



}
