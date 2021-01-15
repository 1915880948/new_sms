<?php

namespace app\admin\controller\modeling;

use app\admin\controller\data_in\TaskSourceDetail;
use app\common\controller\Backend;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Log;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class ModelingUrl extends Backend
{
    
    /**
     * ModelingUrl模型对象
     * @var \app\admin\model\modeling\ModelingUrl
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\modeling\ModelingUrl;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function import()
    {
        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
//            print_r($params);die;

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
            $modelingBaseModel = new \app\admin\model\modeling\ModelingBase();
            $modelingBase = $modelingBaseModel->where(['id'=>$params['source_no_id']])->find();
            $modelingUrlMaxId = $this->model->where(['source_no'=>$modelingBase['source_no']])->max('source_id');
            if( !$modelingUrlMaxId ) $modelingUrlMaxId = 0 ;
            //print_r( $modelingUrlMaxId );die;
            $insert = [];
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            //$allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                $i = 1;
                $url = trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue());
                if( !$url ) continue;
                if (substr($url,0,4) != "http"){
                    $url = "http://".$url;
                }
                $values = [
                    'url'          => $url,
                    'host'         => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'path'         => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'key'          => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'name'         => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'category'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'industry'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'is_valid'     => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'leader'       => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'time'         => trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue()),
                    'source_no_id' => $params['source_no_id'],
                    'source_name'  => $modelingBase['name'],
                    'source_no'    => $modelingBase['source_no'],
                    'creator'      => $this->auth->getUserInfo()['username'],
                    'create_time'  => date('Y-m-d H:i:s'),
                ];
                $values['time'] = gmdate('Y-m-d H:i:s',intval(($values['time'] - 25569) * 3600 * 24));
                $links = parse_url($url);
                if( $values['host'] ){ //如果HOST不为空，使用HOST、PATH、KEY在指定行业下进行精准匹配，如果匹配到了，则URL已存在，不做处理；如果未匹配到，存下这条URL，并按照建模来源编号自增
                    $urlCount = $this->model->where(['host'=>$values['host'],'path'=>$values['path'],'key'=>$values['key']])->count();
                    if( $urlCount ) continue;
                }else{ //如果HOST为空，则把完整的URL分成HOST、PATH、KEY在指定建模来源下进行匹配，如果匹配到了，则URL已存在；如果未匹配到，存下这条URL，并按照建模来源编号自增
                    $where = [];
                    if( isset($links['host']) ) $where['host'] = $links['host'];
                    if( isset($links['path']) ) $where['path'] = $links['path'];
                    if( isset($links['query']) ) $where['key'] = $links['query'];
                    $urlCount = $this->model->where($where)->count();
                    if( $urlCount ) continue;
                }

                $modelingUrlMaxId++;
                $values['url_no'] = $modelingBase['source_no'].str_pad($modelingUrlMaxId,4,0,STR_PAD_LEFT);
                $values['root'] = $this->get_host_to_root($links['host']);
                $insert[] = $values;
            }
            if( empty($insert) ){
                $this->error('过滤后，没有需要新增的URL');
            }
            $result = $this->model->saveAll($insert);
            if( !$result ){
                $this->error('导入失败！！');
            }
            $this->success();
        }
        $modelBaseModel = new \app\admin\model\modeling\ModelingBase();
        $sourceArr = $modelBaseModel->column('id,name');
        if( !$this->auth->isSuperAdmin() ){
            $sourceArr = $modelBaseModel->where("creators like '%{$this->auth->getUserInfo()['username']}%'")->column('id,name');
        }
        $this->view->assign("sourceArr", $sourceArr);
        return  $this->view->fetch();
    }

    public function confirm(){
        $modelBaseModel = new \app\admin\model\modeling\ModelingBase();
        $sourceArr = $modelBaseModel->column('id,name');
        if( !$this->auth->isSuperAdmin() ){
            $sourceArr = $modelBaseModel->where("creators like '%{$this->auth->getUserInfo()['username']}%'")->column('id,name');
        }
        $sourceArr['999'] = '全部';
        krsort($sourceArr);
//        print_r($sourceArr);die;
        $this->view->assign("sourceArr", $sourceArr);
        return  $this->view->fetch();
    }

    public function export($source_no_id)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $where = [];
        if( $source_no_id != 999 ){
            $where['source_no_id'] = $source_no_id;
        }
        $totalCount = $this->model->where($where)->count();
        if( $totalCount <=0 ){
            return '暂时无数据~~';
            $this->error('暂时无数据~~','');
        }
        ob_end_clean();//清除缓冲区,避免乱码
        //定义输出的文件名
        $fileName = 'url_table_' . date('YmdHis') . '.csv';
        //发送下载文件头信息
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $columns = ['url编号', 'URL', 'host', 'path', 'key', '名称', '类型', '行业', '是否有效', '负责人', '时间'];
        $fp = fopen('php://output', 'a');//打开output流
        mb_convert_variables('GBK', 'UTF-8', $columns);
        fputcsv($fp, $columns);//将数据格式化为CSV格式并写入到output流中

        //刷新输出缓冲到浏览器，必须同时使用这两函数来刷新输出缓冲。
//        ob_flush();
//        flush();

        $r = 10000; //每次最多取1万
        $pages = ceil($totalCount / $r);
        for ($page = 1; $page <= $pages; $page++) {
            $data = $this->model->where($where)->page($page, $r)->order("id DESC")->select();
            foreach ($data as $item) {
                $rowData = [
                    $item['url_no'],
                    $item['url'],
                    $item['host'],
                    $item['path'],
                    $item['key'],
                    $item['name'],
                    $item['category'],
                    $item['industry'],
                    $item['is_valid'],
                    $item['leader'],
                    $item['time'],
                ];
                mb_convert_variables('GBK', 'UTF-8', $rowData);
                fputcsv($fp, $rowData);
//                ob_flush();
//                flush();
            }
        }
        fclose($fp);
    }

    public function match(){

        if( $this->request->isPost() ){
            $params = $this->request->post('row/a');
            if( !$params['file_path'] ){
//                $this->error('请上传文件或无权限');
            }
            //实例化reader
            $ext = pathinfo($params['file_path'], PATHINFO_EXTENSION);
            if( $ext === 'csv'){
                $reader = new Csv();
            }elseif ($ext === 'xls') {
                $reader = new Xls();
            } else {
                $reader = new Xlsx();
            }
            //$reader->setInputEncoding('GBK');
            if (!$PHPExcel = $reader->load($params['file_path'])) {
                $this->error(__('Unknown data format'));
            }

            $insert = [];
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                $i = 1;
                $urlL=$url = trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue());
                $host = trim($currentSheet->getCellByColumnAndRow($i++, $currentRow)->getValue());
                if (empty($url)) {
                    break;
                }
                if (substr($url,0,4) != "http"){
                    $urlL = "http://".$url;
                }
                $where = [];
                if( $host ){ //如果HOST不为空，则按照URL拆分HOST、PATH、KEY匹配
                    $links = parse_url($urlL);
                    if( isset($links['host']) ) $where['host'] = $links['host'];
                    if( isset($links['path']) ) $where['path'] = $links['path'];
                    if( isset($links['query']) ) $where['key'] = $links['query'];
                }else{//如果HOST为空，Host path key为空则按照URL匹配
                    $where['url'] = $url;
                }
                $urls = $this->model->where($where)->select();
                if( empty($urls) ){
                    $urls[0]['url'] = $url;
                }
                foreach ( $urls as $item ){
                    $values = [
                        'url'         => $url,
                        'url_no'      => $item['url_no'],
                        'host'        => $item['host'],
                        'path'        => $item['path'],
                        'key'         => $item['key'],
                        'name'        => $item['name'],
                        'category'    => $item['category'],
                        'industry'    => $item['industry'],
                        'is_valid'    => $item['is_valid'],
                        'leader'      => $item['leader'],
                        'time'        => $item['time'],
                        'creator'     => $this->auth->getUserInfo()['username'],
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                    $insert[] = $values;
                }
            }
            ob_end_clean();//清除缓冲区,避免乱码

            $filename = 'modeling_table_' . date('YmdHis');
            $headers = ['url编号','URL','host','path','key','名称', '类型', '行业', '是否有效', '负责人','时间'];

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headerColumns = \PhpOffice\PhpSpreadsheet\Writer\Xls::instance()
                ->generateColumn(count($headers));
            foreach($headerColumns as $k => $item){
                $column = "{$item}1";
                $sheet->setCellValue($column, (string) $headers[$k]);
            }
            $row = 1;
            foreach($insert as $key => $item) {
                $xml = [
                    $item['url'],
                    $item['url_no'],
                    $item['host'],
                    $item['path'],
                    $item['key'],
                    $item['name'],
                    $item['category'],
                    $item['industry'],
                    $item['is_valid'],
                    $item['leader'],
                    $item['time'] . "\t",
                ];
                foreach ($headerColumns as $k => $headerColumn) {
                    $column = $headerColumn . ($row + 1);
                    $sheet->setCellValue($column, (string)$xml[$k]);
                }
                $row++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
        return $this->view->fetch();
    }


    public function get_host_to_root($host){
        //按照":"截取防止端口
        //ipv4正则表达式
        $pattern = '/^(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)$/';
        if (!empty($host)){
            $hosts = explode(":",$host);
            $strs = explode(".",$hosts[0]);
            $len = count($strs);
            if (preg_match($pattern,$hosts[0])){
                $root = $hosts[0];
            }elseif ($strs[$len-1] == "cn" && $strs[$len-2] == "com"){
                $root = $strs[$len-3].".com.cn";
            }elseif ($strs[$len-1] == "cn"){
                $root = $strs[$len-2].".cn";
            }elseif ($strs[$len-1] == "com"){
                $root = $strs[$len-2].".com";
            }elseif ($len == 1){
                $root = $host[0];
            }else{
                $root = $strs[$len-2].".".$strs[$len-1];
            }
        }else{
            $root = $host;
        }
        return $root;
    }
}
