<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/11/2
 * Time: 12:01
 */

namespace app\admin\command;


use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')
            ->setDescription('use to test.....');
    }

    protected function execute(Input $input, Output $output)
    {
        $obj['imei'] = '';

        $output->writeln( $obj['imei']);
        $output->writeln( substr($obj['imei'],0,6) );
        $output->writeln( substr($obj['imei'],6) );        $output->writeln('test..');


        $row = Db::table('sms_task_send')->where('task_id',20852)->find();
        print_r( $row );

    }

}