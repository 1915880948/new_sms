<?php

namespace app\admin\model\basic;

use think\Model;


class Province extends Model
{

    

    

    // 表名
    protected $table = 'sms_province_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 追加属性
    protected $append = [

    ];



    public function getProvince($id = null)
    {
        $model = new \app\admin\model\basic\Province();
        $where['status'] = 1;
        if( (int)($id) ){
            $where['province_id'] = $id;
        }
        $list = $model->field('*')->where($where)->select();
        $provinces = [];
        foreach ($list as $v) {
            $provinces[$v['province_id']] = $v['province_name'];
        }
        return json($provinces);
    }



}
