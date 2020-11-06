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

class Timely extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
//        $this->model = model('AdminLog');
//        $ipList = $this->model->whereTime('createtime', '-37 days')->group("ip")->column("ip,ip as aa");
//        $this->view->assign("ipList", $ipList);
    }

    public function index(){
        $spModel = new Sp();
        $model = new Config();

        $spList = $spModel->field('id,sp_no,sp_name,remote_account')->select();
        //print_r( $spList ); die;
        $row = $model->get(18);
        $domainList = [
//            "z0k"=>'z0k.cn',
//            "9oj"=>'9oj.cn',
//            "5oj"=>'5oj.cn',
//            "vo4"=>'vo4.cn',
//            "j0q"=>'j0q.cn',
            "x0e" => 'x0e.cn',
            "u9t" => 'u9t.cn',
            "d0e" => 'd0e.cn(傅晓妹)',
            "o8d" => 'o8d.cn(黄福忠)',
            "0i4" => '0i4.cn(左浩然|马蓉蓉)',
            "q4f" => 'q4f.cn(姜子文)',
            "g0c" => 'g0c.cn(王古锋)',
            "q0r" => 'q0r.cn',
            "n0x" => 'n0x.cn',
            "h0e" => 'h0e.cn',
            "o4c" => 'o4c.cn',
            "9oj" => '9oj.cn',
            "4a6" => '4a6.cn',
        ];
        if(  $this->request->isPost() ){

            $postData = $this->request->post('row/a');
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
                    'channel_id' => trim($postData['channel_id']),
                    'sp_info_id' => $postData['sp_info_id'],
                    'domain_short' => $postData['domain_short'],
                    'send_start_time' => $postData['send_start_time'],
                    'send_end_time' => $postData['send_end_time'],
                    'sms_content' => trim($postData['sms_content']),
                    'send_status' => $postData['send_status'],
                    'city' => $postData['city'],
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
                    $shortLinkResult = json_decode($shortLinkResult, true);
                    if (empty($shortLinkResult['data'][0])) {
                        throw new \Exception('短链生成失败，请稍后重试..');
                    }
                    $result = $linkShortModel->save([
                        'remark'        => 'ZD-'.date('Ymd').'-M头条水滴展示-SSW-P-C1-'.date("Hi",strtotime($postData['send_start_time'])).'_'.date("Hi",strtotime($postData['send_end_time'])),
                        'link_id'       => $link['id'],
                        'business_link' => $link['link'],
                        'transfer_link' => $transfer_link,
                        'short_link'    => $shortLinkResult['data'][0]['short_url'],
                        'creator'       => 'lzc',
                        'create_time'   => date('Y-m-d H:i:s'),

                    ]);
                    if( !$result ){
                        throw new \Exception('短链生成失败！');
                    }
                    // 增加一条短信发送任务
                    $taskSendModel = new \app\admin\model\sms\TaskSend();
                    $result = $taskSendModel->save([
                        'title' => 'ZD-'.date('Ymd').'-M头条水滴展示-SSW-P-C1-'.date("Hi",strtotime($postData['send_start_time'])).'_'.date("Hi",strtotime($postData['send_end_time'])),
                        'company' => $link['company_name'],
                        'bank' => $link['bank_name'],
                        'business' => $link['business_name'],
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
                        'creator' => 'lzc',
                        'status' => 5, //4发送中，5发送完成
                        'sm_task_id' => $linkShortModel->id,
                        'file_path' => '',
                        'price' => $price,
                        'finish_time' => date('Y-m-d,H:i:s'),
                        //'remark' => '自动发送',
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
        $this->view->assign('spList', $spList);
        $this->view->assign('domainList',$domainList);

        return  $this->view->fetch();
    }

}