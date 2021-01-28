<?php

namespace app\admin\controller\basic;

use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use think\Db;
use think\db\Query;
use think\Env;

/**
 * 短信发送任务管理
 *
 * @icon fa fa-circle-o
 */
class SendQueue extends Backend
{
    
    /**
     * SendQueue模型对象
     * @var \app\admin\model\sms\TaskSend
     */
    protected $model = null;
    protected $statusArr = [
        1 => '待生成短链',
        2 => '生成动态短链中',
        3 => '等待发送',
        4 => '发送中',
        5 => '发送完毕',
        6 => '已停止',
        7 => '已删除',
        8 => '无需发送',
        9 => '暂存',
        10 => '短链生成完毕',
        11 => '创建超信任务失败',
        12 => '创建超信任务成功',
        13 => '超信任务添加手机号中',
        14 => '超信任务添加手机号成功',
        15 => '超信任务添加手机号失败',
        16 => '超信任务提交失败',
        17 => '入队列完毕',
        18 => '写入发送队列中',
        19 => '通道连接异常',
    ];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\TaskSend;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    // 常规短信
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['status'] = 3;
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

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            if (!in_array($row['status'], [1, 2, 3, 6, 7, 9])) {
                $this->error('任务' . $this->statusArr[$row['status']] . '，不能修改。');
            }
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
            }
            $result = $row->allowField(true)->save($params);
            if( $result ){
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
