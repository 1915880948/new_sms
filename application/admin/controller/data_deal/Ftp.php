<?php

namespace app\admin\controller\data_deal;

use app\common\controller\Backend;
use think\Env;

/**
 * ftp任务管理
 *
 * @icon fa fa-circle-o
 */
class Ftp extends Backend
{
    
    /**
     * Ftp模型对象
     * @var \app\admin\model\data_deal\Ftp
     */
    protected $model = null;
    protected $typeArr = [
        1 => '一对一',
        2 => '一对多',
    ];
    protected $newTypeArr = [
        1 => 'imei匹配手机号',
        2 => '手机号匹配imei',
        3 => '泰康数据匹配',
    ];
    protected $imeiTypeArr = [
        0 => '请选择',
        1 => '14imei',
        2 => '15imei',
        3 => 'md14',
        4 => 'md15',
        5 => 'md15大写',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\data_deal\Ftp;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post('row/a');
            if (!$params['file_path']) {
                $this->error('文件必须上传！');
            }
            $data['creator'] = $this->auth->getUserInfo()['username'];
            $data['create_time'] = date('Y-m-d H:i:s');
            $files = $file_paths = [];
            $files_list = rtrim($params['files_list'], "|");
            $files_list = explode('|', $files_list);
            foreach ($files_list as $file_list) {
                $filelists = explode(",", $file_list);
                $file_name = trim($filelists[0]);
                $file_path = $filelists[1];
                if ($file_path) {
                    //$file_path = C('DOWNLOAD_UPLOAD.rootPath') . $file_path['savepath'] . $file_path['savename'];
                    $lastPath = Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'input_imei';
                    if (!is_dir($lastPath)) {
                        @mkdir($lastPath);
                    }
                    $lastFile = $lastPath . '/' . $file_name;
                    $abc = copy($file_path, $lastFile);
                    if ($abc && !empty($lastFile)) {
                        $name = str_replace(strrchr($file_name, "."), "", $file_name);
                        $list = explode('_', $name);
                        //去掉后缀
                        $data['name'] = $list[0];
                        $data['model_date'] = $list[1];
                        $data['class_one'] = $list[2];
                        $data['class_two'] = $list[3];
                        $data['model_name'] = $list[4];
                        $data['model_num'] = $list[5];
                        $data['file_path'] = $file_name;
                        $contents[] = $data;

                    } else {
                        $this->error('ftp文件下载失败。');
                    }
                }
            }
            if (!empty($contents)) {
                $this->model->saveAll($contents);
            }
            $this->success('数据入库成功！！');
        }
        $this->view->assign('typeArr', $this->typeArr);
        $this->view->assign('newTypeArr', $this->newTypeArr);
        $this->view->assign('imeiTypeArr', $this->imeiTypeArr);
        return $this->view->fetch();
    }
    //下载
    public function download($ids){

        $where = 'id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if ($fetchList['status'] != 3) {
            $this->error('当前任务未完成请稍后。');
        } else {
            $filepath = Env::get('file.UPLOAD_FTP_DOWNLOAD') . 'output_phone/';
            $filename = $ids.'.txt';
            $fetchList['file_path'] = str_replace(strrchr($fetchList['file_path'], "."), ".txt", $fetchList['file_path']);
            $txtfile = urlencode($fetchList['file_path']);
            //告诉浏览器这是一个文件流格式的文件
            //Header ( "Content-type: application/octet-stream" );
            Header ( "Content-type: application/octet-stream" );
            //请求范围的度量单位
            Header ( "Accept-Ranges: bytes" );
            //Content-Length是指定包含于请求或响应中数据的字节长度
            Header ( "Accept-Length: " . filesize ( $filepath.$filename ) );
            //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
            Header ( "Content-Disposition: attachment; filename=" .$txtfile );
            //读取文件内容并直接输出到浏览器
            $fp = fopen($filepath.$filename, 'rb');
            // 设置指针位置
            fseek($fp, 0);

            // 开启缓冲区
            ob_start();
            // 分段读取文件
            while (!feof($fp)) {
                $chunk_size = 1024 * 1024 * 25; // 50M
                echo fread($fp, $chunk_size);
                ob_flush(); // 刷新PHP缓冲区到Web服务器
                flush(); // 刷新Web服务器缓冲区到浏览器
                sleep(1); // 每1秒 下载 50M
            }
            // 关闭缓冲区
            ob_end_clean();

            fclose($fp);
            exit();
        }
    }
}
