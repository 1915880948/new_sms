<?php

namespace app\admin\controller\sms;

use app\admin\model\Admin;
use app\common\controller\Backend;
use think\Db;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Env;
use think\console\command\make\Model;
use think\Session;
use think\Validate;

/**
 * 个人配置
 *
 * @icon fa fa-user
 */
class AddBatch extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        return $this->view->fetch();
    }

    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost()) {
            set_time_limit(0);
            $this->token();
            $params = $this->request->post("row/a");
            if (empty($params['file_path'])) {
                $this->error('请上传导入文件');
            }
            if (empty($params['excel_path'])) {
                $this->error('请上传任务');
            }
            $files = $file_paths = [];
            $files_list = rtrim($params['files_list'],"|");
            $files_list = explode('|', $files_list);
            $txtNum = count($files_list);
            foreach ($files_list as $file_list) {
                $filelists = explode(",",$file_list);
                $file_name = md5(trim($filelists[0]));
                $file_path = $filelists[1];
                if ($file_name) {
                    if (!empty($file_path) && !in_array($file_path, $files)) {
                        $files[$file_name] = $file_path;
                    }
                }
            }
            $filePath = $params['excel_path'];
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

            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $num = $ntxtNum = 0;
            $errorMsg = '';
            $contents = [];
            for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
                $sendTasks = $filetmpname = [];
                //渠道号
                $channel_id      = trim($currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue());
                if (!$channel_id) break;
                $sendTasks['linkInfo'] = Db::table("sms_link")->where("channel_id = '{$channel_id}'")->order('id DESC')->find();
                if (!$sendTasks['linkInfo']) {
                    $errorMsg .= "第{$currentRow}行渠道号不存在<br/>";
                    continue;
                }
                //中转域名
                $sendTasks['domain'] = trim($currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue());
                if($sendTasks['domain'] != 'cca.gmget.co' && $sendTasks['domain'] != 'cca.smget.co') {
                    $errorMsg .= "第{$currentRow}行中转域名不存在<br/>";
                    continue;
                }
                //短链域名
                $sendTasks['domain_short'] = trim($currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue());
                if($sendTasks['domain_short'] !='n0x' && $sendTasks['domain_short'] !='h0e' && $sendTasks['domain_short'] !='9oj' && $sendTasks['domain_short'] !='5oj' && $sendTasks['domain_short'] !='vo4' && $sendTasks['domain_short'] !='j0q' && $sendTasks['domain_short'] !='p0o' && $sendTasks['domain_short'] !='b4m' && $sendTasks['domain_short'] !='h8r' && $sendTasks['domain_short'] !='7j0' && $sendTasks['domain_short'] !='4g3' && $sendTasks['domain_short'] !='v1i' && $sendTasks['domain_short'] !='f3u') {
                    $errorMsg .= "第{$currentRow}行短链域名不存在<br/>";
                    continue;
                }
                //短链说明
                $sendTasks['remark'] = trim($currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue());
                if (!$sendTasks['remark']) continue;

                //先判断文件
                $filetmp = trim($currentSheet->getCellByColumnAndRow(10, $currentRow)->getValue());
                $filetmps = explode(',',$filetmp);
                $fileCount = count($filetmps);
                $ntxtNum += $fileCount;
                for ($j=0;$j<$fileCount;$j++){
                    $efilename = md5(trim($filetmps[$j]));
                    if (isset($files[$efilename])) {
                        $filetmpname[] = $files[$efilename];
                    }
                }
                $sendTasks['file_path'] = implode(',', $filetmpname);
                if ($fileCount != count($filetmpname)){
                    $errorMsg .= "第{$currentRow}行文件名不存在<br/>";
                    continue;
                }
                //是否单点
                $sendTasks['is_single'] = trim($currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue());
                if ($sendTasks['is_single'] == '是' || $sendTasks['is_single'] == '特定'){
                    $sendTasks['dynamic_shortlink'] = trim($currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue());
                    if($sendTasks['domain_short'] !='n0x' && $sendTasks['domain_short'] !='h0e' && $sendTasks['domain_short'] !='9oj' && $sendTasks['domain_short'] !='5oj' && $sendTasks['domain_short'] !='vo4' && $sendTasks['domain_short'] !='j0q' && $sendTasks['domain_short'] !='p0o' && $sendTasks['domain_short'] !='b4m' && $sendTasks['domain_short'] !='h8r' && $sendTasks['domain_short'] !='7j0' && $sendTasks['domain_short'] !='4g3' && $sendTasks['domain_short'] !='v1i' && $sendTasks['domain_short'] !='f3u') {
                        $errorMsg .= "第{$currentRow}行单点域名不存在<br/>";
                        continue;
                    }
                }
                //短信文案
                $sendTasks['sms_content'] = trim($currentSheet->getCellByColumnAndRow(7, $currentRow)->getValue());
                $sendTime = trim($currentSheet->getCellByColumnAndRow(8, $currentRow)->getValue());
                if ($sendTime == "=NOW()"){
                    $sendTasks['send_time'] = date('Y-m-d H:i');
                }else{
                    $sendTasks['send_time'] = gmdate('Y-m-d H:i',intval(($sendTime - 25569) * 3600 * 24));
                }
                $sms_gate_id = trim($currentSheet->getCellByColumnAndRow(9, $currentRow)->getValue());
                $smsGates = explode('-',$sms_gate_id);
                $abc = $smsGates[count($smsGates)-1];
                $sendTasks['sms_gate_id'] = Db::table('sms_sp_info')->where("sp_no = '{$abc}'")->order('id DESC')->value('id');
                if (empty($sendTasks['sms_gate_id'])){
                    $errorMsg .= "第{$currentRow}行通道名称不存在<br/>";
                    continue;
                }
                $sendTasks['phone_space'] = trim($currentSheet->getCellByColumnAndRow(11, $currentRow)->getValue());
                //小号数量
                $sendTasks['small'] = trim($currentSheet->getCellByColumnAndRow(12, $currentRow)->getValue());
                $contents[] = $sendTasks;
            }
            if ($txtNum != $ntxtNum) $errorMsg .= "上传的txt文件个数与excel中不同";
            if ($errorMsg) {
                $this->error($errorMsg);
            }
            //业务处理
            if (!empty($contents)) {
                foreach ($contents as $value) {
                    $shortTasks = $sends = [];
                    $linkShortModel = new \app\admin\model\sms\LinkShort();
                    $linkInfo = $value['linkInfo'];

                    //中转域名
                    $domain = $value['domain'];
                    //短链域名
                    $domain_short = $value['domain_short'];
                    //短链说明
                    $remark = $value['remark'];
                    $linkShortLastID = $linkShortModel->max('id');
                    $nextId = $linkShortLastID + 1;
                    $transfer_link = 'http://' . $domain . '/link.php?id=' . $nextId;
                    $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . $domain_short . '&url=' . rawurlencode($transfer_link);
                    //$apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598741&dm=" . $domain_short . '&url=' . rawurlencode($transfer_link);
                    //$apiUrl = 'http://crm.test.com/short.php?key=68598741&dm=' . $domain_short . '&url=' . rawurlencode($transfer_link);
                    $shortLinkResult = httpRequest($apiUrl, 'GET');
                    $shortLinkResult = json_decode($shortLinkResult, true);
                    if (empty($shortLinkResult['data'][0])) {
                        $this->error('短链生成失败，请稍后重试..');
                    }
                    $shortLinkResult['data']['shortlink'] = $shortLinkResult['data'][0]['short_url'];
                    $result = $linkShortModel->save([
                        'remark'        => $remark,
                        'link_id'       => $linkInfo['id'],
                        'business_link' => $linkInfo['link'],
                        'transfer_link' => $transfer_link,
                        'short_link'    => $shortLinkResult['data']['shortlink'],
                        'creator'       => $this->auth->getUserInfo()['username'],
                        'create_time'   => date('Y-m-d H:i:s'),
                    ]);
                    if (!$result) $this->error('短链任务生成失败，请稍后重试..');

                    //开始发短信
                    $sends['title'] = $remark;
                    //空号埋点
                    if (!empty($value['phone_space'])){
                        $phone = $lphone = [];
                        //根据文件标题选择运营商
                        $titleList = explode("-",$remark);
                        if ($titleList[3] == "SYD"){
                            $pwhere = "card_type = '中国移动'";
                        }elseif ($titleList[3] == "SLT"){
                            $pwhere = "card_type = '中国联通'";
                        }elseif ($titleList[3] == "SDX"){
                            $pwhere = "card_type = '中国电信'";
                        }else{
                            $pwhere = "1=1";
                        }
                        $phones = Db::table('phone_info')->field('distinct phone')->where($pwhere)->orderRaw('rand()')->limit($value['phone_space'])->select();
                        if (!empty($phones)) {
                            foreach ($phones as $svalue) {
                                $phone[] = $svalue['phone'];
                            }
                            $phone = array_values(array_unique($phone));
                            $tphone = implode(',', $phone);
                            $endata = curl_encrypt($tphone, 'enc');
                            $endata = json_decode($endata, true);
                            foreach ($endata['data'] as $evalue) {
                                $lphone[] = $evalue;
                            }
                            $sends["phone_path"] = date('Y-m-d') . '/phone' . time() . rand(100, 999) . '.txt';
                            if (!is_dir(Env::get('file.FILE_ROOT_DIR') . '/' . date('Y-m-d'))) {
                                @mkdir(Env::get('file.FILE_ROOT_DIR') . '/' . date('Y-m-d'));
                            }
                            $phonefile = fopen(Env::get('file.FILE_ROOT_DIR'). '/' . $sends['phone_path'], 'w') or die("Unable to open file!");
                            fwrite($phonefile, implode("\n", $lphone));
                            fclose($phonefile);
                        }
                    }
                    //小号埋点
                    if (!empty($value['small']) && $value['small']<=5){
                        //根据文件标题选择运营商
                        $titleList = explode("-",$remark);
                        if ($titleList[3] == "SYD"){
                            $pwhere = "card_type = '中国移动'";
                        }elseif ($titleList[3] == "SLT"){
                            $pwhere = "card_type = '中国联通'";
                        }elseif ($titleList[3] == "SDX"){
                            $pwhere = "card_type = '中国电信'";
                        }else{
                            $pwhere = "1=1";
                        }
                        $smallphones = Db::table('phone_small_info')->field('distinct phone')->where($pwhere)->orderRaw('rand()')->limit($value['small'])->select();
                        if (!empty($smallphones)) {
                            foreach ($smallphones as $smvalue) {
                                $smalls[] = $smvalue['phone'];
                            }
                            $sends["small"] = date('Y-m-d') . '/small' . time() . rand(100, 999) . '.txt';
                            if (!is_dir(Env::get('file.FILE_ROOT_DIR') . '/' . date('Y-m-d'))) {
                                @mkdir(Env::get('file.FILE_ROOT_DIR') . '/' . date('Y-m-d'));
                            }
                            $smallfile = fopen(Env::get('file.FILE_ROOT_DIR') . '/' . $sends['small'], 'w') or die("Unable to open file!");
                            fwrite($smallfile, implode("\n", $smalls));
                            fclose($smallfile);
                        }
                        unset($smalls);
                    }
                    //是否单点
                    $is_single = $value['is_single'];
                    if ($is_single == '是' || $is_single == '特定') {
                        $sends['status'] = 1;
                        $dynamic_shortlink = $value['dynamic_shortlink'];
                        // 设置单点短链
                        switch ($dynamic_shortlink){
                            case 'd0e': $sends['dynamic_shortlink'] = 4;break;
                            case '7d0': $sends['dynamic_shortlink'] = 5;break;
                            case 'o8d': $sends['dynamic_shortlink'] = 6;break;
                            case '0i4': $sends['dynamic_shortlink'] = 7;break;
                            case 'q4f': $sends['dynamic_shortlink'] = 8;break;
                            case 'g0c': $sends['dynamic_shortlink'] = 9;break;
                            case 'z0k': $sends['dynamic_shortlink'] = 10;break;
                            case 'q0r': $sends['dynamic_shortlink'] = 11;break;
                            case 'n0x': $sends['dynamic_shortlink'] = 12;break;
                            case 'h0e': $sends['dynamic_shortlink'] = 13;break;
                            case 'o4c': $sends['dynamic_shortlink'] = 14;break;
                            case '9oj': $sends['dynamic_shortlink'] = 15;break;
                            case '5oj': $sends['dynamic_shortlink'] = 16;break;
                            case 'vo4': $sends['dynamic_shortlink'] = 17;break;
                            case '4a6': $sends['dynamic_shortlink'] = 18;break;
                            case 'j0q': $sends['dynamic_shortlink'] = 19;break;
                            case 'p0o': $sends['dynamic_shortlink'] = 20;break;
                            case 'b4m': $sends['dynamic_shortlink'] = 21;break;
                            case 'h8r': $sends['dynamic_shortlink'] = 22;break;
                            case '7j0': $sends['dynamic_shortlink'] = 23;break;
                            case '4g3': $sends['dynamic_shortlink'] = 24;break;
                            case 'v1i': $sends['dynamic_shortlink'] = 25;break;
                            case 'f3u': $sends['dynamic_shortlink'] = 26;break;
                            default: $sends['dynamic_shortlink'] = 3;
                        }
                    } else {
                        $sends['status'] = 3;
                    }

                    //短信文案
                    $sms_content = $value['sms_content'];
                    $pattern = '/http[s]?:\\/\\/[-.=%&\\?\\w\\/]+/';
                    $sends['sms_content'] = preg_replace($pattern, $shortLinkResult['data']['shortlink'], $sms_content);
                    $sends['sms_content'] = trim($sends['sms_content']);
                    //电信内容链接去掉http(s)头
                    /*$shortlink = preg_replace("/^http[s]?:\\/\\//", "", $shortTasks['short_link']);
                    $pattern = '/http[s]?:\\/\\/[-.=%&\\?\\w\\/]+/';
                    $sends['sms_content'] = preg_replace($pattern, $shortlink, $sms_content);
                    $sends['sms_content'] = trim($sends['sms_content']);*/

                    $sms_content_len = mb_strlen($sends['sms_content'], 'UTF-8');
                    $more_len = $sms_content_len - 70;
                    if ($more_len > 0) {
                        //$data['sms_content'] = str_replace([" ", "\n", "\r", "\t"], '', $data['sms_content'], $more_len);

                        $sms_content_len = mb_strlen($sends['sms_content'], 'UTF-8');
                        if ($sms_content_len > 77) {
                            continue;
                        }
                    }
                    //发送时间
                    $sends['send_time'] = $value['send_time'];
                    $sends['sm_task_id'] = $nextId;
                    $sends['data_id'] = 0;
                    $sends['shortlink'] = $shortLinkResult['data']['shortlink'];
                    $sends['link_from'] = $params['link_from'];
                    $sends['encrypt'] = $params['encrypt'];
                    $sends['exclude_blacklist'] = 1;
                    $sends['sms_gate_id'] = $value['sms_gate_id'];
                    $sends['sms_template_id'] = 0;
                    $sends['file_path'] = str_replace(Env::get('file.FILE_ROOT_DIR').'/', '', $value['file_path']);
                    switch ($is_single){
                        case '特定': $sends['channel_from'] = 1;break;
                        case '是': $sends['channel_from'] = 3;break;
                        default: $sends['channel_from'] = 0;
                    }
                    if($is_single == '特定'){
                        $sends['channel_from'] = 1;
                    }

                    //获取业务三要素
                    $linkModel = new \app\admin\model\sms\Link();
                    $basecomList = $linkModel->get($linkInfo['id']);
                    $sends['company'] = $basecomList['company_name'];
                    $sends['bank'] = $basecomList['bank_name'];
                    $sends['business'] = $basecomList['business_name'];
                    $sends['channel_id'] = $basecomList['channel_id'];
                    //根据所选通道确认价格
                    $sends['price'] = $price = Db::table("channel_pricex")->alias('p')
                        ->join(['sms_sp_info'=>'s'], 'p.SP_ID=s.remote_account')->where("s.id",$sends['sms_gate_id'])->value('p.PRICEX');

                    $sends['creator'] = $this->auth->getUserInfo()['username'];
                    $sends['create_time'] = date('YmdHis');
                    $TaskSendModel = new \app\admin\model\sms\TaskSend();
                    $res = $TaskSendModel->save($sends);
                    if ($res) $num++;
                }
            }
            //---------------------------------------------
            $this->success('任务创建成功！！',"sms/task_send/index");
        }
        return;
    }
}
