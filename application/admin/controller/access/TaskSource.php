<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;

/**
 * 建模源手机号入库任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskSource extends Backend
{
    
    /**
     * TaskSource模型对象
     * @var \app\admin\model\access\TaskSource
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\TaskSource;

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

    public function select(){
        $params = $this->request->get();

        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere = [];
//            if( isset($params['mod']) ){
//                $myWhere['nickname'] = $params['mod'];
//            }
//            if( isset($params['start_time']) ){
//                $myWhere['date'] = ['>=',$params['start_time']];
//            }
//            if( isset($params['end_time']) ){
//                $myWhere['date'] = ['<=',$params['end_time']];
//            }
//            if( isset($params['start_time']) && isset($params['end_time']) ){
//                $myWhere['date'] = ['between',[$params['start_time'],$params['end_time']]];
//            }
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
        $modList = (new \app\admin\model\access\ModSource())->column('nickname');

        $this->assignconfig("modList", array_combine($modList,$modList));
        $this->assignconfig('params',$params);
        return $this->view->fetch();

    }

}
