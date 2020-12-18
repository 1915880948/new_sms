<?php

namespace app\admin\controller\access\basic;

use app\common\controller\Backend;

/**
 * 银行地域管理
 *
 * @icon fa fa-circle-o
 */
class InfoBankArea extends Backend
{
    
    /**
     * InfoBankArea模型对象
     * @var \app\admin\model\access\basic\InfoBankArea
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\basic\InfoBankArea;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    public function tree(){
        $tree = file_get_contents('tree_data1.php');
        $tree = json_decode($tree,true);

//        $nodeList[] = array('id' => $v['id'], 'parent' => $v['pid'] ? $v['pid'] : '#', 'text' => __($v['title']), 'type' => 'menu', 'state' => $state);
        $this->success('', null, $tree);
    }

    public function area_ajax(){
        $bank_id = $this->request->get('bank_id');
        $area = $this->model->where(['bank_id'=>$bank_id])->column('city_name');
        $this->success('',null,$area);
    }
}
