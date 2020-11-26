<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/10/28
 * Time: 15:41
 */

namespace app\admin\command;

use app\admin\model\sms\Link;
use app\admin\model\sms\LinkShort;
use app\admin\model\sms\TaskSend;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Env;
use think\Log;

class ShortLinkAdd extends Command
{
    protected function configure()
    {
        $this->setName('shortlinkadd')
            ->setDescription('everyday add a record');
    }

    protected function execute(Input $input, Output $output){
        $linkShortModel =new LinkShort();
        $list = Db::table('fa_config')->where(['id'=>['>',17]])->select();
        foreach ($list  as  $config ){

            //print_r( json_encode($config ));
            $link = (new Link())->where('channel_id',trim($config['channel_id']))->find();
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$config['sp_info_id'])->value('p.PRICEX');
            if( !$link ){
                $output->writeln('查不到此渠道号！！');
            }
            Db::startTrans();
            try {
                $linkShortLastID = $linkShortModel->max('id');
                $transfer_link =  'http://'.Env::get('sms_click.host').'/link.php?id='.($linkShortLastID+1);

                $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($config['domain_short']) . '&url=' . rawurlencode($transfer_link);
                $shortLinkResult = httpRequest($apiUrl, 'GET');
                $shortLinkResult = json_decode( $shortLinkResult, true);
                if (empty($shortLinkResult['data'][0])) {
                    $output->writeln('短链生成失败，请稍后重试..');
                    throw new \Exception('短链生成失败，请稍后重试..');
                }
                $result = $linkShortModel->save([
                    'remark'        => 'ZD-'.date('Ymd').'-'.$config['title'].'-'.date("Hi",strtotime($config['send_start_time'])).'_'.date("Hi",strtotime($config['send_end_time'])),//ZD-202021105-M头条水滴展示-SSW-P-C1-0901_1001
                    'link_id'       => $link['id'],
                    'business_link' => $link['link'],
                    'transfer_link' => $transfer_link,
                    'short_link'    => $shortLinkResult['data'][0]['short_url'],
                    'creator'       => 'lzc',
                    'create_time'   => date('Y-m-d H:i:s'),
                ]);
                if( !$result ){
                    throw new \Exception('短链添加失败..');
                }
                // 增加一条短信发送任务
                $taskSendModel = new TaskSend();
                //$maxID = $taskSendModel->field('task_id')->where('channel_from',2)->order('task_id','desc')->limit(1)->find();
                //$result1 = $taskSendModel->isUpdate(true)->save(['status'=>5],['channel_from'=>2,'status'=>4]); // 发送完成
                $result = $taskSendModel->isUpdate(false)->save([
                    'title' => 'ZD-'.date('Ymd').'-'.$config['title'].'-'.date("Hi",strtotime($config['send_start_time'])).'_'.date("Hi",strtotime($config['send_end_time'])),
                    'company' => $link['company_name'],
                    'company_id' => $link['company_id'],
                    'bank' => $link['bank_name'],
                    'bank_id' => $link['bank_id'],
                    'business' => $link['business_name'],
                    'business_id' => $link['business_name_id'],
                    'channel_id' => $link['channel_id'],
                    'data_id' => 0,
                    'send_time' => date('Y-m-d,H:i:s'),
                    'sms_gate_id' =>  $config['sp_info_id'],
                    'sms_template_id' => 0,
                    'sms_content' => $config['sms_content'],
                    'shortlink' => $shortLinkResult['data'][0]['short_url'],
                    'channel_from' => 2, // 0，1动态短信，2实时短信
                    'link_from' => 1,
                    'create_time' => date('Y-m-d H:i:s'),
                    'creator' => $config['group'],
                    'status' => 5, //4发送中，5发送完成
                    'sm_task_id' => $linkShortModel->id,
                    'file_path' => '',
                    'price' => $price,
                    'finish_time' => date('Y-m-d,H:i:s'),
                    'remark' => $config['name'],
                ]);
                if( !$result ){
                    throw new \Exception('短信发送任务创建失败..');
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
            }

        }

        $output->writeln('success!');
    }
}