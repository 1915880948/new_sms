<?php

namespace app\admin\model\access;

use think\Model;


class TaskFetch extends Model
{

    

    protected $connection = 'db2';

    // 表名
    protected $table = 'sms_fetch_task';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }









}
