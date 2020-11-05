<?php

namespace app\admin\model\sms;

use think\Model;


class TaskSend extends Model
{


    // 表名
    protected $table = 'sms_task_send';


    protected $pk = 'task_id';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    







}
