<?php
/**
 * Created by : PhpStorm
 * User: daisy
 * Date: 2020/11/16
 * Time: 10:21
 */

namespace app\admin\controller\sms;


use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use think\Db;
use think\Env;

class Single extends Backend
{
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\TaskSend;

    }

    // 单点短信
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['channel_from'] = 3;
            if (!$this->auth->isSuperAdmin()) {
                $myWhere['creator'] = ['in',$this->auth->getChildrenAdminUsername() ];
            }
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            if( isset($params['is_filter']) && $params['is_filter']==1 ){
                $myWhere['total_receive'] = ['>',0];
                $myWhere['total_click'] = ['>',0];
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
            $spInfos = (new Sp())->getSpInfo('id, sp_no, sp_name, vendor_id, price', 0, '1');
            foreach ($list as $k => &$v)
            {
                if (isset($spInfos[$v['sms_gate_id']])) {
                    $v['sp_name'] = $spInfos[$v['sms_gate_id']]['sp_name'];
                }else{
                    $v['sp_name'] = '';
                }
            }
            unset($v);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    //任务列表
    public function check($ids) {
        $type = $this->request->get('type');
        $task_ids = $this->request->get('ids');
        if( $this->request->isAjax() ) {
            if (empty($ids)) {
                $this->error('error...');
            }
            $ids = explode(',', $ids);
            $db = new Db();
            $tasks = $this->model->field('task_id,send_time,finish_time,phone_path,small')->where(['task_id' => ['in', $ids]])->select();
            $tasks = collection($tasks)->toArray();
            if (empty($tasks)) {
                $this->error('下载任务不存在...');
            }
            foreach ($tasks as $key => $task) {
                if ($type == 1) {
                    if (empty($task['phone_path'])) {
                        $this->error('发送任务(' . $task['task_id'] . ')空号内容不存在，无法进行空号检测');
                    }
                }else{
                    if (empty($task['small'])) {
                        $this->error('发送任务(' . $task['task_id'] . ')小号内容不存在，无法进行小号检测');
                    }
                }
                $timeone = date('Ymd', strtotime($task['send_time']));
                $timetwo = date('Ymd', strtotime($task['send_time']) + 3600 * 24);

                //读取空|小号手机号
                if ($type == 1) {
                    $file_path = Env::get('file.FILE_ROOT_DIR') . '/' . $task['phone_path'];
                }else{
                    $file_path = Env::get('file.FILE_ROOT_DIR') . '/' . $task['small'];
                }
                if (!file_exists($file_path)) {
                    $this->error('任务(' . $task['task_id'] . ')的上传文件不存在');
                }
                $file = fopen($file_path, 'r');
                if (!$file) {
                    $this->error('任务(' . $task['task_id'] . ')的上传文件' . $file_path . '打开失败');
                }
                $tasks[$key]['task_id'] = $task['task_id'];
                $tasks[$key]['total'] = 0;
                $tasks[$key]['success'] = 0;
                $tasks[$key]['error'] = 0;
                $tasks[$key]['unkown'] = 0;
                $type = 0;
                $phones = array();
                while (1) {
                    $phone = fgets($file);
                    if ($phone === false) { //EOF
                        break;
                    } else {
                        if (strlen(trim($phone)) != 11) {
                            $type = 1;
                        }
                        $phones[] = trim($phone);
                        $tasks[$key]['total'] ++;
                    }
                }
                fclose($file);
                $phoneStr = implode(',', $phones);
                if ($type == 1) {
                    $phones = [];
                    $endata = curl_encrypt($phoneStr, 'dec');
                    $endata = json_decode($endata, true);
                    foreach ($endata['data'] as $value) {
                        $phones[] = $value;
                    }
                    $phoneStr = implode(',', $phones);
                }
                $sendListone = $db::table('sms_send_data.sms_send_log_' . $timeone)->field('phone,sp_seq,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => 'sms_send_data.sms_send_log_' . $timetwo];
                $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $sendListtwo = $db::table('sms_send_data.sms_send_log_' . $timetwo)->field('phone,sp_seq,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
                }
                if (!empty($sendListone) && !empty($sendListtwo)) {
                    $sendList = array_merge($sendListone, $sendListtwo);
                } elseif (empty($sendListone) && !empty($sendListtwo)) {
                    $sendList = $sendListtwo;
                } else {
                    $sendList = $sendListone;
                }
                $reportListone = $db::table('sms_send_data.sms_report_' . $timeone)->field('phone,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => 'sms_send_data.sms_report_' . $timetwo];
                $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $reportListtwo = $db::table('sms_send_data.sms_report_' . $timetwo)->field('phone,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
                }
                if (!empty($reportListone) && !empty($reportListtwo)) {
                    $reportList = array_merge($reportListone, $reportListtwo);
                } elseif (empty($reportListone) && !empty($reportListtwo)) {
                    $reportList = $reportListtwo;
                } else {
                    $reportList = $reportListone;
                }
                for ($i = 0; $i < count($phones); $i++) {
                    $sstatus = $sp_seq = $rstatus = 0;
                    foreach ($sendList as $send) {
                        if ($send['phone'] === $phones[$i]) {
                            $sstatus = $send['status'];
                            $sp_seq = $send['sp_seq'];
                            break;
                        }
                    }
                    foreach ($reportList as $report) {
                        if ($report['phone'] === $phones[$i]) {
                            $rstatus = $report['status'];
                            break;
                        }
                    }
                    if ($rstatus == 3) {
                        $tasks[$key]['success']++;
                    } elseif ($sp_seq == '9999' || in_array($rstatus, array(2, 4, 5, 6))) {
                        $tasks[$key]['error']++;
                    } else {
                        $tasks[$key]['unkown']++;
                    }
                    unset($sstatus);
                    unset($sp_seq);
                    unset($rstatus);
                }

            }
            $total = count($tasks);
            return json(['total'=>$total,"rows"=>$tasks]);
        }
        return $this->view->fetch();
    }

    /**
     * 空号短信发送数据下载
     * $id  任务task_id
     * @author crmhyg
     */
    public function export($ids)
    {
        set_time_limit(0);
        ini_set('memory_limit', '256M');
        $type = $this->request->get('type');
        $ids = $this->request->get('task_id');
        $id = intval($ids);
        if ($id < 1) {
            exit('下载空号任务task_id为空...');
        }
        $db = new Db();
        $task = $this->model->field('task_id,send_time,finish_time,phone_path,small')->where(" task_id = {$id}")->find();

        if (empty($task)) {
            exit('下载任务'.$id.'不存在...');
        }

        $timeone = date('Ymd',strtotime($task['send_time']));
        $timetwo = date('Ymd',strtotime($task['send_time'])+3600*24);
        //读取空|小号手机号
        if ($type == 1) {
            $file_path = Env::get('file.FILE_ROOT_DIR') . '/' . $task['phone_path'];
        }else{
            $file_path = Env::get('file.FILE_ROOT_DIR') . '/' . $task['small'];
        }
        if (!file_exists($file_path)) {
            $this->error('任务(' . $task['task_id'] . ')的上传文件不存在');
        }
        $file = fopen($file_path, 'r');
        if (!$file) {
            $this->error('任务(' . $task['task_id'] . ')的上传文件' . $file_path . '打开失败');
        }
        $data = array();
        $totalCount = 0;
        $type = 0;
        while (1) {
            $phone = fgets($file);
            if ($phone === false) { //EOF
                break;
            } else {
                if (strlen(trim($phone)) != 11){
                    $type = 1;
                }
                $phones[] = trim($phone);
            }
        }
        fclose($file);
        $phoneStr = implode(',',$phones);
        if($type == 1) {
            $phones = [];
            $endata = curl_encrypt($phoneStr, 'dec');
            $endata = json_decode($endata, true);
            foreach ($endata['data'] as $value) {
                $phones[] = $value;
            }
            $phoneStr = implode(',', $phones);
        }
        $sendListone = $db::table('sms_send_data.sms_send_log_'.$timeone)->field('phone,sp_seq,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
        $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => 'sms_send_data.sms_send_log_' . $timetwo];
        $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
        if ($tableCount) {
            $sendListtwo = $db::table('sms_send_data.sms_send_log_' . $timetwo)->field('phone,sp_seq,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
        }
        if (!empty($sendListone) && !empty($sendListtwo)) {
            $sendList = array_merge($sendListone, $sendListtwo);
        }elseif (empty($sendListone) && !empty($sendListtwo)){
            $sendList = $sendListtwo;
        }else{
            $sendList = $sendListone;
        }
        $reportListone = $db::table('sms_send_data.sms_report_'.$timeone)->field('phone,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
        $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => 'sms_send_data.sms_report_' . $timetwo];
        $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
        if ($tableCount) {
            $reportListtwo = $db::table('sms_send_data.sms_report_' . $timetwo)->field('phone,status')->where(" task_id = {$task['task_id']} and phone in ({$phoneStr})")->select();
        }
        if (!empty($reportListone) && !empty($reportListtwo)) {
            $reportList = array_merge($reportListone, $reportListtwo);
        }elseif (empty($reportListone) && !empty($reportListtwo)){
            $reportList = $reportListtwo;
        }else{
            $reportList = $reportListone;
        }
        for ($i=0;$i<count($phones);$i++){
            $sstatus = $sp_seq = $rstatus = 0;
            foreach ($sendList as $send){
                if ($send['phone'] === $phones[$i]){
                    $sstatus = $send['status'];
                    $sp_seq = $send['sp_seq'];
                    break;
                }
            }
            foreach ($reportList as $report){
                if ($report['phone'] === $phones[$i]){
                    $rstatus = $report['status'];
                    break;
                }
            }
            if ($rstatus == 3){
                $data[$totalCount]['status'] = '成功';
            }elseif ($sp_seq == '9999' || in_array($rstatus,array(2, 4, 5, 6))){
                $data[$totalCount]['status'] = '失败';
            }else{
                $data[$totalCount]['status'] = '未知';
            }
            $data[$totalCount++]['phone'] = $phones[$i];
            unset($sstatus);unset($sp_seq);unset($rstatus);
        }


        //清除缓冲区,避免乱码
        ob_end_clean();

        //定义输出的文件名
        $fileName = 'phone_check_' . $id . '.csv';

        //发送下载文件头信息
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'. $fileName .'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $columns = ['手机号码', '状态'];

        $fp = fopen('php://output', 'a');//打开output流
        mb_convert_variables('GBK', 'UTF-8', $columns);
        fputcsv($fp, $columns);//将数据格式化为CSV格式并写入到output流中

        //刷新输出缓冲到浏览器，必须同时使用这两函数来刷新输出缓冲。
        if(ob_get_level()>0) ob_flush();
        flush();

        $r = 100000; //每次最多取10万
        $pages = ceil($totalCount / $r);

        for ($page = 1; $page <= $pages; $page ++) {

            foreach ($data as $item) {
                $rowData = [
                    $item['phone'],
                    $item['status'],
                ];

                mb_convert_variables('GBK', 'UTF-8', $rowData);
                fputcsv($fp, $rowData);

                if(ob_get_level()>0) ob_flush();
                flush();
            }

        }

        fclose($fp);

    }

    //点击列表
    public function clicklist($ids)
    {
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $where = " where 1=1 ";
            if ($ids > 0) {
                $where .= " AND shortlink_id='{$ids}'";
            }

            $month = date('Ym');
            $preMonth = date('Ym', strtotime('-1 month'));

            $sql = "select * from sms_send_data.sms_click_log_{$month} {$where} union 
				select * from sms_send_data.sms_click_log_{$preMonth} {$where}";

            $countSql = 'select count(1) total from (' . $sql . ') a';
            $totalCount = Db::query($countSql);
            $totalCount = intval($totalCount[0]['total']);

            if ($totalCount > 0) {
                $sql .= ' order by click_time desc limit ' . $offset . ', ' . $limit;
                $list = Db::query($sql);
            }

            return json(['total'=>$totalCount,"rows"=>$list]);
        }
        return $this->view->fetch();
    }
}