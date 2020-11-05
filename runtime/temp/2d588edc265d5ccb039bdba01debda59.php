<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:99:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\public/../application/admin\view\sms\timely\index.html";i:1604289303;s:87:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\layout\default.html";i:1588765311;s:84:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\meta.html";i:1588765311;s:86:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\script.html";i:1588765311;}*/ ?>
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
                                <div class="row">
    <div class="col-lg-offset-1 col-xs-offset-1 col-md-offset-1">
        <h3>自动短信参数配置</h3>
    </div>
<hr/>
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <div class="widget-body no-padding">
            <form id="config-form" class="form-commonsearch form-horizontal " role="form" data-toggle="validator" method="POST" action="<?php echo url('sms/timely/index'); ?>">
                <?php echo token(); ?>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">渠道号：</label>
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                        <input type="text" data-rule="required" class="form-control" name="row[channel_id]" value="<?php echo $row['channel_id']; ?>" placeholder="渠道号">
                    </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">通道：</label>
<!--                     <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">-->
<!--                         <select class="form-control selectpicker" name="row[vendor_id]">-->
<!--                             <option value="0">中国电信</option>-->
<!--                             <option value="1">中国移动</option>-->
<!--                             <option value="2">中国联通</option>-->
<!--                             <option value="3">通用</option>-->
<!--                         </select>-->
<!--                         <select class="form-control selectpicker" name="sp_info_id"></select>-->
<!--                     </div>-->
                     <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                         <select class="selectpicker" data-live-search="true" name="row[sp_info_id]" data-rule="required">
                             <option value="">请选择</option>
                              <?php if(is_array($spList) || $spList instanceof \think\Collection || $spList instanceof \think\Paginator): if( count($spList)==0 ) : echo "" ;else: foreach($spList as $key=>$vo): ?>
                              <option value="<?php echo $vo['id']; ?>" <?php echo $row['sp_info_id']==$vo['id']?'selected':''; ?>><?php echo $vo['sp_name']; ?></option>
                              <?php endforeach; endif; else: echo "" ;endif; ?>
                         </select>
                     </div>
                </div>
                 <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">短链域名：</label>
                     <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                            <?php echo build_select('row[domain_short]',$domainList,$row['domain_short'],['class'=>'form-control selectpicker','data-live-search'=>'true','data-rule'=>'required']); ?>
                     </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">短信文案：</label>
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                        <textarea class="form-control" rows="3" name="row[sms_content]" data-rule="required"><?php echo $row['sms_content']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">城市黑名单：</label>
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                        <textarea class="form-control" rows="3" name="row[city]" data-rule="required"><?php echo $row['city']; ?></textarea>
                    </div>
                    <span>以  |  分割</span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">发送时间区间：</label>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                        <input type="text" class="form-control datetimepicker" name="row[send_start_time]" value="<?php echo $row['send_start_time']; ?>" data-date-format="HH:mm:ss" data-date-use-strict="true" data-date-side-by-side="true" data-rule="required">
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                        <input type="text" class="form-control datetimepicker" name="row[send_end_time]" value="<?php echo $row['send_end_time']; ?>" data-date-format="HH:mm:ss" data-date-use-strict="true" data-date-side-by-side="true" data-rule="required">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 col-md-2	col-lg-2">自动状态：</label>
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                        <label class="radio-inline">
                            <input type="radio" name="row[send_status]"  value="1" <?php echo $row['send_status']==1?'checked':''; ?>> 开启
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="row[send_status]"  value="2" <?php echo $row['send_status']==2?'checked':''; ?>> 关闭
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-lg-offset-1 col-xs-offset-1 col-md-offset-1">
                        <button type="submit" class="btn btn-success btn-embossed"><?php echo __('OK'); ?></button>
                        <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<!--<script src="/assets/libs/bootstrap-select/dist/js/bootstrap-select.js"></script>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>