define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/filter_history/index' + location.search,
                    add_url: 'data_deal/filter_history/add',
                    edit_url: 'data_deal/filter_history/edit',
                    del_url: 'data_deal/filter_history/del',
                    multi_url: 'data_deal/filter_history/multi',
                    table: 'sms_filter_history',
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
                        {field: 'company', title: __('Company')},
                        {field: 'bank', title: __('Bank')},
                        {field: 'business', title: __('Business')},
                        {field: 'use_time', title: __('Use_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'creator', title: __('Creator')},
                        {field: 'total_num', title: __('Total_num')},
                        {field: 'source_name', title: __('Source_name')},
                        {field: 'file_name', title: __('File_name')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
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