<?php

namespace app\admin\controller\plot;

use app\common\controller\Backend;
use think\Db;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * 银行收入报表列管理
 *
 * @icon fa fa-circle-o
 */
class Income extends Backend
{
    protected $typeList = [
        "1" =>'守护保',
        "2" =>'得保',
        "3" =>'豪斯莱广发',
        "4" =>'麦星广发',
        "5" =>'顺风车',
        "6" =>'中信银行',
    ];
    
    /**
     * Income模型对象
     * @var \app\admin\model\plot\Income
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\plot\Income;
        $this->debaoDetailModel = new \app\admin\model\plot\Debaodetail;
        $this->shouhubaoDetailModel = new \app\admin\model\plot\Shouhubaodetail;
        $this->hslgfDetailModel = new \app\admin\model\plot\Hslgfdetail;
        $this->mxgfDetailModel = new \app\admin\model\plot\Mxgfdetail;
        $this->sfcDetailModel = new \app\admin\model\plot\Sfcdetail;
        $this->zhongxinDetailModel = new \app\admin\model\plot\Zhongxindetail;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    // 列表
    public function index()
    {
        if ($this->request->isAjax())
        {
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
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if( $this->request->isPost() ){
            set_time_limit(0);
            ini_set('memory_limit', '512M');
            $params= $this->request->post('row/a');
            if( !$params['file_path'] ){
                $this->error("文件必须上传");
            }else{
                $attachment = model("attachment");
                $file_name = $attachment->where("url='{$params['file_path']}'")->value("extparam");
                $file_name = json_decode($file_name,true);
                $incomes['file_name'] = $file_name['name'];
            }
            //读取文件信息
            $filePath = $params['file_path'];
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
            $num = 0;
            //获取最大的任务id
            $incomeLastID = $this->model->max('id');
            $bank_id = $incomeLastID + 1;
            $creator = $this->auth->getUserInfo()['username'];

            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                //得保  守护保
                if ($params['type'] == 1 || $params['type'] == 2) {
                    $channel = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                    $enter_time = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());

                    if (empty($channel)) continue;
                    if (empty($enter_time)) continue;
                    $values = [
                        'channel' => $channel,
                        'enter_time' => $enter_time,
                        'bank_id' => $bank_id,
                        'creator' => $creator,
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                }elseif ($params['type'] == 3 || $params['type'] == 4){
                    //豪斯莱广发  麦星广发
                    $channel = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                    $enter_time = trim($currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue());
                    if (is_numeric($enter_time)){
                        $enter_time = gmdate('Y-m-d H:i',intval(($enter_time - 25569) * 3600 * 24));
                    }
                    $import_num = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());
                    $approved_num = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());

                    if (empty($channel)) continue;
                    if (empty($enter_time)) continue;
                    if ($params['type'] == 3){
                        $result = $this->hslgfDetailModel->where("channel='$channel' and enter_time='$enter_time'")->delete();
                    }else{
                        $result = $this->mxgfDetailModel->where("channel='$channel' and enter_time='$enter_time'")->delete();
                    }
                    $values = [
                        'channel' => $channel,
                        'enter_time' => $enter_time,
                        'import_num' => $import_num,
                        'approved_num' => $approved_num,
                        'bank_id' => $bank_id,
                        'creator' => $creator,
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                }elseif ($params['type'] == 5){
                    //顺风车
                    $channel = trim($currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue());
                    $collect_time = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                    if (is_numeric($collect_time)){
                        $collect_time = gmdate('Y-m-d H:i',intval(($collect_time - 25569) * 3600 * 24));
                    }
                    $auth_time = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());
                    if (is_numeric($auth_time)){
                        $auth_time = gmdate('Y-m-d H:i',intval(($auth_time - 25569) * 3600 * 24));
                    }
                    if (empty($channel) || $channel == "(NULL)") continue;
                    if (empty($collect_time) || $collect_time == "(NULL)") continue;
                    if (empty($auth_time) || $auth_time == "(NULL)") $auth_time = null;
                    $values = [
                        'channel' => $channel,
                        'collect_time' => $collect_time,
                        'auth_time' => $auth_time,
                        'bank_id' => $bank_id,
                        'creator' => $creator,
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                }else{
                    //中信银行
                    if ($currentRow == 2) continue;
                    $channel = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());
                    $enter_time = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
                    if (is_numeric($enter_time)){
                        $enter_time = gmdate('Y-m-d H:i',intval(($enter_time - 25569) * 3600 * 24));
                    }
                    $first_card_in = trim($currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue());
                    if (empty($channel)) continue;
                    $first_card_pass = trim($currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue());
                    $first_pass = trim($currentSheet->getCellByColumnAndRow(8, $currentRow)->getValue());
                    $pass_15 = trim($currentSheet->getCellByColumnAndRow(9, $currentRow)->getValue());
                    $pass_30 = trim($currentSheet->getCellByColumnAndRow(10, $currentRow)->getValue());
                    $pass_card = trim($currentSheet->getCellByColumnAndRow(11, $currentRow)->getValue());
                    $result = $this->zhongxinDetailModel->where("channel='$channel' and enter_time='$enter_time'")->delete();
                    $values = [
                        'channel' => $channel,
                        'enter_time' => $enter_time,
                        'first_card_in' => $first_card_in,
                        'bank_id' => $bank_id,
                        'first_card_pass' => $first_card_pass,
                        'first_pass' => $first_pass,
                        'pass_15' => $pass_15,
                        'pass_30' => $pass_30,
                        'pass_card' => $pass_card,
                        'creator' => $creator,
                        'create_time' => date('Y-m-d H:i:s'),
                    ];
                }
                $insert[] = $values;
                $num ++;
            }
            if( !$insert ){
                $this->error("文件内容为空，请重新上传文件");
            }
            Db::startTrans();
            try {
                //事务
                $incomes['type'] = $params['type'];
                $incomes['creator'] = $creator;
                $incomes['num'] = $num;
                $incomes['create_time'] = date("Y-m-d H:i:s");
                //保存数据
                $this->model->save($incomes);
                if ($params['type'] == 1){
                    $result = $this->shouhubaoDetailModel->saveAll($insert);
                }elseif ($params['type'] == 2){
                    $result = $this->debaoDetailModel->saveAll($insert);
                }elseif ($params['type'] == 3){
                    $result = $this->hslgfDetailModel->saveAll($insert);
                }elseif ($params['type'] == 4){
                    $result = $this->mxgfDetailModel->saveAll($insert);
                }elseif ($params['type'] == 5){
                    Db::query("truncate sms_sfc_income_detail");
                    $inum = 100;//每次导入条数
                    $limit = ceil($num/$inum);
                    for($i=1;$i<=$limit;$i++){
                        $offset=($i-1)*$inum;
                        $data=array_slice($insert,$offset,$inum);
                        $result=Db::table('sms_sfc_income_detail')->insertAll($data);
                    }
                    //$result = $this->sfcDetailModel->saveAll($insert);
                }else{
                    $result = $this->zhongxinDetailModel->saveAll($insert);
                }
                Db::commit();
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }

            if( !$result ){
                $this->success('任务创建失败！！');
            }
            unset($insert);
            $this->success('任务创建成功！！');
        }
        $this->assign('typeList',$this->typeList);
        return $this->view->fetch();
    }

    //详情
    public function detail(){
        $id = $this->request->get('id');
        $offset  = $this->request->get("offset");
        $limit   = $this->request->get("limit");
        if( $id ){
            $type = $this->model->where('id',$id)->value('type');
            if ($type == 1) {
                $rows = $this->shouhubaoDetailModel->where('bank_id', $id)->order('id', 'desc')->limit($offset, $limit)->select();
                $total = $this->shouhubaoDetailModel->where('bank_id', $id)->order('id', 'desc')->count();
            }else{
                $rows = $this->debaoDetailModel->where('bank_id', $id)->order('id', 'desc')->limit($offset, $limit)->select();
                $total = $this->debaoDetailModel->where('bank_id', $id)->order('id', 'desc')->count();
            }

            return json(['total'=>$total,"rows"=>$rows]);

        }
        return $this->view->fetch();
    }
}
