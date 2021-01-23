<?php

namespace app\admin\controller\access;

use app\admin\model\access\basic\InfoBankArea;
use app\admin\model\basic\Company;
use app\admin\model\basic2\FilterBlack;
use app\admin\model\basic2\ProvinceCityCode;
use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Config;
use think\Env;
use think\Log;

/**
 * 取数任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskFetch extends Backend
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
    //全局黑名单
    private $blackAllArr = [
        1 => '全局黑名单',
        17=> '回T黑名单',
        11 => '金融所有银行黑名单',
    ];
    //分开业务黑名单库
    private $blackNewArr = [
        2 => '游戏黑名单',
        10 => '保险黑名单',
        18 => '恒丰银行黑名单',
        19 => '新广发黑名单',
        21 => '保险通道黑名单',
    ];
    //疑似库
    private $distrustArr = [
        3 => '中信疑似库',
        4 => '兴业疑似库',
        5 => '民生疑似库',
        6 => '平安疑似库',
        7 => '广发疑似库',
        16=> '民生疑似库小',
    ];
    //敏感库
    private $sensitiveArr = [
        8 => 'u2传奇不敏感用户',
        9 => 'TY+TL不敏感库',
        12 => '多次发送不点击用户',
        13 => '保险不敏感库',
        14 => '信用卡大于等于2',
        15 => '信用卡大于等于3',
        20 => 'CCI投保用户',
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

    // 数据出库--标签
    public function add()
    {
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
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

    // 根据标签编号导入
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
            $labelModel = new \app\admin\model\access\Lable();
            $insert = [];
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $batchRowError = [];
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                $i = 1;
                $values = [
                    'remark'      => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'carrier'     => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'batchs'      => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'url_nos'     => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'min_num'     => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'max_num'     => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
                    'region'      => $currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue(),
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
                $urlCount = $labelModel->where(['code' => ['in',explode('|', $values['url_nos'])]])->count();
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

    // 打包下载
    public function downloadBatch($ids){
        if( !$ids ){
            $this->error('缺少参数：ids');
        }
        $rows = $this->model->where(['id'=>['in',$ids]])->select();
        foreach ( $rows as $item){
            if( $item['status'] < 3){
                $this->error('未出库完毕，ID:'.$item['id']);
            }
        }
        $zipname = 'result-' . str_replace(',','-',$ids).'-'.time() . '.zip'; //最终生成的文件名
        $filepath = Env::get('file.UPLOAD_BIG_DOWNLOAD') . 'hu_output/' . $zipname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        //重新生成文件
        $zip = new \ZipArchive();

        if ($zip->open($filepath, \ZipArchive::CREATE) === TRUE) {
            //print_r($zip);die;
            foreach ($rows as $val) {
                if (file_exists(Env::get('file.UPLOAD_BIG_DOWNLOAD') . 'tian_output/models' . $val['id'] . '.txt')) {
                    $zip->addFile(Env::get('file.UPLOAD_BIG_DOWNLOAD') . 'tian_output/models' . $val['id'] . '.txt', $val['remark'] . "-" . $val['total_num'] . '条.txt');
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

    // 二次处理
    public function deal2($ids){

        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            $rows = $this->model->where(['id'=>['in',$params['ids']]])->select();
            $blackNames = [];
            $black = implode(",", $params['black']);
            if (!empty($black)) {
                foreach ($params['black'] as $v) {
                    $blackNames[] = $v . "/*";
                    $params['black'] = $black;
                }
            }
            $all_black = implode(",", $params['all_black']);
            if (!empty($all_black)) {
                foreach ($params['all_black'] as $v) {
                    $blackNames[] = $v . "/*";
                    $params['all_black'] = $all_black;
                }
            }
            $distrust = implode(",", $params['distrust']);
            if (!empty($distrust)) {
                foreach ($params['distrust'] as $v) {
                    $blackNames[] = $v . "/*";
                    $params['distrust'] = $distrust;
                }
            }
            $sensitive = implode(",", $params['sensitive']);
            if (!empty($sensitive)) {
                foreach ($params['sensitive'] as $v) {
                    $blackNames[] = $v . "/*";
                }
                $params['sensitive'] = $sensitive;
            }

            if ( !empty($params['filter_history_ids']) ) {
                $blackName = explode(',', $params['filter_history_ids']);
                foreach ($blackName as $v) {
                    $blackName_n[] = "b/" . $v;
                }
                $blackNames[] = implode(',', $blackName_n);
            }
            $params['black_name'] = implode(',', $blackNames);
            if (empty($params['black_name'])) { //没有选择就选默认无用的
                $params['black_name'] = "0/*";
            }
            $params['bank_city_codes'] = "全国";
            if( !empty($params['region']) ){
                $provinceCityCodeModel = new ProvinceCityCode();
                $bank_city_codes = $provinceCityCodeModel->where(['city'=>['in',str_replace('|',',',$params['region'])]])->column('city_code');
                $params['bank_city_codes'] = implode('|', $bank_city_codes);
            }
            $params['status'] = 1;
            //$params['bank'] = 0;
            $params['creator'] =  $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('Y-m-d H:i:s');
            unset($params['ids']);
            unset($params['filter_history_ids']);
            foreach ($rows as $item){
                if( $item['status'] <3 )  continue;
                $filterBlackModel = new FilterBlack();
                $params['num'] = $item['total_num'];
                $params['output_pid_task_id'] = $item['id'];
                $rs = $filterBlackModel->insertGetId($params);
                //Log::log('$filterBlackModel----->id:'.$rs);
                $abc = copy(Env::get('file.UPLOAD_BIG_DOWNLOAD') . "tian_output/models" . $item['id'] . ".txt", Env::get('file.UPLOAD_BLACK_DOWNLOAD') . "need_filter_file/" . $rs);
                $filterBlackModel->save(array('file_name' => $rs),['id'=>$rs]);
                $taskFetachModel = new \app\admin\model\access\TaskFetch();
                $taskFetachModel->save(array('status' => 4),['id'=>$item['id']]);
            }
            $this->success('二次处理成功');
        }

        //取有覆盖地域的银行数据
        $bankAreasList = (new InfoBankArea())->where(['status'=>1])->field('distinct bank_id, bank_name')->select();
        $bankAreasList = array_combine(array_column($bankAreasList,'bank_id'),array_column($bankAreasList,'bank_name'));
        //array_unshift($bankAreasList,'请选择');


        $this->assign('ids', $ids);
        $this->view->assign("bankAreasList", $bankAreasList);

        $this->assign('blackAllArr', $this->blackAllArr);
        $this->assign('blackNewArr', $this->blackNewArr);
        $this->assign('distrustArr', $this->distrustArr);
        $this->assign('sensitiveArr', $this->sensitiveArr);
        return $this->view->fetch();
    }

    // 二次处理下载
    public function downloadBatch2($ids){
        if (empty($ids)) {
            $this->error('请选择需要批量下载的任务');
        }

        $rows =  $this->model->where(['id'=>['in',$ids]])->select();
        foreach ($rows as $data) {
            if ($data['status'] != 5) {
                $this->error('请确认所选择的任务已全部二次出库完毕');
            }
        }
        $zipname = 'result-' .str_replace(',','-',$ids).'-'. time() . '.zip'; //最终生成的文件名
        $filepath = Env::get('file.UPLOAD_BIG_DOWNLOAD') . 'hu_output/' . $zipname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        //重新生成文件
        $zip = new \ZipArchive();
        if ($zip->open($filepath, \ZipArchive::CREATE) !== TRUE) {
            $this->error('无法打开文件，或者文件创建失败');
        }
        $filterBlackModel = new FilterBlack();
        foreach ($rows as $val) {
            $black_id = $filterBlackModel->where("output_pid_task_id",$val['id'])->order('id','desc')->value('id');
            if (file_exists(Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/' . $black_id . '.txt')) {
                $zip->addFile(Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/' . $black_id . '.txt', $val['remark'] . "-" . $val['total_num'] . "-" . $val['two_total_num'] . '.txt');
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
}
