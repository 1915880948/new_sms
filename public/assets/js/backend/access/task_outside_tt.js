define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_outside_tt/index' + location.search,
                    add_url: 'access/task_outside_tt/add',
                    // edit_url: 'access/task_outside_tt/edit',
                    // del_url: 'access/task_outside_tt/del',
                    // multi_url: 'access/task_outside_tt/multi',
                    table: 'sms_outside_task',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id'),operate:false},
                        {field: 'channel', title: __('Channel')},
                        {field: 'product', title: __('Product')},
                        {field: 'total_number', title: __('Total_number'),operate:false},
                        {field: 'number', title: __('Number'),operate:false},
                        {field: 'startdate', title: __('Startdate'), operate:false},
                        {field: 'enddate', title: __('Enddate'), operate:false},
                        // {field: 'type', title: __('Type'),},
                        //{field: 'source_no', title: __('Source_no')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'提交',2:'处理中', 3:'IMEI查询中', 4:'处理完毕'}
                        },
                        {field: 'is_idfa', title: __('Is_idfa'),operate:false,
                            formatter: Table.api.formatter.normal,
                            searchList:{'0':'imei', '1':'idfa'}
                        },
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs  btn-download',
                                url:'access/task_outside_tt/download',
                            }],
                            formatter: Table.api.formatter.operate}
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
        }
    };
    return Controller;
});