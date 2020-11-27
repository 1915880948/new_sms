<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/10/28
 * Time: 15:41
 */

namespace app\admin\command;

use app\admin\model\file\FileReply;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Env;

class AutoReply extends Command
{
    protected function configure()
    {
        $this->setName('autoreply')
            ->setDescription('everyday add a record');
    }

    protected function execute(Input $input, Output $output){

        $config = Db::table('fa_config')->where('name','file_reply')->find();
        $names = explode("|",$config['value']);
        $count = count($names);
        //print_r( json_encode($config ));
        $subQuery = (new FileReply())->field("max(id)")->group("file_path")->select(false);
        $link = (new FileReply())->where("id in (".$subQuery.")")->select();
        foreach ($link as $value){
            var_dump($value['id']);
            $name = (new FileReply())->where("id = ".$value['id'])->find();
            $namekey = array_search($name['useror'],$names);
            if ($namekey == $count-1){
                $needname = $names[0];
            }else{
                $needname = $names[$namekey+1];
            }
            $params['create_time'] = date("Y-m-d H:i:s");
            $params['file_name'] = $name['file_name'];
            $params['file_path'] = $name['file_path'];
            $params['creator'] = $name['creator'];
            $params['useror'] = $needname;
            $params['num'] = 0;
            (new FileReply())->save($params);
        }
        $output->writeln('success!');
    }
}