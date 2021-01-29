<?php

namespace app\admin\controller\modeling;

use app\common\controller\Backend;
use app\common\model\Attachment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Db;
use think\Env;

/**
 * U2提交取数和粗筛管理
 *
 * @icon fa fa-circle-o
 */
class U2SubmitTask extends Backend
{
    
    /**
     * U2SubmitTask模型对象
     * @var \app\admin\model\modeling\U2SubmitTask
     */
    protected $model = null;

    protected $source_no_arr = [
        1=>'U2',2=>'TL',3=>'DPI',4=>'TY',5=>'TS',6=>'TD',7=>'TW',
    ];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\modeling\U2SubmitTask;

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
            $myWhere = [];
            if( isset($params['source_no']) ){
                $myWhere['source_no'] = $params['source_no'];
            }
            if( isset($params['type']) ){
                $myWhere['type'] = $params['type'];
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

    public function template($source_no){

        $insert = [];
        $filename = 'submit_'.$this->source_no_arr[$source_no].'_template.xlsx';
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("sms")
            ->setLastModifiedBy("sms")
            ->setTitle("提交取数")
            ->setSubject("sms");
        $worksheet = $spreadsheet->getActiveSheet();     //指向激活的工作表
        $worksheet->setTitle('提交取数模板');
        $i= 1;
        if($source_no == 1 ){ // U2
            $worksheet->setCellValue(('A'.$i), 'URL编号');
            $worksheet->setCellValue(('B'.$i), '最小次数');
            $worksheet->setCellValue(('C'.$i), '最大次数');
            $worksheet->setCellValue(('D'.$i), '取数天数（最大15天）');
            $worksheet->setCellValue(('E'.$i), '取数地域');
            $worksheet->setCellValue(('E'.($i+1)), '1|2|3');
            $worksheet->setCellValue(('F'.$i), '手机编号');
            $worksheet->setCellValue(('F'.($i+1)), '1（）');
            $worksheet->setCellValue(('G'.$i), '去重天数');
            $worksheet->setCellValue(('G'.($i+1)), '1（取最近1天的数据）');
            $worksheet->setCellValue(('H'.$i), '去重方式');
            $worksheet->setCellValue(('H'.($i+1)), '1（标签去重）');
            $worksheet->setCellValue(('H'.($i+2)), '2（行业去重）');
            $worksheet->setCellValue(('I'.$i), '取数限制');
            $worksheet->setCellValue(('I'.($i+1)), '10000（代表取1万数据）');
            $worksheet->setCellValue(('J'.$i), 'UV输出');
            $worksheet->setCellValue(('J'.($i+1)), '都填1');
            $worksheet->setCellValue(('K'.$i), '行业标签');
            $worksheet->setCellValue(('K'.($i+1)), 'YX（游戏）');
            $worksheet->setCellValue(('K'.($i+2)), 'JR（金融）');
            $worksheet->setCellValue(('K'.($i+3)), 'BX（保险）');
            $worksheet->setCellValue(('K'.($i+4)), 'SFC（顺风车）');
            $worksheet->setCellValue(('K'.($i+5)), 'YH（云海）');
            $worksheet->setCellValue(('K'.($i+6)), 'HL（火狼）');
        }
        if($source_no == 2 ){ // TL
            $i= 1;
            $insert = [
                ['1','2','3','4','5','6','7','8','9'],
                ['other001','BXB1802','1','10000','1','0999','0','20000','0'],
                ['other001','BXB1801','1','10000','1','0999','0','20000','0'],
                ['other001','BXB1800','1','10000','1','0999','0','20000','0'],
                ['BXGTL_1','Z18869','1','10000','1','0999','0','30000','0'],
                ['BXGTL_1','Z18866','1','10000','1','0999','0','30000','0'],
                ['BXGTL_1','Z18865','1','10000','1','0999','0','30000','0'],
            ];
            foreach($insert as $key => $item) {
                $worksheet->setCellValue(('A'.$i), $item[0]);
                $worksheet->setCellValue(('B'.$i), $item[1]);
                $worksheet->setCellValue(('C'.$i), $item[2]);
                $worksheet->setCellValue(('D'.$i), $item[3]);
                $worksheet->setCellValue(('E'.$i), $item[4]);
                $worksheet->setCellValue(('F'.$i), $item[5]);
                $worksheet->setCellValue(('G'.$i), $item[6]);
                $worksheet->setCellValue(('H'.$i), $item[7]);
                $worksheet->setCellValue(('I'.$i), $item[8]);
                $i++;
            }
        }
        if($source_no == 5 ){ // TS
            $i= 1;
            $insert = [
                ['模型','编号'],
                ['BXB1802','Z12930'],
                ['BXB1801','Z12931'],
                ['BXB1800','Z12932'],
                ['BXB1798','Z12934'],
            ];
            foreach($insert as $key => $item) {
                $worksheet->setCellValue(('A'.$i), $item[0]);
                $worksheet->setCellValue(('B'.$i), $item[1]);
                $i++;
            }
        }
        if($source_no == 6 ){ // TD
            $i= 1;
            $insert = [
                ['模型','编号'],
                ['BXB1802','Z12930'],
                ['BXB1801','Z12931'],
                ['BXB1800','Z12932'],
                ['BXB1798','Z12934'],
            ];
            foreach($insert as $key => $item) {
                $worksheet->setCellValue(('A'.$i), $item[0]);
                $worksheet->setCellValue(('B'.$i), $item[1]);
                $i++;
            }
        }
        if($source_no == 7 ){ // TW
            $i= 1;
            $insert = [
                ['模型','编号'],
                ['BXB1802','Z12930'],
                ['BXB1801','Z12931'],
                ['BXB1800','Z12932'],
                ['BXB1798','Z12934'],
            ];
            foreach($insert as $key => $item) {
                $worksheet->setCellValue(('A'.$i), $item[0]);
                $worksheet->setCellValue(('B'.$i), $item[1]);
                $i++;
            }
        }

        //下载文档
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;

    }

    public function import()
    {
        $params =  $this->request->post();
        //print_r( $params );
        $attachmentModel = new Attachment(); // Env::get('file.FILE_ROOT_DIR').
        $row = $attachmentModel->where('url',$params['file'])->value('extparam');
        $row = json_decode($row,true);
        $data['type'] = 0;
        $data['file_name'] = $row['name'];
        $data['file_path'] = $params['file'];
        $data['source_no'] = $params['source_no'];
        $data['creator'] = $this->auth->getUserInfo()['username'];
        $data['create_time'] = date('YmdHis');

        $unm = $this->model->where(['creator'=>['=',$data['creator']],'source_no'=>['=',$params['source_no']]])->count();
        if( $unm ){
            $this->error('库中已有取数文件，请删除后重新提交');
        }
        $result = $this->model->save($data);
        if( !$result ){
            $this->error('提交失败');
        }

        //实例化reader
        $ext = pathinfo($params['file'], PATHINFO_EXTENSION);
        if ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }
        if (!$PHPExcel = $reader->load($params['file'])) {
            $this->error(__('Unknown data format'));
        }
        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
        $content = [];
        for( $i=2; $i<= $allRow ; $i++){
            $rowCon = [];
            for( $j=1; $j<=$maxColumnNumber; $j++ ){
                $rowCon[] = trim($currentSheet->getCellByColumnAndRow($j, $i)->getValue());
            }
            $content[] = $rowCon;
        }
        //$content = array_unique($content);
        if( $params['source_no'] == 1){ // U2

        }
        if( in_array($params['source_no'], [2,5,6,7])){ // TL
            foreach ($content as $item){
                $match = substr($item[0],0,2);
                $addData['source_no'] = $item[0];
                switch ($match){
                    case 'JR':
                        $addData['industry'] = '金融';
                        $addData['class'] = '金融事业部';
                        break;
                    case 'SF':
                        $addData['industry'] = "顺风车";
                        $addData['class'] = "金融事业部";
                        break;
                    case 'JY':
                        $addData['industry'] = "教育";
                        $addData['class'] = "金融事业部";
                        break;
                    case 'DJ':
                        $addData['industry'] = "营销点击";
                        $addData['class'] = "互联网事业部";
                        break;
                    case 'YX':
                        $addData['industry'] = "游戏";
                        $addData['class'] = "互联网事业部";
                        break;
                    case 'BX':
                        $addData['industry'] = "保险";
                        $addData['class'] = "互联网事业部";
                        break;
                    case 'YH':
                        $addData['industry'] = "云海";
                        $addData['class'] = "互联网事业部";
                        break;
                    case 'HL':
                        $addData['industry'] = "火狼";
                        $addData['class'] = "互联网事业部";
                        break;
                    default:
                        $addData['industry'] = "其他";
                        $addData['class'] = "互联网事业部";
                }
                $result = Db::table('sms_center_new.sms_dpi_industry')->insert($addData);
            }
        }
        if( $params['source_no'] == 5){ // TS

        }
        if( $params['source_no'] == 6){ // TD

        }
        if( $params['source_no'] == 7){ // TW

        }



        $this->success('批量提交成功');


    }

    public function download($ids=null){
        $row = $this->model->where(['id'=>['in',$ids]])->find();
        if( !$row ){
            $this->error('参数错误：ids');
        }
        $filepath =  Env::get('file.FILE_ROOT_DIR') . $row['file_path'];
        $filename = $row['file_name'];
        $excelfile = urlencode($row['file_name']);
        //告诉浏览器这是一个文件流格式的文件
        //Header ( "Content-type: application/octet-stream" );
        Header("Content-type: application/octet-stream");
        //请求范围的度量单位
        Header("Accept-Ranges: bytes");
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header("Accept-Length: " . filesize($filepath));
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header("Content-Disposition: attachment; filename=" . $excelfile);
        $fp = fopen($filepath, 'rb');
        // 设置指针位置
        fseek($fp, 0);

        // 开启缓冲区
        ob_start();
        // 分段读取文件
        while (!feof($fp)) {
            $chunk_size = 1024 * 1024 * 50; // 50M
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
