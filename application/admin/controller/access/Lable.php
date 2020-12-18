<?php

namespace app\admin\controller\access;

use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * 标签管理
 *
 * @icon fa fa-circle-o
 */
class Lable extends Backend
{
    
    /**
     * Lable模型对象
     * @var \app\admin\model\access\Lable
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\Lable;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $modList = (new \app\admin\model\access\ModSource())->column('nickname');
        $this->assignconfig("modList", array_combine($modList,$modList));
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['username'] = $this->auth->getUserInfo()['username'];
            $params['update_time'] = date('Y-m-d H:i:s');
            $result = $row->save($params);
            if( $result ){
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function import()
    {

        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        //$filePath = ROOT_PATH . DS . 'public' . DS . $file;
        $filePath = $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        if (!$PHPExcel = $reader->load($filePath)) {
            $this->error(__('Unknown data format'));
        }
        $insert = [];
        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
        for ($currentRow  = 3; $currentRow <= $allRow; $currentRow++) {
            $i = 1;
            $values = [
                'code'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'host_url'    =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'is_https'    =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'host'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'path'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'key'         =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'site'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'type'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'name'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'lable'       =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'class'       =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'subclass'    =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'priority'    =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'number'      =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'info'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'freq'        =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'info2'       =>  $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                'username'    => $this->auth->getUserInfo()['username'],
                'create_time' => date('Y-m-d H:i:s'),
            ];
            $insert[] = $values;
            $compare[] = $values['code'].$values['host'].$values['path'].$values['key'];
            //数据不允许重复
            $result = $this->model->field('id')->where([
                'code'=>$values['code'],
                'host'=>$values['host'],
                'path'=>$values['path'],
                'key' =>$values['key']
            ])->find();
            if ( $result ){
                $this->error("新增失败，第{$currentRow}行数据库里已经存在，请不要重复添加");
            }
        }
        if( count($compare) != count(array_unique($compare)) ){
            $this->error("新增失败，code,host,path,key不允许有重复，文件中有重复，请检查");
        }
        $result = $this->model->saveAll($insert);
        if( !$result ){
            $this->error('导入失败！！');
        }
        $this->success();
    }

    public function select(){
        if ($this->request->isAjax()) {

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $modList = (new \app\admin\model\access\ModSource())->column('nickname');

        $this->assignconfig("modList", array_combine($modList,$modList));
        return $this->view->fetch();

    }
}
