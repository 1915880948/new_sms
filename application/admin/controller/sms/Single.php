<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/11/16
 * Time: 10:21
 */

namespace app\admin\controller\sms;


use app\admin\model\basic\Sp;
use app\common\controller\Backend;

class Single extends Backend
{
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\TaskSend;

    }

    // 单点短信
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['channel_from'] = 3;
            if (!$this->auth->isSuperAdmin()) {
                $myWhere['creator'] = $this->auth->getUserInfo()['username'];
            }
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            if( isset($params['is_filter']) && $params['is_filter']==1 ){
                $myWhere['total_receive'] = ['>',0];
                $myWhere['total_click'] = ['>',0];
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
            $spInfos = (new Sp())->getSpInfo('id, sp_no, sp_name, vendor_id, price', 0, '1');
            foreach ($list as $k => &$v)
            {
                if (isset($spInfos[$v['sms_gate_id']])) {
                    $v['sp_name'] = $spInfos[$v['sms_gate_id']]['sp_name'];
                }else{
                    $v['sp_name'] = '';
                }
            }
            unset($v);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


}