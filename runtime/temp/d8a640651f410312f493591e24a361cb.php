<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:101:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\public/../application/admin\view\sms\task_send\edit.html";i:1603942915;s:87:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\layout\default.html";i:1588765311;s:84:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\meta.html";i:1588765311;s:86:"C:\Apps\PhpStudy2018\PHPTutorial\WWW\new_sms\application\admin\view\common\script.html";i:1588765311;}*/ ?>
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
                                <form id="edit-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">

    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Title'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-title" class="form-control" name="row[title]" type="text" value="<?php echo htmlentities($row['title']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Company_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-company_id" data-rule="required" data-source="company/index" class="form-control selectpage" name="row[company_id]" type="text" value="<?php echo htmlentities($row['company_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Company'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-company" class="form-control" name="row[company]" type="text" value="<?php echo htmlentities($row['company']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Bank_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-bank_id" data-rule="required" data-source="bank/index" class="form-control selectpage" name="row[bank_id]" type="text" value="<?php echo htmlentities($row['bank_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Bank'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-bank" class="form-control" name="row[bank]" type="text" value="<?php echo htmlentities($row['bank']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Business_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-business_id" data-rule="required" data-source="business/index" class="form-control selectpage" name="row[business_id]" type="text" value="<?php echo htmlentities($row['business_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Business'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-business" class="form-control" name="row[business]" type="text" value="<?php echo htmlentities($row['business']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Exclude_recent_sent'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-exclude_recent_sent" data-rule="required" class="form-control" name="row[exclude_recent_sent]" type="number" value="<?php echo htmlentities($row['exclude_recent_sent']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Exclude_blacklist'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-exclude_blacklist" data-rule="required" class="form-control" name="row[exclude_blacklist]" type="number" value="<?php echo htmlentities($row['exclude_blacklist']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Channel_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-channel_id" data-rule="required" data-source="channel/index" class="form-control selectpage" name="row[channel_id]" type="text" value="<?php echo htmlentities($row['channel_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Data_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-data_id" data-rule="required" data-source="data/index" class="form-control selectpage" name="row[data_id]" type="text" value="<?php echo htmlentities($row['data_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Data_pack_no'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-data_pack_no" class="form-control" name="row[data_pack_no]" type="number" value="<?php echo htmlentities($row['data_pack_no']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Send_limit'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-send_limit" data-rule="required" class="form-control" name="row[send_limit]" type="number" value="<?php echo htmlentities($row['send_limit']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Send_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-send_time" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[send_time]" type="text" value="<?php echo $row['send_time']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sms_gate_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sms_gate_id" data-rule="required" data-source="sms/gate/index" class="form-control selectpage" name="row[sms_gate_id]" type="text" value="<?php echo htmlentities($row['sms_gate_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Retry_on_failure'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-retry_on_failure" data-rule="required" class="form-control" name="row[retry_on_failure]" type="number" value="<?php echo htmlentities($row['retry_on_failure']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Retry_sms_gate_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-retry_sms_gate_id" data-rule="required" data-source="retry/sms/gate/index" class="form-control selectpage" name="row[retry_sms_gate_id]" type="text" value="<?php echo htmlentities($row['retry_sms_gate_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Retry_limit_minute'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-retry_limit_minute" class="form-control" name="row[retry_limit_minute]" type="number" value="<?php echo htmlentities($row['retry_limit_minute']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sms_template_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sms_template_id" data-rule="required" data-source="sms/template/index" class="form-control selectpage" name="row[sms_template_id]" type="text" value="<?php echo htmlentities($row['sms_template_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sms_content'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sms_content" class="form-control" name="row[sms_content]" type="text" value="<?php echo htmlentities($row['sms_content']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Link'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-link" class="form-control" name="row[link]" type="text" value="<?php echo htmlentities($row['link']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Transfer_link'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-transfer_link" class="form-control" name="row[transfer_link]" type="text" value="<?php echo htmlentities($row['transfer_link']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Dynamic_shortlink'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-dynamic_shortlink" data-rule="required" class="form-control" name="row[dynamic_shortlink]" type="number" value="<?php echo htmlentities($row['dynamic_shortlink']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Shortlink'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-shortlink" class="form-control" name="row[shortlink]" type="text" value="<?php echo htmlentities($row['shortlink']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Channel_from'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-channel_from" class="form-control" name="row[channel_from]" type="number" value="<?php echo htmlentities($row['channel_from']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Link_from'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-link_from" data-rule="required" class="form-control" name="row[link_from]" type="number" value="<?php echo htmlentities($row['link_from']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Create_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-create_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[create_time]" type="text" value="<?php echo $row['create_time']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Update_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-update_time" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[update_time]" type="text" value="<?php echo $row['update_time']?datetime($row['update_time']):''; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Creator'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-creator" class="form-control" name="row[creator]" type="text" value="<?php echo htmlentities($row['creator']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-status" data-rule="required" class="form-control" name="row[status]" type="number" value="<?php echo htmlentities($row['status']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Schedule_percent'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-schedule_percent" class="form-control" name="row[schedule_percent]" type="number" value="<?php echo htmlentities($row['schedule_percent']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Task_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-task_num" class="form-control" name="row[task_num]" type="number" value="<?php echo htmlentities($row['task_num']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Total_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-total_num" class="form-control" name="row[total_num]" type="number" value="<?php echo htmlentities($row['total_num']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Total_send'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-total_send" class="form-control" name="row[total_send]" type="number" value="<?php echo htmlentities($row['total_send']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Total_receive'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-total_receive" class="form-control" name="row[total_receive]" type="number" value="<?php echo htmlentities($row['total_receive']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Total_click'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-total_click" class="form-control" name="row[total_click]" type="number" value="<?php echo htmlentities($row['total_click']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sp_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sp_num" class="form-control" name="row[sp_num]" type="number" value="<?php echo htmlentities($row['sp_num']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Failed_num'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-failed_num" class="form-control" name="row[failed_num]" type="number" value="<?php echo htmlentities($row['failed_num']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Retry_status'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-retry_status" class="form-control" name="row[retry_status]" type="number" value="<?php echo htmlentities($row['retry_status']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Sm_task_id'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-sm_task_id" data-rule="required" data-source="sm/task/index" class="form-control selectpage" name="row[sm_task_id]" type="text" value="<?php echo htmlentities($row['sm_task_id']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Price'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-price" class="form-control" name="row[price]" type="text" value="<?php echo htmlentities($row['price']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('File_path'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-file_path" data-rule="required" class="form-control" name="row[file_path]" type="text" value="<?php echo htmlentities($row['file_path']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Remark'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-remark" class="form-control" name="row[remark]" type="text" value="<?php echo htmlentities($row['remark']); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Finish_time'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-finish_time" data-rule="required" class="form-control datetimepicker" data-date-format="YYYY-MM-DD HH:mm:ss" data-use-current="true" name="row[finish_time]" type="text" value="<?php echo $row['finish_time']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2"><?php echo __('Phone_path'); ?>:</label>
        <div class="col-xs-12 col-sm-8">
            <input id="c-phone_path" class="form-control" name="row[phone_path]" type="text" value="<?php echo htmlentities($row['phone_path']); ?>">
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version']); ?>"></script>
    </body>
</html>