<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/10/28
 * Time: 17:33
 */
namespace app\admin\command;
use app\admin\model\basic\Sp;
use app\admin\model\sms\Link;
use app\admin\model\sms\LinkShort;
use app\admin\model\sms\TaskSend;
use function GuzzleHttp\Psr7\str;
use Predis\Client;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Env;
use think\Log;

class AutoSend extends Command
{
    public $task_num = 0;
    public $total_num = 0;
    public $total_send = 0;
    protected function configure()
    {
        $this->setName('autosend')
            ->setDescription('always send sms from redis to redis');
    }

    protected function execute(Input $input, Output $output){
        $runtime = microtime(true);
        $autoSendType = ['sd'];
        $popNum = 100;
        $redis3 = new Client([
            'host' => Env::get('redis3.host'),
            'port' => Env::get('redis3.port'),
            'password' => Env::get('redis3.password')
        ]);
        $redis4 = new Client([
            'host' => Env::get('redis4.host'),
            'port' => Env::get('redis4.port'),
            'password' => Env::get('redis4.password')
        ]);
        $popNum = $redis3->llen('sms_toutiao_message_queue');
        Log::log('开始执行--------->本次从队列取出'.$popNum.'条');
        $taskSendModel = new TaskSend();
        $linkShortModel = new LinkShort();
        $taskSendData = $taskSendModel->where('channel_from',2)->order('task_id','desc')->limit(1)->find();
        $linkShortData = $linkShortModel->where('id',$taskSendData['sm_task_id'])->find();
        //print_r($taskSendData); exit();
        $config = Db::table('fa_config')->where('id',18)->find();
        $sendTime = date('H:i:s');
       // print_r( $time ); die;
        if( $config['send_status'] == 2 || empty($taskSendData)  || !($config['send_start_time']<$sendTime && $sendTime<$config['send_end_time']) ){
            $output->writeln('stop to send or no send data!!');
            Log::log('只取，不做处理：'.$popNum.'条');
            for($i=0;$i<$popNum; $i++){
                $redis3->rpop('sms_toutiao_message_queue');
            }
            exit();
        }
        $spInfo = (new Sp())->where('id',$config['sp_info_id'])->find();
        $cityCode = Db::table('sms_city_code')->field('city,city_no')->select();
        $cityArray = array_combine(array_column($cityCode,'city_no'),array_column($cityCode,'city'));
        $phoneEncodeStr = '';
        $phoneEncodeStrNum = 0;
        $phoneEncodeBlackStr = '';
        $cityBlack = explode('|',$config['city']);
        for($i=0;$i<$popNum; $i++){
            $obj = json_decode($redis3->rpop('sms_toutiao_message_queue'),true);
            $obj = json_decode($obj,true );
            if( !$obj )   break;
            if( !in_array($obj['channel'],$autoSendType )){
                continue ;
            }
            if( $i % 5000 ==0 ){
                Log::log('已经执行到：'.$i.'条');
            }
//            $output->writeln( $obj['imei']);
//            $output->writeln( substr($obj['imei'],0,6) );
//            $output->writeln( substr($obj['imei'],6) );
            //die;
            $phoneStr = $redis4->hget(substr($obj['imei'],0,6),substr($obj['imei'],6));
            //print_r( $phoneStr ); die;
            if( $phoneStr ){
                $phoneExplode = explode(',',$phoneStr);
                foreach ($phoneExplode as $v) {
                   // print_r( $v );
                    if ( in_array($cityArray[substr($v,3,3)],$cityBlack)){
                        // $phoneEncodeBlackStr .= $v.',';   // 城市黑名单
                    }else{
                        $phoneEncodeStr .= $v.',';
                        $phoneEncodeStrNum++;
                        if( $phoneEncodeStrNum == 10000){  // 最对一万个
                            Log::log('10000手机号个请求一次加解密');
                            $this->dealEnPhone($taskSendData,$linkShortData,$config,$spInfo,$phoneEncodeStr,$redis4);
                            $phoneEncodeStrNum = 0;
                            $phoneEncodeStr ='';
                        }
                    }
                }
            }
        }

        //print_r($phoneEncodeStr); die;
        // 剩余不足10000个请求一次
        $this->dealEnPhone($taskSendData,$linkShortData,$config,$spInfo,$phoneEncodeStr,$redis4);
//        Log::log('入列完成，更新任务、发送数量');
//        Db::table('sms_task_send')->where('task_id',$taskSendData['task_id'])
//            ->data(['status' => 17])
//            ->inc('task_num',$this->task_num)
//            ->inc('total_num',$this->total_num)
//            ->inc('total_send',$this->total_send)
//            ->update();

        Log::log('执行完毕！！总耗时：'.round(microtime(true)-$runtime,3).' 内存消耗：'.round(memory_get_usage()/(1024*1024),3)."MB" );
        $output->writeln('success!  Running='.round(microtime(true)-$runtime,3) );
        $output->writeln('内存消耗:'.round(memory_get_usage()/(1024*1024),3)."MB"  );


    }

    public function dealEnPhone($taskSendData,$linkShortData,$config,$spInfo,$phoneEncodeStr,$redis4){   // 解密手机号，最多10000个。

        $decodeResult = curl_encrypt($phoneEncodeStr); //解密手机号，最多10000
        $decodeResult = json_decode($decodeResult, true);
    //print_r( $decodeResult['data']);  die;
        $queueTime =  date("Y-m-d H:i:s");
        $createShortPre = 40; // 为用户生成短链 40请求一次
        $linkArr = [] ;
        $this->task_num +=count($decodeResult['data']);
        $this->total_num = $this->task_num;
        foreach ( $decodeResult['data'] as $k=>$v){
            //print_r( $v);
            if( $redis4->get('sms_blackuser_'.$v) ) continue;
            if( $redis4->get('sms_senno_'.$v) ) continue;
            if( $redis4->get('sms_send_bx_'.$v) ) continue;
            $redis4->setex('sms_send_bx_'.$v,86400,1) ;  // 已发短信，列入保险黑名单24h。

            if( strlen($v)  == 11 ){ // 是手机号
                $this->total_send ++;
                $linkArr[] = $linkShortData['transfer_link'].'&m='.($this->en($v));
            }
            if( count($linkArr) == $createShortPre ){
                $this->createShortLinkForUser($linkArr,$taskSendData['task_id'],$spInfo['sp_no'],$queueTime,$config);
                $linkArr = [] ;
            }
        }
        //print_r( $linkArr );
        if( $linkArr ){  // 剩余请求一次
            $this->createShortLinkForUser($linkArr,$taskSendData['task_id'],$spInfo['sp_no'],$queueTime,$config);
        }

        Log::log('每个手机号解密批次，后入列完成，更新任务数量：'.$this->task_num.'、发送数量：'.$this->total_send);
        $row = Db::table('sms_task_send')->where('task_id',$taskSendData['task_id'])->find();
        if( $row['status'] != 4 ){
            Db::table('sms_task_send')->where('task_id',$taskSendData['task_id'])
                ->data(['status' => 17])
                ->inc('task_num',$this->task_num)
                ->inc('total_num',$this->total_num)
                ->inc('total_send',$this->total_send)
                ->update();
        }else{
            Db::table('sms_task_send')->where('task_id',$taskSendData['task_id'])
                ->inc('task_num',$this->task_num)
                ->inc('total_num',$this->total_num)
                ->inc('total_send',$this->total_send)
                ->update();
        }
        $this->task_num = $this->total_num = $this->total_send = 0;
    }

    public function createShortLinkForUser($linkArr,$task_id,$sp_no,$queue_time,$config){ // 为用户生成短链，受接口限制每次40个。
        $phonePattern = '/^1[3-9][\\d]{9}$/';
        $pattern = '/http[s]?:\\/\\/[-.=%&\\?\\w\\/]+/';

        $redis1 = new Client([
            'host' => Env::get('redis1.host'),
            'port' => Env::get('redis1.port'),
            'password' => Env::get('redis1.password')
        ]);

        $transfer_link = '';
        foreach($linkArr as $v) {
            $transfer_link .= "&url=".rawurlencode($v);
        }
        //print_r( $transfer_link ); die;
        $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($config['domain_short'])  . $transfer_link;
        //print_r( $apiUrl) ; die;
        $shortLinkResult = httpRequest($apiUrl, 'GET');
        $shortLinkResult = json_decode($shortLinkResult,true);
        //print_r( $shortLinkResult );
        $realIntoQueueNumber = 0;
        if(!empty($shortLinkResult['data'][0])) {
            foreach($shortLinkResult['data'] as $item) {
                $phoneArr = explode('&m=',$item['url']);
                if( isset($phoneArr[1]) ){
                    $realIntoQueueNumber++;
                    $phone = $this->de($phoneArr[1]);
                    $replace = substr($phone, -4);
                    $curContent = str_replace('XXXX', $replace, $config['sms_content']);
                    $curContent = preg_replace($pattern, $item['short_url'], $curContent);
                    //写入redis队列
                    $redisArray = [
                        'taskId' => $task_id,
                        'companyId' => null,
                        'bankId' => null,
                        'businessId' => null,
                        'spNo' => $sp_no,
                        'mobilePhone' => $phone,
                        'queueTime' => $queue_time,
                        'content' => $curContent,
                    ];
                    $redisValues[] = json_encode($redisArray);
                    //Log::log('入列数据====='.json_encode($redisArray) );
                }

            }
        }
        if( !empty($redisValues) ){
            $redis1->lpush('sms_send_queue_' . $task_id, ...$redisValues);
        }
        Log::log('为用户生成短链后，入短信队列'.$realIntoQueueNumber.'条');

    }

    //加密
    public function en($str) {
        $head = substr($str, 1, 1);
        $head = $this->enHead($head);
        $body = substr($str, -4, 4).substr($str, -9, 5);
        $body = intval($body);
        $body = base_convert($body, 10, 36);
        return $body.$head;
    }
    //解密
    public function de($str) {
        $head = substr($str, -1, 1);
        $head = $this->deHead($head);
        $body = substr($str, 0, strlen($str)-1);
        $body = base_convert($body, 36, 10);
        $body = str_pad($body, 9, "0", STR_PAD_LEFT);
        $body = substr($body, 4, 5).substr($body, 0, 4);
        return '1'.$head.$body;
    }

    public function enHead($str) {
        $array = array('b','c','d','e','f','g','h','i','j','k');
        return $array[$str];
    }

    public function deHead($str) {
        $array = array('b'=>'0','c'=>'1','d'=>'2','e'=>'3','f'=>'4','g'=>'5','h'=>'6','i'=>'7','j'=>'8','k'=>'9');
        return $array[$str];
    }

}

//
//"{\"taskId\":20851,\"companyId\":\"\",\"bankId\":\"\",\"businessId\":\"\",\"spNo\":\"\\u5916\\u90e8\\u901a\\u75280.035\",
//\"mobilePhone\":\"13927401626\",\"queueTime\":\"2020-11-01 16:00:20\",\"content\":\"\\u5c3e\\u53f71626\\u5ba2\\u6237\\uff0c\\u4f60\\u597d\"}"
//"{\"taskId\":20851,\"companyId\":\"\",\"bankId\":\"\",\"businessId\":\"\",\"spNo\":\"\\u5916\\u90e8\\u901a\\u75280.035\",
//\"mobilePhone\":\"18783960222\",\"queueTime\":\"2020-11-01 16:00:20\",\"content\":\"\\u5c3e\\u53f70222\\u5ba2\\u6237\\uff0c\\u4f60\\u597d\"}"
//

//{
//"aid":"1680513034865726",
//"androidid":"e120ad1031e9d869356e5fba6c0519f3",
//"callback":"CL6Y7_Oujf4CEL6Izoyvjf4CGN2MwMOW9J8GIN2MwMOW9J8GKIjwjeCl_vwCMA44hYvPtwNCKTQzMTcxY2E2LTk2ZGEtNDdhMS05MTEwLTc4ZjkzYWJlNzk3YnUzMDE4SIDSk60DUACIAQI=",
//"channel":"sd",
//"cid":"1680513086751806",
//"csite":"900000000",
//"idfa":"",
//"imei":"",
//"ip":"139.213.70.192","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}