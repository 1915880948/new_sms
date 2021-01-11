<?php

namespace app\admin\model\modeling;

use think\Model;


class ModelingDianxin extends Model
{

    

    protected $connection = 'db2';

    // 表名
    protected $table = 'sms_modeling_dianxin';
    
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