<?php

namespace app\admin\controller\basic;

use app\common\controller\Backend;
use Think\Db;

/**
 * 业务管理
 *
 * @icon fa fa-circle-o
 */
class Passway extends Backend
{
    
    /**
     * Business模型对象
     * @var \app\admin\model\basic\Business
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

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
            /*if (!$this->auth->isSuperAdmin()) {
                $myWhere['creator'] = ['in',$this->auth->getChildrenAdminUsername() ];
            }*/
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $db = new Db();
            $total = $db::table("sms_send_data.sp_chanel")
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $db::table("sms_send_data.sp_chanel")
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
        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if ($params) {
                $db = new Db();
                $spList = $db::table("sms_send_data.sp_chanel")->where("sp_no='$params[sp_no]'")->find();
                if ($spList){
                    $this->error ( '通道号不能重复。');
                }
                $result = $db::table("sms_send_data.sp_chanel")->insert($params);
                if( !$result ){
                    $this->error('任务创建失败！！');
                }
                $this->success('任务创建成功！！');
            }
            $this->error();
        }
        return $this->view->fetch();
    }
    public function edit($ids = null)
    {
        $db = new Db();
        $row = $db::table("sms_send_data.sp_chanel")->where("id=$ids")->find();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            $result = $db::table("sms_send_data.sp_chanel")->where("id=$ids")->update($params);
            if ($result !== false) {
                $this->success();
            } else {
                $this->error(__('No rows were inserted'));
            }
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function del($ids = "")
    {
        if ($ids) {
            $delIds = [];
            $delIds = explode(',',$ids);
            /*foreach (explode(',', $ids) as $k => $v) {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, true));
            }
            $delIds = array_unique($delIds);*/
            $db = new Db();
            $count = $db::table("sms_send_data.sp_chanel")->where('id', 'in', $delIds)->delete();
            if ($count) {
                $this->success();
            }
        }
        $this->error("删除失败");

    }
}
