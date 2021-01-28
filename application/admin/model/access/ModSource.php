<?php

namespace app\admin\model\access;

use think\Model;


class ModSource extends Model
{

    

    protected $connection = 'db2';

    // 表名
    protected $table = 'sms_mod_source';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];




    public function getModList()
    {
        $modlist = $this->column("nickname,nickname");
        return $modlist;

    }





}
