define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'modeling/modeling_base/index' + location.search,
                    add_url: 'modeling/modeling_base/add',
                    edit_url: 'modeling/modeling_base/edit',
                    del_url: 'modeling/modeling_base/del',
                    multi_url: 'modeling/modeling_base/multi',
                    table: 'sms_modeling_base',
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
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'name', title: __('Name')},
                        {field: 'creators', title: __('Creators')},
                        {field: 'class', title: __('Class')},
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