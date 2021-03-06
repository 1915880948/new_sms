<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/10/26
 * Time: 11:21
 */

namespace app\admin\controller\sms;


use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use app\common\model\Config;
use think\Db;
use think\Env;
use think\Log;

class Timely extends Backend
{
    protected $typeArr = [
        1=>'展示',
        2=>'点击',
        3=>'视频播放',
        4=>'视频播放完',
        5=>'视频有效播放',
    ];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\TaskSend;
    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $myWhere['channel_from'] = 2;
            if (!$this->auth->isSuperAdmin()) {
                $myWhere['creator'] = ['in',$this->auth->getChildrenAdminUsername() ];
            }
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

    public function add(){
        $model = new Config();
        $data = [
            'type' => '1',
            'value' => '自动发送',
            'content' => '自动发送',
            'channel_id'=>'',
            'sp_info_id'=>'',
            'domain_short'=>'',
            'send_start_time'=>'',
            'send_end_time'=>'',
            'sms_content'=>'',
        ];
        $result = $model->allowField(true)->save($data);
        return json(['data'=>['msg'=>'添加配置成功，请修改配置以生效！！'],'code'=>1]);
    }
    public function list(){
        $model = new Config();
        $offset  = $this->request->get("offset");
        $limit   = $this->request->get("limit");
        if ($this->request->isAjax()) {
            $myWhere['id'] = ['>',17];
            $myWhere['type'] = ['=',1];

            $list = $model->where($myWhere)->order('id','desc')->limit($offset,$limit)->select();
            $total = $model->where($myWhere)->order('id','desc')->count();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function config($ids){
        $spModel = new Sp();
        $model = new Config();

        $spList = $spModel->field('id,sp_no,sp_name,remote_account')->select();
        $row = $model->get($ids);
        $domainList = \think\Config::get('domainList');
        if(  $this->request->isPost() ){

            $postData = $this->request->post('row/a');
            //print_r($postData ); die;
            $link = (new \app\admin\model\sms\Link())->where('channel_id',trim($postData['channel_id']))->find();
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$postData['sp_info_id'])->value('p.PRICEX');
            if( !$link ){
                $this->error('查不到此渠道号！！');
            }
            Db::startTrans();
            try{
                $result = $row->save([
                    'name' => trim($postData['name']), // 作为下标：channel_product
                    'title' => trim($postData['title']),
                    'channel_id' => trim($postData['channel_id']),
                    'bank_id' => $link['bank_id'],
                    'sp_info_id' => $postData['sp_info_id'],
                    'domain_short' => $postData['domain_short'],
                    'send_start_time' => $postData['send_start_time'],
                    'send_end_time' => $postData['send_end_time'],
                    'sms_content' => trim($postData['sms_content']),
                    'send_status' => $postData['send_status'],
                    'timely_type' => implode(',',$postData['timely_type']),
                    'timely_encrypt_type' => $postData['timely_encrypt_type'],
                    'city' => $postData['city'],
                    'group' => $this->auth->getUserInfo()['username'], // 用于每天自动生成任务时，确定该配置文件的所有者
                ]);
                if( !$result ){
                    throw new \Exception('参数更新失败');
                }

                if( $postData['send_status'] == 1 ){// 如果是设置开启，生成短链
                    $linkShortModel =new \app\admin\model\sms\LinkShort();
                    $linkShortLastID = $linkShortModel->max('id') + 1;
                    $transfer_link =  'http://'.Env::get('sms_click.host').'/link.php?id='.$linkShortLastID;

                    $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($postData['domain_short']) . '&url=' . rawurlencode($transfer_link);
                    $shortLinkResult = httpRequest($apiUrl, 'GET');
                    //Log:Log::log($shortLinkResult);
                    $shortLinkResult = json_decode($shortLinkResult, true);
                    if (empty($shortLinkResult['data'][0])) {
                        throw new \Exception('短链生成失败，请稍后重试..');
                    }
                    $result = $linkShortModel->save([
                        'remark'        => 'ZD-'.date('Ymd').'-'.trim($postData['title']).'-'.date("Hi",strtotime($postData['send_start_time'])).'_'.date("Hi",strtotime($postData['send_end_time'])),
                        'link_id'       => $link['id'],
                        'business_link' => $link['link'],
                        'transfer_link' => $transfer_link,
                        'short_link'    => $shortLinkResult['data'][0]['short_url'],
                        'creator'       => $this->auth->getUserInfo()['username'],
                        'create_time'   => date('Y-m-d H:i:s'),

                    ]);
                    if( !$result ){
                        throw new \Exception('短链生成失败！');
                    }
                    // 增加一条短信发送任务
                    $taskSendModel = new \app\admin\model\sms\TaskSend();
                    $result = $taskSendModel->save([
                        'title' => 'ZD-'.date('Ymd').'-'.trim($postData['title']).'-'.date("Hi",strtotime($postData['send_start_time'])).'_'.date("Hi",strtotime($postData['send_end_time'])),
                        'company' => $link['company_name'],
                        'company_id' => $link['company_id'],
                        'bank' => $link['bank_name'],
                        'bank_id' => $link['bank_id'],
                        'business' => $link['business_name'],
                        'business_id' => $link['business_id'],
                        'channel_id' => $link['channel_id'],
                        'data_id' => 0,
                        'send_time' => date('Y-m-d,H:i:s'),
                        'sms_gate_id' =>  $postData['sp_info_id'],
                        'sms_template_id' => 0,
                        'sms_content' => $postData['sms_content'],
                        'shortlink' => $shortLinkResult['data'][0]['short_url'],
                        'channel_from' => 2, // 0，1动态短信，2实时短信
                        'link_from' => 1,
                        'create_time' => date('Y-m-d H:i:s'),
                        'creator' => $this->auth->getUserInfo()['username'],
                        'status' => 5, //4发送中，5发送完成
                        'sm_task_id' => $linkShortModel->id,
                        'file_path' => '',
                        'price' => $price,
                        'finish_time' => date('Y-m-d,H:i:s'),
                        'remark' => trim($postData['name']),
                    ]);
                    if( !$result ){
                        throw new \Exception('短信发送任务创建失败！');
                    }
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                $this->error('提交失败，稍后再试:'.$e->getMessage());
            }
            $this->success('提交成功！');

        }


        $this->view->assign('row', $row);
        $this->view->assign('typeArr', $this->typeArr);
        $this->view->assign('spList', $spList);
        $this->view->assign('domainList',$domainList);

        return  $this->view->fetch();
    }

}