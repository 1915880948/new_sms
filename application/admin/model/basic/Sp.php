<?php

namespace app\admin\model\basic;

use think\Model;


class Sp extends Model
{

    

    

    // 表名
    protected $table = 'sms_sp_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function getSpInfo($fields = '', $id = 0, $status = 1)
    {
        $where = " status in ({$status})";
        if ($id) {
            $where .= " and id=$id ";
        }

        $list = $this->field($fields)->where($where)->select();
        $list = collection($list)->toArray();

        $sps = [];
        foreach ($list as $v) {
            $sps[$v['id']] = $v;
        }

        return $sps;
    }

    







}
