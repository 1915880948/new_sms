define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/filter_black/index' + location.search,
                    add_url: 'data_deal/filter_black/add',
                    edit_url: 'data_deal/filter_black/edit',
                    del_url: 'data_deal/filter_black/del',
                    multi_url: 'data_deal/filter_black/multi',
                    table: 'sms_filter_black',
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
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'total_num', title: __('Total_num')},
                        {field: 'num', title: __('Num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'creator', title: __('Creator')},
                        {field: 'file_name', title: __('File_name')},
                        {field: 'black_name', title: __('Black_name')},
                        {field: 'source_name', title: __('Source_name')},
                        {field: 'black', title: __('Black')},
                        {field: 'phone_manufacturer', title: __('Phone_manufacturer')},
                        {field: 'all_black', title: __('All_black')},
                        {field: 'bank', title: __('Bank')},
                        {field: 'bank_city_codes', title: __('Bank_city_codes')},
                        {field: 'status', title: __('Status')},
                        {field: 'distrust', title: __('Distrust')},
                        {field: 'sensitive', title: __('Sensitive')},
                        {field: 'region', title: __('Region')},
                        {field: 'output_pid_task_id', title: __('Output_pid_task_id')},
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