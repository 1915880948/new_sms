<?php

namespace app\admin\controller\access;

use app\admin\model\access\basic\InfoBankArea;
use app\admin\model\basic\Company;
use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Config;
use think\Env;

/**
 * 取数任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskFetch2 extends Backend
{
    protected $relationSearch = true;
    /**
     * TaskFetch模型对象
     * @var \app\admin\model\access\TaskFetch
     */
    protected $model = null;
    protected $blackList = [
        1  => '全局黑名单',
        2  => '游戏黑名单',
        3  => '中信疑似库',
        4  => '兴业疑似库',
        5  => '民生疑似库',
        6  => '平安疑似库',
        7  => '广发疑似库',
        8  => 'u2传奇不敏感用户',
        9  => 'TY+TL不敏感库',
        10 => '保险黑名单',
        11 => '金融所有银行黑名单',
        12 => '多次发送不点击用户',
        13 => '保险不敏感库',
        14 => '信用卡大于等于2',
        15 => '信用卡大于等于3',
        16 => '民生疑似库小',
        17 => '回T黑名单',
        18 => '恒丰银行黑名单',
        19 => '新广发黑名单',
        20 => 'CCI投保用户',
        21 => '保险通道黑名单',
        22 => '电信游戏不敏感库',
        23 => '回Y黑名单',
    ];
    protected $statusList = [
        1 => '提交',
        2 => '出库中',
        3 => '出库完毕',
        4 => '二次处理中',
        5 => '二次处理完毕',
    ];
    protected $outTypeList = [
        0 => '只有密文',
        1 => '云海出数形式',
        2 => 'AI出数形式',
    ];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\access\TaskFetch;
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    // 数据出库
    public function add()
    {
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            $params['region'] = empty($params['region'])? "全国": $params['region'];
            $result = $this->model->allowField(true)->save($params);
            if( $result ){
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        //$bankareas = $db->table('sms_info_bank_area')->where(['status' => 1])->field('distinct bank_id, bank_name')->select();
        $bankAreasList = (new InfoBankArea())->where(['status'=>1])->field('distinct bank_id, bank_name')->select();
        $bankAreasList = array_combine(array_column($bankAreasList,'bank_id'),array_column($bankAreasList,'bank_name'));
        array_unshift($bankAreasList,'请选择');

        $modList = (new \app\admin\model\access\ModSource())->column('nickname');
        $this->view->assign("bankAreasList", $bankAreasList);
        $this->view->assign("modList", array_combine($modList,$modList));
        $this->view->assign("blackList", $this->blackList);
        $this->view->assign("outTypeList", $this->outTypeList);
        return  $this->view->fetch();
    }

    // 暂时不用
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $this->view->assign("row", $row);
        $this->view->assign("blackList", $this->blackList);
        $this->view->assign("statusList", $this->statusList);
        return $this->view->fetch();
    }
    // 暂时不用
    public function detail($ids=null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $modList = (new \app\admin\model\access\ModSource())->column('nickname');
        $this->view->assign("testList", array_combine($modList,$modList));
        $this->view->assign("row", $row);
        $this->view->assign("blackList", $this->blackList);
        $this->view->assign("statusList", $this->statusList);
        $this->view->assign("outTypeList", $this->outTypeList);
        return $this->view->fetch();
    }
    // 暂时不用
    public function download($ids=null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($row['status'] != 3) {
            $this->error('当前出库未完成请稍后。');
        }
        set_time_limit(0);
        $filepath = Env::get('file.UPLOAD_BIG_DOWNLOAD') . 'tian_output/';
        $filename = 'models' . $ids . '.txt';
        $txtfile = urlencode($row['remark'] . "-" . $row['total_num'] . "条.txt");
        $file = fopen($filepath . $filename, "rb");

        //告诉浏览器这是一个文件流格式的文件
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filepath . $filename));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $txtfile);

        //读取文件内容并直接输出到浏览器
        /*echo fread ( $file, filesize ( $filepath.$filename ) );
        fclose ( $file );
        exit ();*/
        $fp = fopen($filepath . $filename, 'rb');
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

    }

    // 根据URL模型编号导入
    public function import()
    {
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            //print_r($params);die;

            if (!is_file($params['file_path'])) {
                $this->error(__('No results were found'));
            }
            //实例化reader
            $ext = pathinfo($params['file_path'], PATHINFO_EXTENSION);
            if ($ext === 'xls') {
                $reader = new Xls();
            } else {
                $reader = new Xlsx();
            }

            if (!$PHPExcel = $reader->load($params['file_path'])) {
                $this->error(__('Unknown data format'));
            }
            $taskSourceModel = new \app\admin\model\data_in\TaskSource();
            $taskSourceDetailModel = new \app\admin\model\data_in\TaskSourceDetail();
            $insert = [];
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $batchRowError = [];
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                $i = 1;
                $region = trim($currentSheet->getCellByColumnAndRow(7, $currentRow)->getValue());
                if (empty($region)) $region = "全国";
                $values = [
                    'remark'      => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'carrier'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'batchs'      => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'url_nos'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'min_num'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'max_num'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'region'      => $region,
                    'black'       => $params['black'],
                    'out_type'    => $params['out_type'],
                    'status'      => 1,
                    'creator'     => $this->auth->getUserInfo()['username'],
                    'create_time' => date('Y-m-d H:i:s'),
                ];

                //判断批次号是否全部存在
                $batchCount = count(explode('|', $values['batchs']));
                $batchCountDb = $taskSourceModel->where(['task_id'=>['in',explode('|', $values['batchs'])]])->count();
                if ($batchCountDb != $batchCount){
                    $batchRowError[] = $currentRow;
                    continue;
                }
                $urlCount = $taskSourceDetailModel->where([
                    'source_task_id' => ['in',explode('|', $values['batchs'])],
                    'url_no' => ['in',explode('|', $values['url_nos'])]
                ])->count();
                if( !$urlCount ){
                    continue;
                }
                $insert[] = $values;
            }
            $result = $this->model->saveAll($insert);
            if( !$result ){
                $this->error('导入失败！！');
            }
            if( count($batchRowError) >0 ){
                $this->success('以下行导入失败，由于批次号不全存在：'.implode(',',$batchRowError));
            }
            $this->success();
        }

        $this->view->assign("blackList", $this->blackList);
        $this->view->assign("outTypeList", $this->outTypeList);
        return  $this->view->fetch();
    }

}
