<?php

namespace app\admin\controller\data_deal;

use app\admin\model\access\basic\InfoBankArea;
use app\admin\model\basic2\ProvinceCityCode;
use app\common\controller\Backend;
use think\Env;

/**
 * 过滤管理
 *
 * @icon fa fa-circle-o
 */
class FilterBlack extends Backend
{
    protected $relationSearch = true;
    /**
     * FilterBlack模型对象
     * @var \app\admin\model\data_deal\FilterBlack
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
        $this->model = new \app\admin\model\data_deal\FilterBlack;

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
            $params = $this->request->get();
            $myWhere['status'] = ['<>',5];
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            if( !(isset($params['is_filter']) && $params['is_filter']==1)){
                $myWhere['output_pid_task_id'] = 0;
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)->where($myWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    // 二次处理
    public function add(){
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            if( !$params['file_path']){
                $this->error('文件必须上传！');
            }
            $blackNames = [];
            $data['black'] = implode(",", $params['black']);
            if (!empty($data['black'])) {
                foreach ($params['black'] as $v) {
                    $blackNames[] = $v . "/*";
                }
            }
            $data['all_black'] = implode(",", $params['all_black']);
            if (!empty($data['all_black'])) {
                foreach ($params['all_black'] as $v) {
                    $blackNames[] = $v . "/*";
                }
            }
            $data['distrust'] = implode(",", $params['distrust']);
            if (!empty($data['distrust'])) {
                foreach ($params['distrust'] as $v) {
                    $blackNames[] = $v . "/*";
                }
            }
            $data['sensitive'] = implode(",", $params['sensitive']);
            if (!empty($data['sensitive'])) {
                foreach ($params['sensitive'] as $v) {
                    $blackNames[] = $v . "/*";
                }
            }
            if ( !empty($params['filter_history_ids']) ) {
                $blackName = explode(',', $params['filter_history_ids']);
                foreach ($blackName as $v) {
                    $blackName_n[] = "b/" . $v;
                }
                $blackNames[] = implode(',', $blackName_n);
            }
            $data['black_name'] = implode(',', $blackNames);
            if (empty($data['black_name'])) { //没有选择就选默认无用的
                $data['black_name'] = "0/*";
            }
            $data['bank_city_codes'] = "全国";
            if( !empty($params['region']) ){
                $provinceCityCodeModel = new ProvinceCityCode();
                $bank_city_codes = $provinceCityCodeModel->where(['city'=>['in',str_replace('|',',',$params['region'])]])->column('city_code');
                $data['bank_city_codes'] = implode('|', $bank_city_codes);
            }
            $data['status'] = 1;
            $data['region'] = $params['region'];
            $data['creator'] =  $this->auth->getUserInfo()['username'];
            $data['create_time'] = date('Y-m-d H:i:s');
            unset($params['filter_history_ids']);
            $files = $file_paths = [];
            $files_list = rtrim($params['files_list'],"|");
            $files_list = explode('|', $files_list);
            foreach ($files_list as $file_list) {
                $filelists = explode(",",$file_list);
                $file_name = trim($filelists[0]);
                $file_path = $filelists[1];
                if ($file_path) {
                    $handle = fopen($file_path,"r");//以只读方式打开一个文件
                    $k = 0;
                    while(!feof($handle)){
                        if(fgets($handle)){
                            $k++;
                        };
                    }
                    fclose($handle);
                    $total_nums[] = $k;
                    $files[] = $file_path;
                    $file_names[] = $file_name;
                }
            }
            for ($i=0;$i<count($file_names);$i++){
                $filterBlackModel = new \app\admin\model\data_deal\FilterBlack();
                $data['num'] = $total_nums[$i];
                $data['source_name'] = $file_names[$i];
                $rs = $filterBlackModel->insertGetId($data);
                $lastPath = Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'need_filter_file';
                if (!is_dir($lastPath)) {
                    @mkdir($lastPath);
                }
                $lastFile = $lastPath . '/' . $rs;
                $abc = copy($files[$i], $lastFile);
                //Log::log('$filterBlackModel----->id:'.$rs);
                $filterBlackModel->save(array('file_name' => $rs),['id'=>$rs]);
            }
            $this->success('数据入库成功');
        }

        //取有覆盖地域的银行数据
        $bankAreasList = (new InfoBankArea())->where(['status'=>1])->field('distinct bank_id, bank_name')->select();
        $bankAreasList = array_combine(array_column($bankAreasList,'bank_id'),array_column($bankAreasList,'bank_name'));
        //array_unshift($bankAreasList,'请选择');

        $this->view->assign("bankAreasList", $bankAreasList);

        $this->assign('blackAllArr', $this->blackAllArr);
        $this->assign('blackNewArr', $this->blackNewArr);
        $this->assign('distrustArr', $this->distrustArr);
        $this->assign('sensitiveArr', $this->sensitiveArr);
        return $this->view->fetch();
    }

    //下载
    public function download($ids){

        $where = 'id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if ($fetchList['status'] != 3) {
            $this->error('当前任务未完成请稍后。');
        } else {
            $filepath = Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/';
            $filename = $ids.'.txt';
            $outfile = explode('.',$fetchList['source_name']);
            $txtfile = urlencode($outfile[0]."-".$fetchList['total_num']."条.txt");
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
        $filepath = Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/' . $zipname;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        //重新生成文件
        $zip = new \ZipArchive();

        if ($zip->open($filepath, \ZipArchive::CREATE) === TRUE) {
            //print_r($zip);die;
            foreach ($rows as $val) {
                if (file_exists(Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/' . $val['id'] . '.txt')) {
                    $outfile = explode('.',$val['source_name']);
                    $txtfile = $outfile[0]."-".$val['total_num']."条.txt";
                    $zip->addFile(Env::get('file.UPLOAD_BLACK_DOWNLOAD') . 'result/' . $val['id'] . '.txt', $txtfile);
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
