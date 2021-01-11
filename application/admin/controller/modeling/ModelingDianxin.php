<?php

namespace app\admin\controller\modeling;

use app\common\controller\Backend;

/**
 * 电信建模管理
 *
 * @icon fa fa-circle-o
 */
class ModelingDianxin extends Backend
{
    
    /**
     * ModelingDianxin模型对象
     * @var \app\admin\model\modeling\ModelingDianxin
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\modeling\ModelingDianxin;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function add()
    {
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            // 查重dpi_no
            $dpi_no = $this->model->where('dpi_no',trim($params['dpi_no']))->find();
            if( $dpi_no ){
                $this->error('DPI编号已经存在：'.$params['dpi_no']);
            }
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $result = $this->model->allowField(true)->save($params);
            if( !$result ){
                 $this->error('保存失败！！！');
            }
            $this->success('添加成功！');
        }
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if( !$row ){
            $this->error('参数错误！！');
        }
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            // 查重dpi_no
            $dpi_no = $this->model->where(['dpi_no'=>trim($params['dpi_no']),'id'=>['<>',$row['id']]])->find();
            if( $dpi_no ){
                $this->error('DPI编号已经存在：'.$params['dpi_no']);
            }
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $result = $row->allowField(true)->save($params);
            if( !$result ){
                $this->error('修改失败！！！');
            }
            $this->success('修改成功！');
        }
        $this->view->assign('row',$row);
        return $this->view->fetch();
    }

    public function start($ids = null){
        $row = $this->model->get($ids);
        if( !$row ){
            $this->error('参数错误！！');
        }
        $result = $row->allowField(true)->save(['status'=>1]);
        if( $result ){
            $this->success("成功！！");
        }
        $this->error("失败！！");
    }

    public function stop($ids = null){
        $row = $this->model->get($ids);
        if( !$row ){
            $this->error('参数错误！！');
        }
        $result = $row->allowField(true)->save(['status'=>2]);
        if( $result ){
            $this->success("成功！！");
        }
        $this->error("失败！！");
    }

}
