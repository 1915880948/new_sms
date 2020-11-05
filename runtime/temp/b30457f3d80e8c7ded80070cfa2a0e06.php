<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:100:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\public/../application/admin\view\basic\sheet\index.html";i:1600781315;s:87:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\layout\default.html";i:1588765311;s:84:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\meta.html";i:1588765311;s:86:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\script.html";i:1588765311;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <div class="panel panel-default panel-intro">
    <?php echo build_heading(); ?>

    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" title="<?php echo __('Refresh'); ?>" ><i class="fa fa-refresh"></i> </a>
                        <a href="javascript:;" class="btn btn-success btn-add <?php echo $auth->check('basic/sheet/add')?'':'hide'; ?>" title="<?php echo __('Add'); ?>" ><i class="fa fa-plus"></i> <?php echo __('Add'); ?></a>
                        <a href="javascript:;" class="btn btn-success btn-edit btn-disabled disabled <?php echo $auth->check('basic/sheet/edit')?'':'hide'; ?>" title="<?php echo __('Edit'); ?>" ><i class="fa fa-pencil"></i> <?php echo __('Edit'); ?></a>
                        <a href="javascript:;" class="btn btn-danger btn-del btn-disabled disabled <?php echo $auth->check('basic/sheet/del')?'':'hide'; ?>" title="<?php echo __('Delete'); ?>" ><i class="fa fa-trash"></i> <?php echo __('Delete'); ?></a>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover table-nowrap"
                           data-operate-edit="<?php echo $auth->check('basic/sheet/edit'); ?>" 
                           data-operate-del="<?php echo $auth->check('basic/sheet/del'); ?>" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script id="customsearch" type="text/html">
    <!--form表单必须添加form-commsearch这个类-->
    <form action="" class="form-commonsearch">
        <div style="border-radius:2px;margin-bottom:10px;background:#f5f5f5;padding:15px 20px;">
            <!--            <h4>自定义搜索表单</h4>-->
            <!--            <hr>-->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Number_head'); ?>:</label>
                        <!--隐式的operate操作符，必须携带一个class为operate隐藏的文本框,且它的data-name="字段",值为操作符-->
                        <input class="operate" type="hidden" data-name="number_head" value="like"/>
                        <div >
                            <input class="form-control" type="text" name="number_head" placeholder="请输入号段--最长7位" value=""/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Province_id'); ?>:</label>
                        <div class="row" data-toggle="cxselect" data-selects="province,city">
                            <div class="col-xs-6">
                                <select class="province form-control" name="province_id" data-url="basic/province/getProvince" data-live-search="true"></select>
                            </div>
                            <div class="col-xs-6">
                                <select class="city form-control" name="city_id" data-url="basic/province/getCity" data-live-search="true"
                                        data-query-name="pid"></select>
                            </div>
                            <input type="hidden" class="operate" data-name="province_id" value="="/>
                            <input type="hidden" class="operate" data-name="city_id" value="="/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Vendor_id'); ?>:</label>
                        <input type="hidden" class="operate" data-name="vendor_id" value="="/>
                        <div>
                            <select id="c-vendor_id" class="form-control" name="vendor_id">
                                <option value="" selected>请选择</option>
                                <?php if(is_array($vendorArr) || $vendorArr instanceof \think\Collection || $vendorArr instanceof \think\Paginator): if( count($vendorArr)==0 ) : echo "" ;else: foreach($vendorArr as $key=>$vo): ?>
                                <option value="<?php echo $key; ?>"><?php echo $vo; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="form-group">
                        <label class="control-label"></label>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="submit" class="btn btn-success btn-block" value="提交"/>
                            </div>
                            <div class="col-xs-6">
                                <input type="reset" class="btn btn-primary btn-block" value="重置"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</script>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>