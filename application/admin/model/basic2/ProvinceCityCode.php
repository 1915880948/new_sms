<?php

namespace app\admin\model\basic2;

use think\Model;


class ProvinceCityCode extends Model
{

    

    protected $connection = 'db2';

    // 表名
    protected $table = 'province_city_code';
    
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