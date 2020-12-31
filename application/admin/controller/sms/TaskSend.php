<?php

namespace app\admin\controller\sms;

use app\admin\model\basic\Sp;
use app\common\controller\Backend;
use app\common\model\Attachment;
use Exception;
use think\Db;
use think\db\Query;
use think\Env;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Log;

/**
 * 短信发送任务管理
 *
 * @icon fa fa-circle-o
 */
class TaskSend extends Backend
{
    
    /**
     * TaskSend模型对象
     * @var \app\admin\model\sms\TaskSend
     */
    protected $model = null;
    protected $domainList = [
                '' =>'不使用动态短链',
                //"1" =>'u9t.cn',
                //"2" =>'x0e.cn',
                //"3" =>> 'q9e.cn',
                //"4" =>'d0e.cn(傅晓妹)',
                //"5" =>'7d0.cn(杨刚)',
                //"6" =>'o8d.cn(黄福忠)',
                //"7" =>'0i4.cn(左浩然|马蓉蓉)',
                //"8" =>'q4f.cn(姜子文)',
                //"9" =>'g0c.cn(王古锋)',
                //"10" =>'z0k.cn',
                //"11" => 'q0r.cn',
                "12" => 'n0x.cn(游戏专用)',
                "13" => 'h0e.cn(短信内容无http://)',
                //"14" => 'o4c.cn',
                "15" => '9oj.cn',
                "16" =>'5oj.cn',
                "17" =>'vo4.cn',
                //"18" =>'4a6.cn',
                "19" =>'j0q.cn',
                "20" =>'p0o.cn',
                "21" =>'b4m.cn',
                "22" =>'h8r.cn',
                "23" =>'7j0.cn',
                "24" =>'4g3.cn',
                "25" =>'j0l.cn',
    ];
    protected $shortDomainArr = [
        0=>"j0q",
        1=>"u9t",
        2=>"x0e",
        3=>"q9e",
        4=>"d0e",
        5=>"7d0",
        6=>"o8d",
        7=>"0i4",
        8=>"q4f",
        9=>"g0c",
        10=>"z0k",
        11=>"q0r",
        12=>"n0x",
        13=>"h0e",
        14=>"o4c",
        15=>"9oj",
        16=>"5oj",
        17=>"vo4",
        18=>"4a6",
        19=>"j0q",
        20=>"p0o",
        21=>"b4m",
        22=>"h8r",
        23=>"7j0",
        24=>"4g3",
        25=>"j0l",
    ];
    protected $statusArr = [
        1 => '待生成短链',
        2 => '生成动态短链中',
        3 => '等待发送',
        4 => '发送中',
        5 => '发送完毕',
        6 => '已停止',
        7 => '已删除',
        8 => '无需发送',
        9 => '暂存',
        10 => '短链生成完毕',
        11 => '创建超信任务失败',
        12 => '创建超信任务成功',
        13 => '超信任务添加手机号中',
        14 => '超信任务添加手机号成功',
        15 => '超信任务添加手机号失败',
        16 => '超信任务提交失败',
        17 => '入队列完毕',
        18 => '写入发送队列中',
        19 => '通道连接异常',
    ];
    protected $pattern = '/http[s]?:\\/\\/[-.=%&\\?\\w\\/]+/';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\TaskSend;

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    // 常规短信
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere['channel_from'] = 0;
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

    public function add()
    {
        $short_link_id = $this->request->get('ids');
        $link_from = $this->request->get('link_from');
        $channel_from = (int)$this->request->get('channel_from'); // 0常规，1特定，2实时，3单点，4外部
        $spModel = new Sp();
        $linkShortModel = new \app\admin\model\sms\LinkShort();
        $linkShort = $linkShortModel->get($short_link_id);
        $spList = $spModel->field('id,sp_no,sp_name,remote_account,price')->select();

        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if( mb_strlen($params['sms_content'],'utf-8') > 70 ){
                $this->error('短信内容最多70个字符！');
            }
            $linkModel = new \app\admin\model\sms\Link();
            $linkShort = $linkShortModel->get($params['sm_task_id']);
            $sp = $spModel->get($params['sms_gate_id']);
            if( !$linkShort ){
                $this->error('短链ID参数错误！！！');
            }
            if( !$sp ){
                $this->error('通道不存在！！');
            }
            if( $sp['status'] == 3 && !$params['sms_template_id'] ){//如果是http通道，template_id必填
                $this->error('http通道，必须选择短信模板！！');
            }
            $link = $linkModel->get($linkShort['link_id']);
            $params['company'] = $link['company_name'];
            $params['bank'] = $link['bank_name'];
            $params['business'] = $link['business_name'];
            $params['channel_id'] = $link['channel_id'];
            if( $params['link_from'] == 1 && !$params['file_path']){ // 0:未知 1:内部 2:外部
                $this->error('发送文件必须上传！');
            }
            $params['file_path'] = trim($params['file_path'],Env::get('file.FILE_ROOT_DIR'));
            // 设置短信类型及初始状态
            switch ($params['channel_from'] ){
                case 0: $params['status'] = 3;break;
                case 1: $params['status'] = 1;break;
                case 2: $params['status'] = 5;break;
                case 3: $params['status'] = 1;break;
                case 4: $params['status'] = 8;break;
            }

            $params['sms_content'] = preg_replace($this->pattern, $params['shortlink'], $params['sms_content']);
            $params['sms_content'] = trim($params['sms_content']);
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('YmdHis');
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$params['sms_gate_id'])->value('p.PRICEX');
            $params['price'] = $price;
            $result = $this->model->save($params);
            $linkShortModel->save(['task_send_num'=>$linkShort['task_send_num']+1],['id'=>$linkShort['id']]);
            if( !$result ){
                $this->success('任务创建失败！！');
            }
            $this->success('任务创建成功！！');
        }
        $this->assign('link_from',$link_from);
        $this->assign('channel_from',$channel_from);
        $this->assign('domainList',$this->domainList);
        $this->assign('spList',$spList);
        $this->assignconfig('spList',$spList);
        $this->assign('linkShort',$linkShort);
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $spModel = new Sp();
        //$linkShortModel = new \app\admin\model\sms\LinkShort();
        //$linkShort = $linkShortModel->get($row['sm_task_id']);
        $spList = $spModel->field('id,sp_no,sp_name,remote_account,price')->select();
        $attachmentModel = new Attachment();
        $file_name = $attachmentModel->where('url',Env::get('file.FILE_ROOT_DIR').$row['file_path'])->value('extparam');
        $file_name = json_decode($file_name,true);
        $row['file_name'] = $file_name['name'];
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            if (!in_array($row['status'], [1, 2, 3, 6, 7, 9])) {
                $this->error('任务' . $this->statusArr[$row['status']] . '，不能修改。');
            }
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
            }
            if( $params['link_from'] == 1 &&  !$params['file_path']){ // 0:未知 1:内部 2:外部
                $this->error('发送文件必须上传！');
            }
            $params['file_path'] = trim($params['file_path'],Env::get('file.FILE_ROOT_DIR'));
            $sp = $spModel->get($params['sms_gate_id']);
            if( !$sp ){
                $this->error('通道不存在！！');
            }
            if( $sp['status'] == 3 && !$params['sms_template_id'] ){//如果是http通道，template_id必填
                $this->error('http通道，必须选择短信模板！！');
            }
            $params['sms_content'] = preg_replace($this->pattern, $params['shortlink'], $params['sms_content']);
            $params['sms_content'] = trim($params['sms_content']);
            $params['creator'] = $this->auth->getUserInfo()['username'];
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$params['sms_gate_id'])->value('p.PRICEX');
            $params['price'] = $price;
            $result = $row->allowField(true)->save($params);
            if( $result ){
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $this->assign('domainList',$this->domainList);
        $this->assign('spList',$spList);
        $this->assignconfig('spList',$spList);
        //$this->assign('linkShort',$linkShort);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    // 失败下载
    public function failedDownload($type=1){
        $params = $this->request->get();
        $ids = explode(',', $params['ids']);
        $list = $this->model->where(['task_id'=>['in',$ids]])->select();
        if( empty($list) ){
            return json(['data'=>['msg'=>'下载任务不存在...']]);
        }
        $extract_file = 'send_failed_' . implode('_', $ids) . '.txt';

        $db = new Query();
        foreach ($list as $task) {
            if ($task['total_send'] < 1) {
                continue;
            }
            if ($task['total_send'] == $task['total_receive']) {
                continue;
            }
            $time  = strtotime($task['send_time']);
            $date  = date('Ymd', $time);
            $date2 = date('Ymd', $time + 86400);
            $date3 = date('Ymd', $time + 86400 * 2);
            $dates = [$date, $date2, $date3];
            $tableNames = [];
            foreach ($dates as $date) {
                $tableName = 'sms_report_' . $date;
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => $tableName];
                $tableCount = $db->table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $tableNames[] = $tableName;
                }
            }

            foreach ($tableNames as $tableName) {
                $table = 'sms_send_data.' . $tableName;
                $where = 'task_id = ' . $task['task_id'] . ' and status in (2, 4, 5, 6)';
                $totalCount = $db->table($table)->where($where)->count();

                if ($totalCount > 0) {
                    $r = ($type == 1) ? 10000 : 100000; //每次最多取10万
                    $pages = ceil($totalCount / $r);

                    $total = $totalCount;

                    for ($page = 1; $page <= $pages; $page ++) {
                        $start = ($page - 1) * $r;
                        $limit = $r;
                        if ($limit > $total) {
                            $limit = $total;
                        }

                        $list = $db->table($table)->where($where)->field('phone')->limit($start, $limit)->select();
                        if ($type == 1){
                            foreach ($list as $user) {
                                $phonenc[] = $user['phone'];
                            }
                            $tphone = implode(',',$phonenc);
                            unset($list,$phonenc);
                            $endata = curl_encrypt($tphone,'enc');
                            $endata = json_decode($endata,true);
                            foreach ($endata['data'] as $value){
                                $list[]['phone'] = $value;
                            }
                        }
                        foreach ($list as $user) {
                            echo $user['phone'] . "\n";
                        }

                        $total -= $limit;
                    }
                }
            }

            $tableNames = [];
            unset($dates[2]);
            foreach ($dates as $date) {
                $tableName = 'sms_send_log_' . $date;
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => $tableName];
                $tableCount = $db->table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $tableNames[] = $tableName;
                }
            }

            foreach ($tableNames as $tableName) {
                $table = 'sms_send_data.' . $tableName;
                $where = 'task_id = ' . $task['task_id'] . ' and sp_seq = "9999" ';
                $totalCount = $db->table($table)->where($where)->count();

                if ($totalCount > 0) {
                    $r = ($type == 1) ? 10000 : 100000; //每次最多取10万
                    $pages = ceil($totalCount / $r);

                    $total = $totalCount;

                    for ($page = 1; $page <= $pages; $page ++) {
                        $start = ($page - 1) * $r;
                        $limit = $r;
                        if ($limit > $total) {
                            $limit = $total;
                        }

                        $list = $db->table($table)->where($where)->field('phone')->limit($start, $limit)->select();
                        if ($type == 1){
                            foreach ($list as $user) {
                                $phonenc[] = $user['phone'];
                            }
                            $tphone = implode(',',$phonenc);
                            unset($list,$phonenc);
                            $endata = curl_encrypt($tphone,'enc');
                            $endata = json_decode($endata,true);
                            foreach ($endata['data'] as $value){
                                $list[]['phone'] = $value;
                            }
                        }
                        foreach ($list as $user) {
                            echo $user['phone'] . "\n";
                        }

                        $total -= $limit;
                    }
                }
            }
        }


        header("Content-Type: application/octet-stream");
        if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) {
            header('Content-Disposition:  attachment; filename="' . $extract_file . '"');
        } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename*="' .  $extract_file . '"');
        } else {
            header('Content-Disposition: attachment; filename="' .  $extract_file . '"');
        }

    }

    // 成功下载
    public function successDownload($ids){
        if(!$ids){
            $this->error('请选择下载项');
        }

        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $ids = explode(',', $ids);
        $db = new Query();
        $tasks = $db->table('sms_task_send')->where(['task_id' => ['in', $ids]])->select();

        if (empty($tasks)) {
            exit('下载任务不存在...');
        }

        $extract_file = 'send_success.txt';

        header("Content-Type: application/octet-stream");
        if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) {
            header('Content-Disposition:  attachment; filename="' . $extract_file . '"');
        } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename*="' .  $extract_file . '"');
        } else {
            header('Content-Disposition: attachment; filename="' .  $extract_file . '"');
        }

        $city = $province = $carrier = [];
        $gw_mobile = fopen($_SERVER['DOCUMENT_ROOT']."/gw_mobilearea.txt", 'r');
        $gw_phone = fgets($gw_mobile);
        while ($gw_phone !== false) {
            $gw_phone = trim($gw_phone);
            $gws = explode("|",$gw_phone);
            $city[$gws[0]] = $gws[1];
            $province[$gws[0]] = $gws[2];
            $carrier[$gws[0]] = $gws[3];

            $gw_phone = fgets($gw_mobile);
        }

        fclose($gw_mobile);

        foreach ($tasks as $task) {

            if ($task['total_receive'] < 1) {
                continue;
            }

            if ($task['total_send'] == $task['total_receive']) {
                continue;
            }

            $time = strtotime($task['send_time']);
            $date = date('Ymd', $time);
            $date2 = date('Ymd', $time + 86400);
            $date3 = date('Ymd', $time + 86400 * 2);
            $dates = [$date, $date2, $date3];

            $tableNames = [];
            foreach ($dates as $date) {
                $tableName = 'sms_report_' . $date;
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => $tableName];
                $tableCount = $db->table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $tableNames[] = $tableName;
                }
            }

            foreach ($tableNames as $tableName) {
                $table = 'sms_send_data.' . $tableName;
                $where = 'task_id = ' . $task['task_id'] . ' and status = 3';
                $totalCount = $db->table($table)->where($where)->count();

                if ($totalCount > 0) {
                    $r = 10000; //每次最多取10万
                    $pages = ceil($totalCount / $r);

                    $total = $totalCount;

                    for ($page = 1; $page <= $pages; $page++) {
                        $start = ($page - 1) * $r;
                        $limit = $r;
                        if ($limit > $total) {
                            $limit = $total;
                        }

                        $list = $db->table($table)->where($where)->field('phone')->limit($start, $limit)->select();
                        foreach ($list as $user) {
                            $phonenc[] = $user['phone'];
                        }
                        $tphone = implode(',',$phonenc);
                        unset($list,$phonenc);
                        $endata = curl_encrypt($tphone,'enc');
                        $endata = json_decode($endata,true);
                        foreach ($endata['data'] as $key=>$value){
                            $gwcontent = substr($key,0,7);
                            $carrierContent = $carrier[$gwcontent];
                            $provinceContent = $province[$gwcontent];
                            $cityContent = $city[$gwcontent];
                            $carrierContent = $carrierContent ? $carrierContent : 4;
                            $provinceContent = $provinceContent ? $provinceContent : '00';
                            $cityContent = $cityContent ? $cityContent : '000';
                            $enContent = $carrierContent.$provinceContent.$cityContent.'00'.$value;
                            echo $enContent . "\n";
                        }

                        $total -= $limit;
                    }
                }
            }
        }

    }

    // 点击下载
    public function clickDownload($ids){
        Log::log('点击下载');
        if(!$ids){
            $this->error('请选择下载项');
        }
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $ids = explode(',', $ids);
        $minTime = $this->model->where(['task_id' => ['in', $ids]])->order('send_time','desc')->limit(1)->value('send_time');
        $shortIds = $this->model->where(['task_id' => ['in', $ids]])->column('sm_task_id');
        if (empty($shortIds)) {
            $this->error('下载任务不存在...');
        }
        $starttime = strtotime($minTime);//要用到的是9月    所以从8月开始
        $starttime = strtotime(date('Y-m-01',$starttime));
        $endDay = date("Y-m-01");
        $endtime = strtotime("$endDay +1 month");
        while( $starttime < $endtime){
            $month_arr[] = date('Ym',$starttime); // 取得递增月;
            $starttime = strtotime('+1 month', $starttime);
        }
        $extract_file = 'send_click.txt';
        header("Content-Type: application/octet-stream");
        if (preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']) ) {
            header('Content-Disposition:  attachment; filename="' . $extract_file . '"');
        } elseif (preg_match("/Firefox/", $_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Disposition: attachment; filename*="' .  $extract_file . '"');
        } else {
            header('Content-Disposition: attachment; filename="' .  $extract_file . '"');
        }
        $city = $province = $carrier = [];
        $gw_mobile = fopen($_SERVER['DOCUMENT_ROOT']."/gw_mobilearea.txt", 'r');
        $gw_phone = fgets($gw_mobile);
        while ($gw_phone !== false) {
            $gw_phone = trim($gw_phone);
            $gws = explode("|",$gw_phone);
            $city[$gws[0]] = $gws[1];
            $province[$gws[0]] = $gws[2];
            $carrier[$gws[0]] = $gws[3];
            $gw_phone = fgets($gw_mobile);
        }
        fclose($gw_mobile);
        foreach ($month_arr as $month) {
            $table = 'sms_send_data.sms_click_log_' . $month;
            Log::log( implode(',',$shortIds));
            Log::log( json_encode($shortIds) );
            $count = Db::table($table)->where(['shortlink_id' =>['in',implode(',',$shortIds)]])->where(['phone'=>['>',0]])->count();
            Log::log($count);
            if ($count > 0) {
                $list = Db::table($table)->distinct(true)->field('phone_sec,phone')->where(['shortlink_id' => ['in',implode(',',$shortIds)]])->where([['phone'=>['>',0]]])->select();
                foreach ($list as $user) {
                    $gwcontent = substr($user['phone'],0,7);
                    $carrierContent = isset($carrier[$gwcontent]) ? $carrier[$gwcontent] : 4;
                    $provinceContent = isset($province[$gwcontent]) ? $province[$gwcontent] : '00';
                    $cityContent = isset($city[$gwcontent]) ? $city[$gwcontent] : '000';
                    $enContent = $carrierContent.$provinceContent.$cityContent.'00'.$user['phone_sec'];
                    echo $enContent . "\n";
                }
            }
        }
    }
    // 一键复发
    public function relapse($ids){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($row['total_send'] < 1) {
            $this->error('发送任务总量为0，无法一键复发。');
        }
        $spModel = new Sp();
        $spList = $spModel->field('id,sp_no,sp_name,remote_account,price')->select();
        if( $this->request->isPost() ){
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
            }
            $linkModel = new \app\admin\model\sms\Link();
            $linkShortModel = new \app\admin\model\sms\LinkShort();
            $linkShort = $linkShortModel->get($params['sm_task_id']);


            $linkShortLastID = $linkShortModel->max('id');
            $transfer_link =  'http://cca.smget.co/link.php?id='.($linkShortLastID+1);
            $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($this->shortDomainArr[$params['dynamic_shortlink']]) . '&url=' . rawurlencode($transfer_link);
            $shortLinkResult = httpRequest($apiUrl, 'GET');
            $shortLinkResult = json_decode($shortLinkResult, true);
            if (empty($shortLinkResult['data'][0])) {
                return json(['data'=>['msg'=>'短链生成失败，请稍后重试..']]);
            }
            $linkInfo = $linkModel->where("channel_id = '$row[channel_id]'")->find();
            //$this->error(__('No Results were found'));
            $result = $linkShortModel->save([
                'remark'        => $params['title'],
                'link_id'       => $linkInfo['id'],
                'business_link' => $linkInfo['link'],
                'transfer_link' => $transfer_link,
                'short_link'    => $shortLinkResult['data'][0]['short_url'],
                'creator'       => $this->auth->getUserInfo()['username'],
                'create_time'   => date('Y-m-d H:i:s'),
            ]);


            //$link = $linkModel->get($linkShort['link_id']);
            $params['company'] = $linkInfo['company_name'];
            $params['bank'] = $linkInfo['bank_name'];
            $params['business'] = $linkInfo['business_name'];
            $params['channel_id'] = $linkInfo['channel_id'];
            if( $params['link_from'] == 1){ // 0:未知 1:内部 2:外部
                if( !$params['file_path'] ){
                    $this->error('发送文件必须上传！');
                }
            }
            $sp = $spModel->get($params['sms_gate_id']);
            if( !$sp ){
                $this->error('通道不存在！！');
            }
            if( $sp['status'] == 3 && !$params['sms_template_id'] ){//如果是http通道，template_id必填
                $this->error('http通道，必须选择短信模板！！');
            }
            $params['shortlink'] = $shortLinkResult['data'][0]['short_url'];
            $params['sms_content'] = preg_replace($this->pattern, $params['shortlink'], $params['sms_content']);
            $params['sms_content'] = trim($params['sms_content']);
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('YmdHis');
            // 设置短信类型及初始状态
            switch ($params['channel_from'] ){
                case 0: $params['status'] = 3;break;
                case 1: $params['status'] = 1;break;
                case 2: $params['status'] = 5;break;
                case 3: $params['status'] = 1;break;
                case 4: $params['status'] = 8;break;
            }
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$params['sms_gate_id'])->value('p.PRICEX');
            $params['price'] = $price;
            $result = $this->model->save($params);
            $linkShortModel->save(['task_send_num'=>$linkShort['task_send_num']+1],['id'=>$linkShort['id']]);
            if( !$result ){
                $this->success('复发任务创建失败！！');
            }
            $this->success('复发任务创建成功！！');
        }

        $this->assign('domainList',$this->domainList);
        $this->assign('spList',$spList);
        $this->assignconfig('spList',$spList);
        $this->assign('row',$row);
        return $this->view->fetch();
    }

    // 失败用户复发
    public function repeat($ids){

        $row = $this->model->get($ids);
        if (empty($row) || ($row['status'] == 7)) {
            $this->error('发送任务不存在，或已被删除。');
        }
        if ($row['total_send'] < 1) {
            $this->error('发送任务总量为0，无法失败重发。');
        }

        if ($row['total_send'] == $row['total_receive']) {
            $this->error('发送任务没有需要复发的失败短信。');
        }
        $spModel = new Sp();
        $spList = $spModel->field('id,sp_no,sp_name,remote_account,price')->select();
        if( $this->request->isPost() ){
            $time  = strtotime($row['send_time']);
            $date  = date('Ymd', $time);
            $date2 = date('Ymd', $time + 86400);
            $date3 = date('Ymd', $time + 86400 * 2);
            $dates = [$date, $date2, $date3];

            $db = new Db();
            $tableNames = [];
            $file_paths = [];
            $totalCount = 0;
            foreach ($dates as $date) {
                $tableName = 'sms_report_' . $date;
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => $tableName];
                $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $tableNames[] = $tableName;
                }
            }
            foreach ($tableNames as $tableName) {
                $table = 'sms_send_data.' . $tableName;
                $where = 'task_id = ' . $row['task_id'] . ' and status in (2, 4, 5, 6)';
                $reCount = $db::table($table)->where($where)->count();
                $totalCount += $reCount;
                if ($reCount > 0) {
                    $file_path = date('Y-m-d') . '/' . $totalCount . time() . rand(100, 999) . 're.txt';
                    if (!is_dir(Env::get('file.FILE_ROOT_DIR') .'/'. date('Y-m-d'))) {
                        @mkdir(Env::get('file.FILE_ROOT_DIR') .'/'. date('Y-m-d'));
                    }
                    $file = fopen(Env::get('file.FILE_ROOT_DIR') .'/'. $file_path, 'w') or die("Unable to open file!");
                    $r = 10000; //每次最多取1万,方便手机号加密
                    $pages = ceil($reCount / $r);
                    $total = $reCount;

                    for ($page = 1; $page <= $pages; $page++) {
                        $start = ($page - 1) * $r;
                        $limit = $r;
                        if ($limit > $total) {
                            $limit = $total;
                        }
                        $list = $db::table($table)->where($where)->field('phone')->limit($start, $limit)->select();
                        foreach ($list as $user) {
                            $phone[] = $user['phone'];
                        }
                        $total -= $limit;
                        fwrite($file, implode("\n", $phone));
                        fclose($file);
                        unset($phone);
                        $file_paths[] = $file_path;
                    }

                }
            }
            $tableNames = [];
            unset($dates[2]);
            foreach ($dates as $date) {
                $tableName = 'sms_send_log_' . $date;
                $condition = ['TABLE_SCHEMA' => 'sms_send_data', 'TABLE_NAME' => $tableName];
                $tableCount = $db::table('INFORMATION_SCHEMA.TABLES')->where($condition)->count();
                if ($tableCount) {
                    $tableNames[] = $tableName;
                }
            }
            foreach ($tableNames as $tableName) {
                $table = 'sms_send_data.' . $tableName;
                $where = 'task_id = ' . $row['task_id'] . ' and sp_seq = "9999" ';
                $seCount = $db::table($table)->where($where)->count();
                $totalCount += $seCount;
                if ($seCount > 0) {
                    $file_path = date('Y-m-d') . '/' . $totalCount . time() . rand(100, 999) . 'send.txt';
                    if (!is_dir(Env::get('file.FILE_ROOT_DIR') .'/'. date('Y-m-d'))) {
                        @mkdir(Env::get('file.FILE_ROOT_DIR') .'/'. date('Y-m-d'));
                    }
                    $file = fopen(Env::get('file.FILE_ROOT_DIR') .'/'. $file_path, 'w') or die("Unable to open file!");
                    $r = 10000; //每次最多取1万,方便加密
                    $pages = ceil($seCount / $r);
                    $total = $seCount;

                    for ($page = 1; $page <= $pages; $page ++) {
                        $start = ($page - 1) * $r;
                        $limit = $r;
                        if ($limit > $total) {
                            $limit = $total;
                        }
                        $list = $db::table($table)->where($where)->field('phone')->limit($start, $limit)->select();
                        foreach ($list as $user) {
                            $phone[] = $user['phone'];
                        }
                        $total -= $limit;
                    }
                    fwrite($file, implode("\n", $phone));
                    fclose($file);
                    unset($phone);
                    $file_paths[] = $file_path;
                }
            }
            if ($totalCount < 1){
                $this->error('经查，最近三天没有需要复发的失败短信。','sms/link_short/index');
            }
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
            }
            $linkModel = new \app\admin\model\sms\Link();
            $linkShortModel = new \app\admin\model\sms\LinkShort();
            $linkShort = $linkShortModel->get($params['sm_task_id']);


            $linkShortLastID = $linkShortModel->max('id');
            $transfer_link =  'http://cca.smget.co/link.php?id='.($linkShortLastID+1);
            $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($this->shortDomainArr[$params['dynamic_shortlink']]) . '&url=' . rawurlencode($transfer_link);
            $shortLinkResult = httpRequest($apiUrl, 'GET');
            $shortLinkResult = json_decode($shortLinkResult, true);
            if (empty($shortLinkResult['data'][0])) {
                return json(['data'=>['msg'=>'短链生成失败，请稍后重试..']]);
            }
            $linkInfo = $linkModel->where("channel_id = '$row[channel_id]'")->find();
            //$this->error(__('No Results were found'));
            $result = $linkShortModel->save([
                'remark'        => $params['title'],
                'link_id'       => $linkInfo['id'],
                'business_link' => $linkInfo['link'],
                'transfer_link' => $transfer_link,
                'short_link'    => $shortLinkResult['data'][0]['short_url'],
                'creator'       => $this->auth->getUserInfo()['username'],
                'create_time'   => date('Y-m-d H:i:s'),
            ]);


            //$link = $linkModel->get($linkShort['link_id']);
            $params['company'] = $linkInfo['company_name'];
            $params['bank'] = $linkInfo['bank_name'];
            $params['business'] = $linkInfo['business_name'];
            $params['channel_id'] = $linkInfo['channel_id'];
            if( $params['link_from'] == 1){ // 0:未知 1:内部 2:外部
                $params['file_path'] = implode(',',$file_paths);
            }
            $sp = $spModel->get($params['sms_gate_id']);
            if( !$sp ){
                $this->error('通道不存在！！');
            }
            if( $sp['status'] == 3 && !$params['sms_template_id'] ){//如果是http通道，template_id必填
                $this->error('http通道，必须选择短信模板！！');
            }
            $params['shortlink'] = $shortLinkResult['data'][0]['short_url'];
            $params['sms_content'] = preg_replace($this->pattern, $params['shortlink'], $params['sms_content']);
            $params['sms_content'] = trim($params['sms_content']);
            $params['creator'] = $this->auth->getUserInfo()['username'];
            $params['create_time'] = date('YmdHis');
            // 设置短信类型及初始状态
            switch ($params['channel_from'] ){
                case 0: $params['status'] = 3;break;
                case 1: $params['status'] = 1;break;
                case 2: $params['status'] = 5;break;
                case 3: $params['status'] = 1;break;
                case 4: $params['status'] = 8;break;
            }
            //根据所选通道确认价格
            $price = Db::table("channel_pricex")->alias('p')
                ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$params['sms_gate_id'])->value('p.PRICEX');
            $params['price'] = $price;
            $result = $this->model->save($params);
            $linkShortModel->save(['task_send_num'=>$linkShort['task_send_num']+1],['id'=>$linkShort['id']]);
            if( !$result ){
                $this->success('复发任务创建失败！！');
            }
            $this->success('复发任务创建成功！！');
        }

        $this->assign('domainList',$this->domainList);
        $this->assign('spList',$spList);
        $this->assignconfig('spList',$spList);
        $this->assign('row',$row);
        return $this->view->fetch();
    }

    // 停止
    public function stop($ids){
        $taskSend = $this->model->get($ids);
        if( !$taskSend ){
            $this->error('发送任务不存在');
        }
        if( !in_array($taskSend['status'],[1,2,3]) ){
            $this->error('该任务不能停止。');
        }
        $status = 6;
        $result = $this->model->save(['status'=>$status,'update_time'=>date('Y-m-d H:i:s')],['task_id'=>$taskSend['task_id']]);
        if( $result ){
            $this->success("成功！！", null, ['status' => $status]);
        }
        $this->error("失败！！", null, ['status' => $status]);
    }

    // 开始发送
    public function start($ids){
        $taskSend = $this->model->get($ids);
        if( !$taskSend ){
            $this->error('发送任务不存在');
        }
        if( $taskSend['status'] != 10 ){
            $this->error('状态必须为“短链生成完毕”的任务才能开始发送。');
        }

        $result = $this->model->save(['status'=>3,'update_time'=>date('Y-m-d H:i:s')],['task_id'=>$taskSend['task_id']]);
        if( $result ){
            $this->success("成功！！", null, ['status' => 1]);
        }
        $this->error("失败！！", null, ['status' => '']);
    }

    //批量点击开始发送短信
    public function startAll($ids) {
        if (empty($ids)) {
            $this->error('未选择任何项');
        }

        $ids = explode(',', $ids);
        $tasks = $this->model->where(['task_id' => ['in', $ids]])->select();

        if (empty($tasks)) {
            $this->error('下载任务不存在...');
        }
        $num = 0;
        foreach ($tasks as $task) {
            if ($task['status'] != 10) {
                continue;
            }
            $data['task_id'] = $task['task_id'];
            $data['status'] = 3;
            $data['update_time'] = date('Y-m-d H:i:s');
            $list[] = $data;
            $num++;
        }
        if ($num>0){
            $res = $this->model->saveAll($list);
        }
        $this->success('成功开始发送'.$num.'条任务');
    }
}
