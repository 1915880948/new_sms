<?php

namespace app\admin\controller\modeling;

use app\common\controller\Backend;
use think\Env;

/**
 * U2结果管理
 *
 * @icon fa fa-circle-o
 */
class U2ResultTask extends Backend
{
    
    /**
     * U2ResultTask模型对象
     * @var \app\admin\model\modeling\U2ResultTask
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\modeling\U2ResultTask;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function download($ids=null){
        if( !$ids ){
            $this->error('缺少参数：ids');
        }
        $row = $this->model->where(['id'=>['in',$ids]])->find();
        $zipname = 'result-' . str_replace(',','-',$ids).'-'.time() . '.zip'; //最终生成的文件名
        $filepath = Env::get('file.FILE_ROOT_DIR') . 'submit/' .$row['path'] . '/' . $zipname;
        $basedir = Env::get('file.FILE_ROOT_DIR') . 'submit/' . $row['path'] . '/';
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        //重新生成文件
        $zip = new \ZipArchive();

        if ($zip->open($filepath, \ZipArchive::CREATE) === TRUE) {
            $dirArr = scandir($basedir);

            foreach($dirArr as $v){
                if ($v != '.' && $v != '..') {
                    $zipFile = $basedir . $v;
                    $zip->addFile($zipFile, $v);
                }
            }

            $zip->close();//关闭
            if (!file_exists($filepath)) {
                $this->error('无法找到文件');//即使创建，仍有可能失败
            }
            //下载zip包，下载完删除压缩数据
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length: " . filesize($filepath));
            header("Content-Disposition: attachment; filename=" . $zipname);
            readfile($filepath);
            unlink($filepath);
            ob_flush();
            flush();
            exit;
        }
        $this->error('无法打开文件，或者文件创建失败');

    }

}
