define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_a_i_detail/index' + location.search,
                    add_url: 'access/task_a_i_detail/add',
                    edit_url: 'access/task_a_i_detail/edit',
                    del_url: 'access/task_a_i_detail/del',
                    multi_url: 'access/task_a_i_detail/multi',
                    table: 'sms_ai_task_detail',
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
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'source_data_num', title: __('Source_data_num')},
                        {field: 'source_output_name', title: __('Source_output_name')},
                        {field: 'auc', title: __('Auc')},
                        {field: 'ai_path', title: __('Ai_path')},
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