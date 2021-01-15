<?php

namespace app\admin\controller\basic;

use app\common\controller\Backend;

/**
 * 通道小号埋点
 *
 * @icon fa fa-circle-o
 */
class PhoneSmallInfo2 extends Backend
{
    
    /**
     * PhoneSmallInfo2模型对象
     * @var \app\admin\model\basic\PhoneSmallInfo2
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\basic\PhoneSmallInfo2;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    public function add()
    {
        $spModel = new \app\admin\model\basic\Sp();
        $spList = $spModel->column('id,sp_name');

        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            $data = $spModel->get($params['sms_sp_info_id']);
            $params['sp_no'] = $data['sp_no'];
            $params['sp_name'] = $data['sp_name'];
            $result = $this->model->save($params);
            if( $result ){
                $this->success();
            }
            $this->error();
        }
        $this->assign('spList',$spList);
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $spModel = new \app\admin\model\basic\Sp();
        $row = $this->model->get($ids);
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            $data = $spModel->get($params['sms_sp_info_id']);
            $params['sp_no'] = $data['sp_no'];
            $params['sp_name'] = $data['sp_name'];
            $result = $row->save($params);
            if( $result ){
                $this->success();
            }
            $this->error();
        }
        $spList = $spModel->column('id,sp_name');
        $this->assign('spList',$spList);
        $this->assign('row',$row);
        return $this->view->fetch();

    }

}
