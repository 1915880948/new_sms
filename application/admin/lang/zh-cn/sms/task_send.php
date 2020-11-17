<?php

return [
    'Task_id'             => '任务ID',
    'Title'               => '标题/备注',
    'Company_id'          => '公司ID',
    'Company'             => '公司',
    'Bank_id'             => '银行ID',
    'Bank'                => '银行',
    'Business_id'         => '业务ID',
    'Business'            => '业务',
    'Exclude_recent_sent' => '是否过滤最近发送过的',
    'Exclude_blacklist'   => '是否过滤黑名单',
    'Channel_id'          => '渠道链接ID',
    'Data_id'             => '数据模型ID',
    'Data_pack_no'        => '数据拆包号',
    'Send_limit'          => '发送上限限制,0表示不限制',
    'Send_time'           => '发送时间',
    'Sms_gate_id'         => '短信通道ID',
    'Retry_on_failure'    => '发送失败是否重试',
    'Retry_sms_gate_id'   => '重发短信通道id',
    'Retry_limit_minute'  => '0表示只针对发送失败的用户重发,无回执或回执为未知状态的用户不发',
    'Sms_template_id'     => '短信模版ID',
    'Sms_content'         => '短信内容',
    'Link'                => '业务链接',
    'Transfer_link'       => '中转长链',
    'Dynamic_shortlink'   => '是否动态短链',
    'Shortlink'           => '短链地址',
    'Channel_from'        => '发送类型',
    'Link_from'           => '链接来源',
    'Create_time'         => '创建时间',
    'Update_time'         => '更新时间',
    'Creator'             => '创建者',
    'Status'              => '状态',
    'Schedule_percent'    => '当前状态进度百分比',
    'Task_num'            => '任务原始总量',
    'Total_num'           => '未过滤的任务总量',
    'Total_send'          => '发送总量',
    'Total_receive'       => '接收总量',
    'Total_click'         => '点击总量',
    'Sp_num'              => '通道成功量',
    'Failed_num'          => '失败量',
    'Retry_status'        => '1: 待重发 2：发送中 3：发送完成',
    'Sm_task_id'          => '短链ID',
    'Price'               => '单价',
    'File_path'           => '发送文件',
    'Remark'              => '备注',
    'Phone_path'          => '空号文件',
    'Finish_time'         => '完成时间',
];
