<?php

namespace app\admin\controller\data_in;

use app\admin\model\basic2\DpiCostPlot;
use app\common\controller\Backend;
use think\Env;

/**
 * 建模源手机号入库任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskSource extends Backend
{
    
    /**
     * TaskSource模型对象
     * @var \app\admin\model\data_in\TaskSource
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\data_in\TaskSource;

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
        $ModSource = new \app\admin\model\access\ModSource();
        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if( !$params['nickname'] ){
                $this->error('请选择建模源ID！');
            }
            if( !$params['file_path']){
                $this->error('文件必须上传！');
            }
            $handle = fopen($params['file_path'],"r");//以只读方式打开一个文件
            $k = 0;
            while(!feof($handle)){
                if(fgets($handle)){
                    $k++;
                };
            }
            fclose($handle);
            $params['source_no'] = $ModSource->where(['nickname' => $params['nickname']])->value('modid');
            $params['cost_num'] = $k;
            $params['status'] = 1;
            $params['create_time'] = date('YmdHis');
            $result = $this->model->save($params);
            if( !$result ){
                $this->success('失败！！');
            }
            $this->success('成功！！');
        }
        $modList = $ModSource->column('nickname');
        $this->assign("modList", array_combine($modList,$modList));
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

    public function detail($ids=null){
        $row = $this->model->get($ids);

        if (empty($row) || $row['status'] == 4) {
            $this->error('您查看的建模源任务不存在或已被删除。');
        }

        if( $this->request->isAjax() ){
            $myWhere['source_task_id'] = $ids;
            $detailModel = new \app\admin\model\data_in\TaskSourceDetail();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $detailModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $detailModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);

        }

        $this->assignconfig('row',$row);
        return $this->view->fetch();
    }

    public function spread($ids=null){
        $row = $this->model->get($ids);

        if (empty($row) || $row['status'] == 4) {
            $this->error('您查看的建模源任务不存在或已被删除。');
        }
        if( $this->request->isAjax() ){
            $myWhere = [];
            $DpiCostPlotModel = new DpiCostPlot();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $DpiCostPlotModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $DpiCostPlotModel
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $totalTitle = $DpiCostPlotModel->field('SUM(cost_num) as cost_num, SUM(number) as number, FORMAT(SUM(cost),2) as cost, SUM(yh) as yh, FORMAT(SUM(yhcost),2) as yhcost, SUM(bx) as bx, FORMAT(SUM(bxcost),2) as bxcost,
             SUM(jr) as jr,FORMAT(SUM(jrcost),2) as jrcost,SUM(sfc) as sfc,FORMAT(SUM(sfccost),2) as sfccost,SUM(yx) as yx,FORMAT(SUM(yxcost),2) as yxcost,SUM(qt) as qt,FORMAT(SUM(qtcost),2) as qtcost')
                ->where($where)->select();
            $totalTitle[0]['days'] = $totalTitle[0]['nickname'] = $totalTitle[0]['task_id'] = '总计';
            collection($totalTitle)->toArray() ;
            array_unshift($list,$totalTitle[0]);
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }

        $modList = (new \app\admin\model\access\ModSource())->column('nickname');
        $this->assignconfig("modList", array_combine($modList,$modList));
        $this->assignconfig('row',$row);
        return $this->view->fetch();
    }
}
