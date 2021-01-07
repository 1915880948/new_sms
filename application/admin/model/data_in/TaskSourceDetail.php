<?php

namespace app\admin\model\data_in;

use think\Model;


class TaskSourceDetail extends Model
{

    

    protected $connection = 'db2';

    // 表名
    protected $table = 'sms_source_task_detail';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function taskSource()
    {
        return $this->belongsTo('TaskSource', 'source_task_id', 'task_id', [], 'INNER')->setEagerlyType(0);
    }

    public function model()
    {
        return $this->belongsTo('app\\admin\\model\\access\\basic\\Model2', 'url_no', 'model_no', [], 'LEFT')->setEagerlyType(0);
    }







}
