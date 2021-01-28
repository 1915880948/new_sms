<?php

namespace app\admin\controller\plot;

use app\common\controller\Backend;
use think\Db;

/**
 * 通道回Y统计管理
 *
 * @icon fa fa-circle-o
 */
class Datapage extends Backend
{
    
    /**
     * Passplot模型对象
     * @var \app\admin\model\plot\Passplot
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        //$this->model = new \app\admin\model\plot\Passplot;

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
            $params = $this->request->get();
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $db = new Db();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $db::table('sms_send_data.sms_landpage_total')
                ->field("DATE_FORMAT(create_time,'%Y-%m-%d') day")
                ->where($where)
                ->group('url_link,day')
                ->order($sort, $order)
                ->count();

            $list = $db::table('sms_send_data.sms_landpage_total')
                ->field("url_link,DATE_FORMAT(create_time,'%Y-%m-%d') day,sum(second) as second, sum(is_show) as is_show,sum(is_top) as is_top, sum(is_draw) as is_draw,sum(is_retrie) as is_retrie,sum(is_start) as is_start,sum(is_jump) as is_jump,sum(is_jump_click) as is_jump_click,sum(is_prize) as is_prize,sum(is_jump_close) as is_jump_close,sum(other_click) as other_click")
                ->where($where)
                ->group('url_link,day')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            foreach ($list as $k => &$v)
            {
                $v['create_time'] = $v['day'];
            }
            unset($v);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
