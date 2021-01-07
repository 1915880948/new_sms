define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic2/province_city_code/index' + location.search,
                    add_url: 'basic2/province_city_code/add',
                    edit_url: 'basic2/province_city_code/edit',
                    del_url: 'basic2/province_city_code/del',
                    multi_url: 'basic2/province_city_code/multi',
                    table: 'province_city_code',
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
                        {field: 'province', title: __('Province')},
                        {field: 'city', title: __('City')},
                        {field: 'province_code', title: __('Province_code')},
                        {field: 'city_code', title: __('City_code')},
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