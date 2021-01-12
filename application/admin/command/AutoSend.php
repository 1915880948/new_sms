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
        $popNum = 10;
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
        Log::log('开始执行--------->从队列取出'.$popNum.'条');
        $taskSendModel = new TaskSend();
        $linkShortModel = new LinkShort();
        $spModel = new Sp();
        $list =  Db::table('fa_config')->where(['id'=>['>',17],'type'=>['=','1']])->select();
        $autoSendType = array_column($list,'name');
        $config = [];
        foreach ($list as  $item ){
            $config[$item['name']]        = $item;
            $taskSendData[$item['name']]  =  $taskSendModel->where(['channel_from'=>['=',2],'bank_id'=>['=',$item['bank_id']],'remark'=>['=',$item['name']]])->order('task_id','desc')->limit(1)->find();
            $linkShortData[$item['name']] = $linkShortModel->where('id', $taskSendData[$item['name']]['sm_task_id'])->find();
            $spInfo[$item['name']] = $spModel->where('id',$item['sp_info_id'])->find();
            $phoneEncodeStr[$item['name']] = '';
            $phoneEncodeStrNum[$item['name']] = 0;
            //$phoneEncodeBlackStr[$item['name'] = '';
            $cityBlack[$item['name']] = explode('|',$item['city']);
            $bankBlack[$item['name']] = $item['bank_id'];
        }
        $sendTime = date('H:i:s');
        $cityCode = Db::table('sms_city_code')->field('city,city_no')->select();
        $cityArray = array_combine(array_column($cityCode,'city_no'),array_column($cityCode,'city'));
        for($i=0;$i<$popNum; $i++){
            if( $i % 5000 ==0 ){
                Log::log('已经执行到：'.$i.'条');
            }
            $obj = json_decode($redis3->rpop('sms_toutiao_message_queue'),true);
            $obj = json_decode($obj,true );
            if( !$obj )   continue;
            $index = $obj['channel'].'_'.$obj['product']; // 业务下标
            if( !in_array($index,$autoSendType )){
                continue ;
            }
            if( !isset($obj['type']) ){
                Log::log($obj);
            }
            Log::log($config[$index]['timely_type'].'======>'.$obj['type']);
            if( stripos($config[$index]['timely_type'],$obj['type']) === false ){
                continue;
            }
            // 确定配置
            if( $config[$index]['send_status'] == 2 || empty($taskSendData[$index])  || !($config[$index]['send_start_time']<$sendTime && $sendTime<$config[$index]['send_end_time']) ){
               continue ;
            }
            $imei = 'imei';
            if( $obj['channel'] == 'za') $imei = 'imei2';
            $phoneStr = $redis4->hget(substr($obj[$imei],0,6),substr($obj[$imei],6));
            if( $phoneStr ){
                $phoneExplode = explode(',',$phoneStr);
                foreach ($phoneExplode as $v) {
                    if ( in_array($cityArray[substr($v,3,3)],$cityBlack[$index])){
                        continue ; // $phoneEncodeBlackStr .= $v.',';   // 城市黑名单
                    }
                    $phoneEncodeStr[$index] .= $v.',';
                    $phoneEncodeStrNum[$index]++;
                    if( $phoneEncodeStrNum[$index] == 10000){  // 最多一万个
                        Log::log('10000手机号个请求一次加解密===>'.$index);
                        $this->dealEnPhone($taskSendData[$index],$linkShortData[$index],$config[$index],$spInfo[$index],$phoneEncodeStr[$index],$redis4,$bankBlack[$index]);
                        $phoneEncodeStrNum[$index] = 0;
                        $phoneEncodeStr[$index] ='';
                    }
                }
            }
        }

        // 剩余不足10000个请求一次
        foreach ($config as $k){
            Log::log(('最后一次phoneEncodeStrNum['.$k['name'].']'.$phoneEncodeStrNum[$k['name']]));
            if( $phoneEncodeStrNum[$k['name']] > 0 ){
                $this->dealEnPhone($taskSendData[$k['name']],$linkShortData[$k['name']],$config[$k['name']],$spInfo[$k['name']],$phoneEncodeStr[$k['name']],$redis4,$bankBlack[$k['name']]);
            }
        }

        $totalTime = round(microtime(true)-$runtime,3);
        Log::log('执行完毕！！总数量：'.$popNum.'，总耗时：'.$totalTime.'，处理速度：'.round($popNum/$totalTime,2).' 内存消耗：'.round(memory_get_usage()/(1024*1024),3)."MB" );
        $output->writeln('success!  Running='.round(microtime(true)-$runtime,3) );
        $output->writeln('内存消耗:'.round(memory_get_usage()/(1024*1024),3)."MB"  );


    }

    public function dealEnPhone($taskSendData,$linkShortData,$config,$spInfo,$phoneEncodeStr,$redis4,$bankBlack){   // 解密手机号，最多10000个。
        //Log::log('dealEnPhone');
        $decodeResult = curl_encrypt($phoneEncodeStr); //解密手机号，最多10000
        $decodeResult = json_decode($decodeResult, true);
        //Log::log( json($decodeResult['data']));
        $queueTime =  date("Y-m-d H:i:s");
        $createShortPre = 40; // 为用户生成短链 40请求一次
        $linkArr = [] ;
        $this->task_num +=count($decodeResult['data']);
        $this->total_num = $this->task_num;
        foreach ( $decodeResult['data'] as $k=>$v){
            if( $redis4->get('sms_blackuser_'.$v) ) continue;
            if( $redis4->get('sms_senno_'.$v) ) continue;
            if( $redis4->get('bank_'.$bankBlack.'_'.$v) ) continue;
            $redis4->setex('bank_'.$bankBlack.'_'.$v,86400,1) ;  // 已发短信，列入保险黑名单24h。

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
        //Log::log('createShortLinkForUser');
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
//"idfa":"",175012345678
//"imei":"",6176a45d2cd5136b9e3d093e50b7d16d:17502199183  6176a45d2cd5136b9e3d093e50b7d16d:15885052629    1a2b3c5d2cd5136b9e3d093e50b7d16d:13061968020
//"ip":"139.213.70.192","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}


// {"aid":"1679065789348920","androidid":"6176a45d2cd5136b9e3d093e50b7d16d","callback":"","channel":"sd","cid":"1679068252857463","csite":"900000000","idfa":"","imei":"6176a45d2cd5136b9e3d093e50b7d16d","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}
// {"aid":"1679065789348920","androidid":"1234565d2cd5136b9e3d093e50b7d16d","callback":"","channel":"za","cid":"1679068252857463","csite":"900000000","idfa":"","imei":"1234565d2cd5136b9e3d093e50b7d16d","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}
// {"aid":"1679065789348920","androidid":"6543215d2cd5136b9e3d093e50b7d16d","callback":"","channel":"sd","cid":"1679068252857463","csite":"900000000","idfa":"","imei":"6543215d2cd5136b9e3d093e50b7d16d","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}



