<?php

namespace app\admin\controller\sms;

use app\admin\model\basic\Bank;
use app\admin\model\basic\Business;
use app\admin\model\basic\Company;
use app\common\controller\Backend;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use think\Config;
use think\Db;
use think\Env;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 渠道管理
 *
 * @icon fa fa-circle-o
 */
class Link extends Backend
{
    
    /**
     * Link模型对象
     * @var \app\admin\model\sms\Link
     */
    protected $model = null;
    protected $company = [];
    protected $bank = [];
    protected $business = [];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\sms\Link;
        $this->company = (new Company())->field('id,company_name')->where('status',1)->select();
        $this->company = array_combine(array_column($this->company,'id'),array_column($this->company,'company_name'));
        $this->bank = (new Bank())->field('id,bank_name')->where('status',1)->select();
        $this->bank = array_combine(array_column($this->bank,'id'),array_column($this->bank,'bank_name'));
        $this->business = (new Business())->field('id,business_name')->where('status',1)->select();
        $this->business = array_combine(array_column($this->business,'id'),array_column($this->business,'business_name'));

    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");

            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $params['company_name'] = $this->company[$params['company_id']];
                $params['bank_name'] = $this->bank[$params['bank_id']];
                $params['business_name'] = $this->business[$params['business_id']];
                $params['creator'] = $this->auth->getUserInfo()['username'];
                $params['create_time'] = date("Y-m-d H:i:s");
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
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
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('company',$this->company);
        $this->view->assign('bank',$this->bank);
        $this->view->assign('business',$this->business);

        return $this->view->fetch();

    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['company_name'] = $this->company[$params['company_id']];
                $params['bank_name'] = $this->bank[$params['bank_id']];
                $params['business_name'] = $this->business[$params['business_id']];
                $params['creator'] = $this->auth->getUserInfo()['username'];
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $this->view->assign('company',$this->company);
        $this->view->assign('bank',$this->bank);
        $this->view->assign('business',$this->business);
        $this->view->assign("row", $row);
        return $this->view->fetch();

    }

    public function import()
    {

        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        //$filePath = ROOT_PATH . DS . 'public' . DS . $file;
        $filePath = $file;
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
        $this->company = array_flip($this->company);
        $this->bank = array_flip($this->bank);
        $this->business = array_flip($this->business);

        $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
        $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        //$maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
        for ($currentRow  = 2; $currentRow <= $allRow; $currentRow++) {
            $company      = $currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue();
            $bank         = $currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue();
            $business     = $currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue();
            $link         = $currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue();
            $channel_id   = $currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue();
            $channel_code = $currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue();
            $remark       = $currentSheet->getCellByColumnAndRow(7, $currentRow)->getValue();

            if( !isset($this->company[$company]) )  $this->error("第$currentRow 行，公司名称不存在！");
            if( !isset($this->bank[$bank]) )  $this->error("第$currentRow 行，银行名称不存在！");
            if( !isset($this->business[$business]) )  $this->error("第$currentRow 行，业务名称不存在！");
            $values = [
                'channel_id'    => $channel_id,
                'channel_code'  => $channel_code,
                'link'          => $link,
                'company_id'    => $this->company[$company],
                'company_name'  => $company,
                'bank_id'       => $this->bank[$bank],
                'bank_name'     => $bank,
                'business_id'   => $this->business[$business],
                'business_name' => $business,
                'remark'        => $remark,
                'creator'       => $this->auth->getUserInfo()['username'],
                'create_time'   => date('Y-m-d H:i:s'),
                'status'        => 1,
            ];
            $insert[] = $values;
            $compare[] = $channel_id.$this->company[$company].$this->bank[$bank].$this->business[$business];
            //数据不允许重复
            $result = $this->model->field('id')->where([
                'channel_id'=>$channel_id,
                'company_id'=>$this->company[$company],
                'bank_id'=>$this->bank[$bank],
                'business_id'=>$this->business[$business]
            ])->find();
            if ( $result ){
                $this->error("新增失败，第{$currentRow}行数据库里已经存在，请不要重复添加");
            }
        }
        if( count($compare) != count(array_unique($compare)) ){
            $this->error("新增失败，公司+银行+业务+渠道码不允许有重复，文件中有重复，请检查");
        }
        $result = $this->model->saveAll($insert);
        if( !$result ){
            $this->error('导入失败！！');
        }
        $this->success();
    }

    public function short(){
        $link_id = $this->request->get('link_id');
        $offset  = $this->request->get("offset");
        $limit   = $this->request->get("limit");
        if( $link_id ){
            $model = new \app\admin\model\sms\LinkShort();
            $rows = $model->where('link_id',$link_id)->order('id','desc')->limit($offset,$limit)->select();
            $total = $model->where('link_id',$link_id)->order('id','desc')->count();

            return json(['total'=>$total,"rows"=>$rows]);

        }
        return $this->view->fetch();
    }

    public function short_add(){
        $link_id = $this->request->get('link_id');
        $linkShortModel = new \app\admin\model\sms\LinkShort();
        $link = $this->model->get($link_id);
        if( $this->request->isPost() ){
            $params = $this->request->post();
            //print_r( $params ); die;
            $linkShortLastID = $linkShortModel->max('id');
            $transfer_link =  'http://'.$params['transfer_link'].'/link.php?id='.($linkShortLastID+1);
            $apiUrl = "http://".Env::get('sms_short.host')."/short.php?key=68598736&dm=" . trim($params['short_link']) . '&url=' . rawurlencode($transfer_link);
            $shortLinkResult = httpRequest($apiUrl, 'GET');
            $shortLinkResult = json_decode($shortLinkResult, true);
            if (empty($shortLinkResult['data'][0])) {
                return json(['data'=>['msg'=>'短链生成失败，请稍后重试..']]);
            }
            $result = $linkShortModel->save([
                'remark'        => $params['remark'],
                'link_id'       => $link['id'],
                'business_link' => $link['link'],
                'transfer_link' => $transfer_link,
                'short_link'    => $shortLinkResult['data'][0]['short_url'],
                'creator'       => $this->auth->getUserInfo()['username'],
                'create_time'   => date('Y-m-d H:i:s'),
            ]);
            if( !$result ){
                return json(['data'=>['msg'=>'提交失败']]);
            }
            return json(['data'=>['msg'=>'成功！！'],'code'=>1]);
        }
        $transDomain = Config::get('transDomain');
        $domainList = Config::get('domainList');
        $this->assign('link',$link);
        $this->assign('transDomain',$transDomain);
        $this->assign('domainList',$domainList);
        return $this->view->fetch();
    }

}
