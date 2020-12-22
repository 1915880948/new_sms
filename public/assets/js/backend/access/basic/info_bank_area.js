define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/basic/info_bank_area/index' + location.search,
                    add_url: 'access/basic/info_bank_area/add',
                    edit_url: 'access/basic/info_bank_area/edit',
                    del_url: 'access/basic/info_bank_area/del',
                    multi_url: 'access/basic/info_bank_area/multi',
                    table: 'sms_info_bank_area',
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
                        {field: 'bank_id', title: __('Bank_id')},
                        {field: 'bank_name', title: __('Bank_name')},
                        {field: 'city_id', title: __('City_id')},
                        {field: 'city_name', title: __('City_name')},
                        // {field: 'province_id', title: __('Province_id')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'正常',0:'删除'}
                        },
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