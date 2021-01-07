<?php

namespace app\admin\controller\data_in;

use app\admin\model\access\basic\Model2;
use app\common\controller\Backend;

/**
 * 建模源任务详情
 *
 * @icon fa fa-circle-o
 */
class TaskSourceDetail extends Backend
{
    protected $relationSearch = true;

    /**
     * TaskSourceDetail模型对象
     * @var \app\admin\model\data_in\TaskSourceDetail
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\data_in\TaskSourceDetail;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function select(){
        $params = $this->request->get();
        if ($this->request->isAjax()) {
            //print_r( $params );  die;
            $myWhere = [];
            if( isset($params['batch_ids']) && $params['batch_ids'] ){
                $myWhere['d.source_task_id'] = ['in',explode('|',$params['batch_ids'])];
            }
            $having = "num > 0";
//            if ($params['num_min'] > 0) {
//                $having = "num >= {$params['$num_min']}";
//            }

//            if ($params['num_max'] > 0) {
//                $having .= " and num < {$params['num_max']}";
//            }

//            $list = $db->table('sms_source_task_detail d')
//                ->join('sms_source_task t on d.source_task_id = t.task_id')
//                ->join('model l on d.url_no = l.model_no', 'left')
//                ->field('d.url_no,d.source_task_id,d.url_no,sum(d.num_total) as num,t.nickname, l.name,l.industry, l.category')->where($where)->group('d.url_no,t.nickname')->having($having)->order('t.nickname')->select();
//
            $offset  = $this->request->get("offset");
            $limit   = $this->request->get("limit");
            $taskSourceModel = new \app\admin\model\data_in\TaskSource();
            $model2= new Model2();

            $rows = $this->model->alias('d')->field('d.id,d.url_no,d.source_task_id,sum(d.num_total) as num,t.nickname, l.name,l.industry, l.category')
                    ->where($myWhere)
                    ->join([$taskSourceModel->getTable()=>'t'],'d.source_task_id=t.task_id')
                    ->join([$model2->getTable()=>'l'],'d.url_no=l.model_no','LEFT')
                    ->group('d.id,d.url_no,t.nickname')->order('t.nickname','desc')->limit($offset,$limit)->select();
            //print_r( $rows ); die;
            $total = $this->model->alias('d')->field('d.id,d.url_no,d.source_task_id,sum(d.num_total) as num,t.nickname, l.name,l.industry, l.category')
                ->join([$taskSourceModel->getTable()=>'t'],'d.source_task_id=t.task_id')
                ->join([$model2->getTable()=>'l'],'d.url_no=l.model_no','LEFT')
                ->where($myWhere)->group('d.url_no,t.nickname')->order('t.nickname','desc')->count();

            return json(['total'=>$total,"rows"=>$rows]);

        }
        $modList = (new \app\admin\model\access\ModSource())->column('nickname');
        $this->assignconfig("modList", array_combine($modList,$modList));
        $this->assignconfig('params',$params);
        return $this->view->fetch();
    }
}
