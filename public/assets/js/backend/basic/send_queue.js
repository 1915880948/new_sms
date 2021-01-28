define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic/send_queue/index' + location.search,
                    edit_url: 'basic/send_queue/edit',
                    //del_url: 'basic/send_queue/del',
                    multi_url: 'basic/send_queue/multi',
                    table: 'sms_task_send',
                }
            });
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'task_id',
                sortName: 'task_id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                // showColumns: false,                 //3.字段显示
                // showExport: false,                  //4.导出按钮
                commonSearch: true,                //5.通用搜索框
                pagination: true,                   //6.是否显示分页条
                // onlyInfoPagination: true,           //7.只显示总数据数
                // showHeader: false,                  //8.是否显示列头
                // paginationVAlign: 'top',            //9.指定分页条垂直位置
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'title', title: __('Title'),operate:'like'},
                        {field: 'link_from', title: __('Link_from'),
                            formatter: Table.api.formatter.normal,
                            searchList:{0:'未知',1:'内部',2:'外部'},operate:false,visible: false
                        },
                        {field: 'sm_task_id', title: __('Sm_task_id')},
                        {field: 'shortlink', title: __('Shortlink'),formatter:Table.api.formatter.url,operate:false},
                        {field: 'send_time', title: __('Send_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'create_time', title: __('Create_time'), operate:false},
                        {field: 'channel_id', title: __('Channel_id'),operate:'like'},
                        {field: 'company', title: __('Company'),operate:'like'},
                        {field: 'bank', title: __('Bank'),operate:'like'},
                        {field: 'business', title: __('Business'),operate:'like'},
                        {field: 'sp_name', title: __('Sp_name'),operate:false},
                        {field: 'task_num', title: __('Task_num'),operate:false},
                        {field: 'creator', title: __('Creator'),operate:'like',visible: false},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.status,
                            searchList:{1:'待生成短链',2:'生成动态短链中',3:'等待发送',4:'发送中',5:'发送完成',6:'已中止',7:'已删除',8 :'无需发送', 9 :'暂存',
                                10: '短链生成完毕',
                                11: '创建超信任务失败',
                                12: '创建超信任务成功',
                                13: '超信任务添加手机号中',
                                14: '超信任务添加手机号成功',
                                15: '超信任务添加手机号失败',
                                16: '超信任务提交失败',
                                17: '入队列完毕',
                                18: '写入发送队列中',
                                19: '通道连接异常',},
                        },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
           Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        },
    };
    return Controller;
});