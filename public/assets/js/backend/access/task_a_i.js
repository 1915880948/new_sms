define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_a_i/index' + location.search,
                    //add_url: 'access/task_a_i/add',
                    edit_url: 'access/task_a_i/edit',
                    // del_url: 'access/task_a_i/del',
                    multi_url: 'access/task_a_i/multi',
                    table: 'sms_ai_task',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'business_no', title: __('Business_no')},
                        {field: 'source_data_num', title: __('Source_data_num'),operate:false,},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'任务创建', 2:'出库中', 3:'源数据出库完毕', 4:'正在AI运算', 5:'AI出库完毕'},
                        },
                        {field: 'source_no', title: __('Source_no'),operate:false,},
                        {field: 'start_batch', title: __('Start_batch'),operate:false,},
                        {field: 'remark', title: __('Remark'),},

                        {field: 'source_start_time', title: __('Source_start_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'source_end_time', title: __('Source_end_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '详情',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-align-justify',
                                classname: 'btn btn-primary btn-xs btn-click',
                                click:function(data,row){
                                    //console.log(row);
                                    Fast.api.open('access/task_a_i/detail?ids='+row.id,'详情----'+'ID：'+row.id);
                                },
                            },{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs  btn-download',
                                url:'access/task_a_i/download',
                            }],
                            formatter: Table.api.formatter.operate
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
        detail: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_a_i/detail'+"?ids="+Config.ids,
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'source_data_num', title: __('Source_data_num')},
                        {field: 'source_output_name', title: __('Source_output_name'),},
                        {field: 'auc', title: __('Auc')},
                        {field: 'remark', title: __('Remark')},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
    };
    return Controller;
});