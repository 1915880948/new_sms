define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_a_i/index' + location.search,
                    add_url: 'access/task_a_i/add',
                    edit_url: 'access/task_a_i/edit',
                    del_url: 'access/task_a_i/del',
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
                        {field: 'source_data_num', title: __('Source_data_num')},
                        {field: 'status', title: __('Status')},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'start_date', title: __('Start_date')},
                        {field: 'end_date', title: __('End_date')},
                        {field: 'start_batch', title: __('Start_batch')},
                        {field: 'source_output_name', title: __('Source_output_name')},
                        {field: 'ai_output_name', title: __('Ai_output_name')},
                        {field: 'source_path', title: __('Source_path')},
                        {field: 'ai_path', title: __('Ai_path')},
                        {field: 'auc', title: __('Auc')},
                        {field: 'auc_path', title: __('Auc_path')},
                        {field: 'source_start_time', title: __('Source_start_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'source_end_time', title: __('Source_end_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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