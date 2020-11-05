<?php

namespace app\admin\controller\blacklist;

use app\common\controller\Backend;
use Exception;
use fast\Random;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Config;
use think\Db;
use think\exception\PDOException;
use think\Log;
use think\Session;
/**
 * 黑名单管理
 *
 * @icon fa fa-circle-o
 */
class BlackReplyT extends Backend
{
    protected $relationSearch = true;

    /**
     * BlackReplyT模型对象
     * @var \app\admin\model\BlackReplyT
     */
    protected $model = null;
    protected $searchFields = 'phone,remark,status';
    protected $admin;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\BlackReplyT;
        $this->admin = Session::get('admin');

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index(){

        if( $this->request->isAjax() ){
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            Log::info(json_encode($where));
            $total = $this->model
                ->with('admin')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->with('admin')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total"=> $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        $redis = $this->getRedis();
        $config = \think\Config::get('redis');
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            $params['admin_id'] = $this->admin['id'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $params['update_time'] = date('Y-m-d H:i:s');
            $result = $this->model->allowField(true)->save($params);
            if ($result !== false) {
                $redis->set($config['blacklist_reply_t'].$params['phone'],'1');
                $this->success();
            } else {
                $this->error(__('No rows were inserted'));
            }
        }
        return $this->view->fetch();
    }

    public function edit($ids=null){
        //Log::error(Session::get('admin'));
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $redis = $this->getRedis();
        $config = \think\Config::get('redis');
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            $params['admin_id'] = $this->admin['id'];
            $result = $row->allowField(true)->save($params);
            if ($result !== false) {
                $redis->del($config['blacklist_reply_t'].$params['phone']);
                $redis->set($config['blacklist_reply_t'].$params['phone'],'1');
                $this->success();
            } else {
                $this->error(__('No rows were inserted'));
            }
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $list = $this->model->where($pk, 'in', $ids)->select();
            $redis = $this->getRedis();
            $config = \think\Config::get('redis');
            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                    $redis->del($config['blacklist_reply_t'].$v['phone']);
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));

    }

    public function upload(){
        Config::set('default_return_type', 'json');
        $upload = Config::get('upload');
        $file = $this->request->file('file');
        $redis = $this->getRedis();
        $config = \think\Config::get('redis');
        if (empty($file)) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        //判断是否已经存在附件
        $sha1 = $file->hash();
        $fileInfo = $file->getInfo();

        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';
        $replaceArr = [
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);

        $splInfo = $file->validate(['size' => '15678','ext'=>'csv,xls,xlsx'])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $filePath = './'.$uploadDir.$splInfo->getSaveName();
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($extension === 'csv') {
                $reader = new Csv();
            } elseif ($extension === 'xls') {
                $reader = new Xls();
            } else {
                $reader = new Xlsx();
            }

            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $insert = [];
            $mset = [];
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $phone = $currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue();
                $remark = $currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue();
                $insert[] = ['phone'=>$phone,'remark'=>$remark,
                    'create_time'=>date('Y-m-d H:i:s'),
                    'update_time'=>date('Y-m-d H:i:s'),
                    'admin_id'=> $this->admin['id']];
                $mset[$config['blacklist_reply_t'].$phone] = 1;
            }
            //Log::info($insert);
            $redis->mset($mset);
            $result = $this->model->saveAll($insert);
            //$this->success();
            $this->success('导入成功！','',json($result));
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }

    public function import()
    {
        $this->success();
    }
}
