<?php

namespace app\admin\controller\data_deal;

use app\admin\model\basic\Bank;
use app\admin\model\basic\Business;
use app\admin\model\basic\Company;
use app\common\controller\Backend;
use think\Env;

/**
 * 历史数据管理
 *
 * @icon fa fa-circle-o
 */
class FilterHistory extends Backend
{
    
    /**
     * FilterHistory模型对象
     * @var \app\admin\model\data_deal\FilterHistory
     */
    protected $model = null;
    protected $company = [];
    protected $bank = [];
    protected $business = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\data_deal\FilterHistory;
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
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
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

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if( $this->request->isPost() ){
            $params= $this->request->post('row/a');
            if( !$params['use_time'] ){
                $this->error('请选择建模源ID！');
            }
            if( !$params['file_path']){
                $this->error('文件必须上传！');
            }
            $data['creator'] = $this->auth->getUserInfo()['username'];;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['use_time'] = $params['use_time'];
            $data['company'] = $this->company[$params["company_id"]];
            $data['bank'] = $this->bank[$params["bank_id"]];
            $data['business'] = $this->business[$params["business_id"]];
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
                $model = new \app\admin\model\data_deal\FilterHistory();
                $data['source_name'] = $file_names[$i];
                $data['total_num'] = $total_nums[$i];
                $result = $model->save($data);
                $res = $model->getLastInsID();
                $lastPath = Env::get('file.FILE_ROOT_DIR') . '/'.'black_file';
                $copyPath = Env::get('file.FILE_ROOT_DIR') . '/'.'black_list/b';
                if(!is_dir($lastPath)){
                    @mkdir($lastPath);
                }
                $lastFile = $lastPath.'/'.$res;
                $copyFile = $copyPath.'/'.$res;
                $abc = copy($files[$i],$lastFile);
                $abc = copy($files[$i],$copyFile);
                $model->save(['file_name'=>$res],['id'=>$res]);
            }
            if( !$result ){
                $this->success('数据入库失败！！');
            }
            $this->success('数据入库成功！！');
        }
        $this->view->assign('company',$this->company);
        $this->view->assign('bank',$this->bank);
        $this->view->assign('business',$this->business);
        return $this->view->fetch();
    }

    //下载
    public function download($ids){

        $where = 'id = ' . $ids;

        $fetchList = $this->model->where($where)->find();
        if (empty($fetchList)) {
            $this->error('请选择需要下载的历史数据。','',15);
        } else {
            $filepath = Env::get('file.FILE_ROOT_DIR') . '/' .'black_list/b/';
            $filename = $ids;
            $txtfile = urlencode($fetchList['source_name']);
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
            $file = fopen ( $filepath.$filename, "rb" );
            echo fread ( $file, filesize ( $filepath.$filename ) );
            fclose ( $file );
            exit ();
        }
    }

    public function select(){
        $params = $this->request->get();

        if ($this->request->isAjax()) {
            $params = $this->request->get();
            $myWhere = [];
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

        $this->assignconfig('params',$params);
        return $this->view->fetch();
    }
}
