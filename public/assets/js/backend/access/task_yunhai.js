define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_yunhai/index' + location.search,
                    add_url: 'access/task_yunhai/add',
                    edit_url: 'access/task_yunhai/edit',
                    del_url: 'access/task_yunhai/del',
                    multi_url: 'access/task_yunhai/multi',
                    table: 'sms_yunhai_task',
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
                        {field: 'number', title: __('Number')},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'status', title: __('Status')},
                        {field: 'file_path', title: __('File_path')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'transfer_time', title: __('Transfer_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'remark', title: __('Remark')},
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