<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/11/2
 * Time: 12:01
 */

namespace app\admin\command;


use app\admin\model\access\Lable;
use Predis\Client;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;
use think\Env;
use think\Log;

class BackupSendData extends Command
{
    public $YYYYMM = 'YYYYMM';
    public $YYYYMMDD = 'YYYYMMDD';
    public $db_name  =  'sms_send_data';
    public $click = " (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `phone` char(11) DEFAULT NULL COMMENT '手机号',
  `phone_sec` varchar(10) DEFAULT NULL COMMENT '密文号码',
  `shortlink_id` int(11) DEFAULT NULL COMMENT '短链id',
  `click_time` datetime DEFAULT NULL COMMENT '点击时间',
  `ip` varchar(30) DEFAULT NULL COMMENT 'IP',
  `agent` varchar(512) DEFAULT NULL COMMENT 'UA',
  `province_name` varchar(16) DEFAULT NULL COMMENT 'ip省份',
  `city_name` varchar(16) DEFAULT NULL COMMENT 'ip城市',
  `phone_model` varchar(32) DEFAULT NULL COMMENT '手机型号',
  PRIMARY KEY (`id`),
  KEY `shortlink_id` (`shortlink_id`),
  KEY `click_time` (`click_time`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='短信链接点击日志表';";

    public $report = " (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT '任务编号',
  `sp_seq` varchar(32) NOT NULL COMMENT '发送序列号',
  `sp_no` varchar(20) NOT NULL COMMENT '网关编号',
  `phone` char(11) NOT NULL COMMENT '手机号',
  `phone_sec` varchar(16) NOT NULL COMMENT '加密手机号',
  `error_code` varchar(24) DEFAULT NULL COMMENT 'status=3时错误编码',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 提交成功 2：提交失败 3：发送成功 4:发送失败 5:网关已提交',
  PRIMARY KEY (`id`),
  KEY `sp_seq_index` (`sp_seq`),
  KEY `sp_no_index` (`sp_no`),
  KEY `task_id_index` (`task_id`),
  KEY `status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;";

    public $send = " (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT '任务编号',
  `company_id` int(11) NOT NULL COMMENT '公司编号',
  `bank_id` int(11) NOT NULL COMMENT '银行编号',
  `business_id` int(11) NOT NULL COMMENT '业务编号',
  `sp_seq` varchar(32) NOT NULL COMMENT '发送序列号',
  `sp_no` varchar(20) NOT NULL COMMENT '网关编号',
  `phone` char(11) NOT NULL COMMENT '手机号',
  `phone_sec` varchar(16) NOT NULL COMMENT '加密手机号',
  `content` varchar(512) NOT NULL COMMENT '短信内容',
  `error_code` varchar(24) DEFAULT NULL COMMENT 'status=3时错误编码',
  `retry` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: 首次发送 2：重发',
  `queue_time` datetime NOT NULL COMMENT '队列表的创建时间',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `report_time` datetime DEFAULT NULL COMMENT '网关回执时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1: 提交成功 2：提交失败 3：发送成功 4:发送失败 ',
  PRIMARY KEY (`id`),
  KEY `sp_seq_index` (`sp_seq`),
  KEY `sp_no_index` (`sp_no`),
  KEY `task_id_index` (`task_id`),
  KEY `status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;";
    protected function configure()
    {
        $this->setName('backupsenddata')
            ->setDescription('backup sms_send_data.....');
    }

    protected function execute(Input $input, Output $output)
    {
        $YYYYMMDD_time= date("Ymd", strtotime("-6 month"));
        $YYYYMM_time = substr($YYYYMMDD_time,0,6) ;
        $limit = 1000;  // 注意太大，会报错。mysql有限制
        $db3 = Config::get('db3');
        $db4 = Config::get('db4');
        //数据源
        $mysqli4 = new \mysqli(
            $db4['hostname'],
            $db4['username'],
            $db4['password'],
            $db4['database'],
            $db4['hostport']
        );
        //备份数据
        $mysqli3 = new \mysqli(
            $db3['hostname'],
            $db3['username'],
            $db3['password'],
            $db3['database'],
            $db3['hostport']
        );

        Log::log('开始：'."sms_click_log_".$YYYYMM_time."'");
        $sql = "show tables like '"."sms_click_log_".$YYYYMM_time."'";
        if( mysqli_num_rows($mysqli3->query($sql)) != 1){ //表不存在
            //print_r('表不存在');
            $sql = 'create table sms_click_log_'.$YYYYMM_time.$this->click;
            //print_r($sql);
            $mysqli3->query("SET NAMES utf8mb4");
            $mysqli3->query($sql);
            $total = mysqli_fetch_row($mysqli4->query("select count('id') from sms_click_log_$YYYYMM_time"))[0];
            $pages = $total/$limit + 1;
            //$pages = 1; $limit=10;
            for($page=0; $page < $pages; $page++){
                $select_sql = "SELECT * FROM sms_click_log_$YYYYMM_time order by 'id' desc limit ".$page*$limit.",".$limit;
                $inset_sql = "insert into sms_click_log_".$YYYYMM_time."(id,phone,phone_sec,shortlink_id,click_time,ip,agent,province_name,city_name,phone_model) values ";
                $result = $mysqli4->query($select_sql);
                while ($row = mysqli_fetch_array($result)){
                    $inset_sql .= '('.$row['id'].', "'.$row['phone'].'","'.$row['phone_sec'].'","'.$row['shortlink_id'].'","'.$row['click_time'].'","'.$row['ip'].'","'.$row['agent'].'","'.$row['province_name'].'","'.$row['city_name'].'", "'.$row['phone_model'].'"),';
                }
                $inset_sql = trim($inset_sql,",");
                //Log::log($inset_sql);
                //$this->insert_db($mysqli3,"sms_click_log_$YYYYMM_time",$inset_sql);
                $mysqli3->query($inset_sql,MYSQLI_USE_RESULT);
            }
        }
        Log::log('开始：'."sms_report_".$YYYYMMDD_time."'");
        $sql = "show tables like '"."sms_report_".$YYYYMMDD_time."'";
        if( mysqli_num_rows($mysqli3->query($sql)) != 1){ //表不存在
            //print_r('表不存在');
            $sql = 'create table sms_report_'.$YYYYMMDD_time.$this->report;
            //print_r($sql);
            $mysqli3->query("SET NAMES utf8mb4");
            $mysqli3->query($sql);
            $total = mysqli_fetch_row($mysqli4->query("select count('id') from sms_report_$YYYYMMDD_time"))[0];
            $pages = $total/$limit + 1;
            //$pages = 1; $limit=10;
            for($page=0; $page < $pages; $page++){
                $select_sql = "SELECT * FROM sms_report_$YYYYMMDD_time order by 'id' desc limit ".$page*$limit.",".$limit;
                $inset_sql = "insert into sms_report_".$YYYYMMDD_time."(id,task_id,sp_seq,sp_no,phone,phone_sec,error_code,create_time,status) values ";
                $result = $mysqli4->query($select_sql);
                while ($row = mysqli_fetch_array($result)){
                    $inset_sql .= '('.$row['id'].', "'.$row['task_id'].'","'.$row['sp_seq'].'","'.$row['sp_no'].'","'.$row['phone'].'","'.$row['phone_sec'].'","'.$row['error_code'].'","'.$row['create_time'].'","'.$row['status'].'"),';
                }
                $inset_sql = trim($inset_sql,",");
                //Log::log($inset_sql);
                $mysqli3->query($inset_sql,MYSQLI_USE_RESULT);
            }
        }

        Log::log('开始：'."sms_send_log_".$YYYYMMDD_time."'");
        $sql = "show tables like '"."sms_send_log_".$YYYYMMDD_time."'";
        if( mysqli_num_rows($mysqli3->query($sql)) != 1){ //表不存在
            //print_r('表不存在');
            $sql = 'create table sms_send_log_'.$YYYYMMDD_time.$this->send;
            //print_r($sql);
            $mysqli3->query("SET NAMES utf8mb4");
            $mysqli3->query($sql);
            $total = mysqli_fetch_row($mysqli4->query("select count('id') from sms_send_log_$YYYYMMDD_time"))[0];
            $pages = $total/$limit + 1;
            //$pages = 1; $limit=10;
            for($page=0; $page < $pages; $page++){
                $select_sql = "SELECT * FROM sms_send_log_$YYYYMMDD_time order by 'id' desc limit ".$page*$limit.",".$limit;
                $inset_sql = "insert into sms_send_log_".$YYYYMMDD_time."(id,task_id,company_id,bank_id,business_id,sp_seq,sp_no,phone,phone_sec,content,error_code,retry,queue_time,create_time,update_time,report_time,status) values ";
                $result = $mysqli4->query($select_sql);
                while ($row = mysqli_fetch_array($result)){
                    $inset_sql .= '('.$row['id'].', "'.$row['task_id'].'","'.$row['company_id'].'","'.$row['bank_id'].'","'.$row['business_id'].'","'.$row['sp_seq'].'","'.$row['sp_no'].'","'.$row['phone'].'","'.$row['phone_sec'].'","'.$row['content'].'","'.$row['error_code'].'","'.$row['retry'].'","'.$row['queue_time'].'","'.$row['create_time'].'",null,null,"'.$row['status'].'"),';
                    //$inset_sql .= '('.$row['id'].', "'.$row['task_id'].'","'.$row['company_id'].'","'.$row['bank_id'].'","'.$row['business_id'].'","'.$row['sp_seq'].'","'.$row['sp_no'].'","'.$row['phone'].'","'.$row['phone_sec'].'","'.$row['content'].'","'.$row['error_code'].'","'.$row['retry'].'","'.$row['queue_time'].'","'.$row['create_time'].'","'.$row['update_time'].'","'.$row['report_time'].'","'.$row['status'].'"),';
                }
                $inset_sql = trim($inset_sql,",");
                //Log::log($inset_sql);
                $mysqli3->query($inset_sql,MYSQLI_USE_RESULT);
            }
        }



    }

    public function insert_db($mysql,$table,$sql){
        $mysql->query($sql);
    }

}