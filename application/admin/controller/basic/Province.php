<?php

namespace app\admin\controller\basic;

use app\admin\model\basic\City;
use app\common\controller\Backend;

/**
 * 省份
 *
 * @icon fa fa-circle-o
 */
class Province extends Backend
{
    protected $model;
    public function _initialize()
    {
        parent::_initialize();

    }
    

    public function getProvince($id = null)
    {
        $model = new \app\admin\model\basic\Province();
        $where['status'] = 1;
        if( (int)($id) ){
            $where['province_id'] = $id;
        }
        $list = $model->field('province_id,province_name,province_id as value,province_name as name')->where($where)->select();
        $this->success('','',$list);
    }

    public function getCity($pid = null,$id='')
    {
        $model = new City();
        $where['status'] = 1;
        if( $pid ){
            $where['province_id'] = $pid;
        }
        if( (int)($id) ){
            $where['city_id'] = $id;
        }
        $list = $model->field('city_id,city_name, city_id as value,city_name as name')->where($where)->select();

        $this->success('','',$list);
    }

}
