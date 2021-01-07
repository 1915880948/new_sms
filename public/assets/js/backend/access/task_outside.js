define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_outside/index' + location.search,
                    add_url: 'access/task_outside/add',
                    edit_url: 'access/task_outside/edit',
                    del_url: 'access/task_outside/del',
                    multi_url: 'access/task_outside/multi',
                    table: 'sms_outside_task',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'task_id',
                sortName: 'task_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'status', title: __('Status')},
                        {field: 'channel', title: __('Channel')},
                        {field: 'product', title: __('Product')},
                        {field: 'startdate', title: __('Startdate'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'enddate', title: __('Enddate'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'type', title: __('Type')},
                        {field: 'is_idfa', title: __('Is_idfa')},
                        {field: 'total_number', title: __('Total_number')},
                        {field: 'number', title: __('Number')},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange'},
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