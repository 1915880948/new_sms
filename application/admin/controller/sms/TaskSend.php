<?php

namespace app\admin\controller\sms;

use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use think\Db;

/**
 * 短信发送任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskSend extends Backend
{
    
    /**
     * TaskSend模型对象
     * @var \app\admin\model\sms\TaskSend
     */
    protected $model = null;
    protected $domainList = [
                '' =>'不使用动态短链',
                "1" =>'u9t.cn',
                "2" =>'x0e.cn',
                //"3" =>> 'q9e.cn',
                "4" =>'d0e.cn(傅晓妹)',
                //"5" =>'7d0.cn(杨刚)',
                "6" =>'o8d.cn(黄福忠)',
                "7" =>'0i4.cn(左浩然|马蓉蓉)',
                "8" =>'q4f.cn(姜子文)',
                "9" =>'g0c.cn(王古锋)',
                //"10" =>'z0k.cn',
                "11" => 'q0r.cn',
                "12" => 'n0x.cn',
                "13" => 'h0e.cn',
                "14" => 'o4c.cn',
                "15" => '9oj.cn',
                //"16" =>'5oj.cn',
                //"17" =>'vo4.cn',
                "18" =>'4a6.cn',
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

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
//            if( $this->request->get('channel_form') ){
//                $myWhere['channel_from'] = $this->request->get('channel_form');
//            }else{
               // $myWhere['channel_from'] = 2;
//            }

            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)//->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)//->where($myWhere)
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
        $short_link_id = $this->request->get('ids');
        $link_from = $this->request->get('link_from');
        $spModel = new Sp();
        $linkShortModel = new \app\admin\model\sms\LinkShort();
        $linkShort = $linkShortModel->get($short_link_id);
        $spList = $spModel->field('id,sp_no,sp_name,remote_account,price')->select();

        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if( strlen($params['sms_content']) > 70 ){
                $this->error('短信内容最多70个字符！');
            }
            $linkModel = new \app\admin\model\sms\Link();
            $linkShort = $linkShortModel->get($params['sm_task_id']);
            if( !$linkShort ){
                $this->error('短链ID参数错误！！！');
            }
            $link = $linkModel->get($linkShort['link_id']);
            $params['company'] = $link['company_name'];
            $params['bank'] = $link['bank_name'];
            $params['business'] = $link['business_name'];
            if( $params['link_from'] == 1){ // 0:未知 1:内部 2:外部
                if( !$params['file_path'] ){
                    $this->error('发送文件必须上传！');
                }

            }
            $params['creator'] = $this->auth->getUserInfo()['username'];
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$params['sms_gate_id'])->value('p.PRICEX');
            $params['price'] = $price;
            $params['status'] = 1;
            $result = $this->model->save($params);
            $linkShortModel->save(['task_send_num'=>$linkShort['task_send_num']+1],['id'=>$linkShort['id']]);
            if( !$result ){
                $this->success('任务创建失败！！');
            }
            $this->success('任务创建成功！！');
        }
        $this->assign('link_from',$link_from);
        $this->assign('domainList',$this->domainList);
        $this->assign('spList',$spList);
        $this->assignconfig('spList',$spList);
        $this->assign('linkShort',$linkShort);
        return $this->view->fetch();
    }
}
