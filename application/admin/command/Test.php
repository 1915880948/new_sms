<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/11/2
 * Time: 12:01
 */

namespace app\admin\command;


use Predis\Client;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Env;
use think\Log;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')
            ->setDescription('use to test.....');
    }

    protected function execute(Input $input, Output $output)
    {
        $redis3 = new Client([
            'host' => Env::get('redis3.host'),
            'port' => Env::get('redis3.port'),
            'password' => Env::get('redis3.password')
        ]);

        for($i=0;$i<100;$i++){
            $redis3->lpush('sms_toutiao_message_queue',json_encode('{"aid":"","androidid":"","callback":"","channel":"sd","cid":"","csite":"","idfa":"","imei":"6176a404676a5fb1082267f89c211aa7","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}'));
            $redis3->lpush('sms_toutiao_message_queue',json_encode('{"aid":"","androidid":"","callback":"","channel":"sd","cid":"","csite":"","idfa":"","imei":"6176a4cb82dc03384c4e1478a61281a9","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}'));
            $redis3->lpush('sms_toutiao_message_queue',json_encode('{"aid":"","androidid":"","callback":"","channel":"sd","cid":"","csite":"","idfa":"","imei":"6176a447a52b7c6cbc1e7663984c5a65","ip":"223.107.232.152","oaid":"","oaid_md5":"","os":"android","product":"sdb","type":"3"}'));
        }

//        $obj = json_decode($redis3->rpop('sms_toutiao_message_queue'),true);
//        $obj = json_decode($obj,true );
//        Log::log($obj['channel']);
//        $output->writeln('success!!');



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

    public function enHead($str) {
        $array = array('b','c','d','e','f','g','h','i','j','k');
        return $array[$str];
    }


// 6176a45d2cd5136b9e3d093e50b7d16d:17502199183 f6rfkbi   6176a45d2cd5136b9e3d093e50b7d16d:15885052629 4ckr89g    1a2b3c5d2cd5136b9e3d093e50b7d16d:13061968020 d9hrxwe
}