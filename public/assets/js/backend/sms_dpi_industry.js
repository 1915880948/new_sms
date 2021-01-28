define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms_dpi_industry/index' + location.search,
                    add_url: 'sms_dpi_industry/add',
                    edit_url: 'sms_dpi_industry/edit',
                    del_url: 'sms_dpi_industry/del',
                    multi_url: 'sms_dpi_industry/multi',
                    table: 'sms_dpi_industry',
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
                        {field: 'category', title: __('Category')},
                        {field: 'industry', title: __('Industry')},
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