<?php

namespace app\admin\model\basic;

use think\Model;


class SmsTemplate extends Model
{

    

    

    // 表名
    protected $table = 'sms_template';
    
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