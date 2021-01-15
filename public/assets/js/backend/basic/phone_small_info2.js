define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic/phone_small_info2/index' + location.search,
                    add_url: 'basic/phone_small_info2/add',
                    edit_url: 'basic/phone_small_info2/edit',
                    del_url: 'basic/phone_small_info2/del',
                    multi_url: 'basic/phone_small_info2/multi',
                    table: 'phone_small_info2',
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
                        {field: 'phone', title: __('Phone')},
                        {field: 'sms_sp_info_id', title: __('Sms_sp_info_id')},
                        {field: 'sp_no', title: __('Sp_no')},
                        {field: 'sp_name', title: __('Sp_name')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{'0':'弃用','1':'正常'}
                        },
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