define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/basic/model/index' + location.search,
                    add_url: 'access/basic/model/add',
                    edit_url: 'access/basic/model/edit',
                    del_url: 'access/basic/model/del',
                    multi_url: 'access/basic/model/multi',
                    table: 'model',
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
                        {field: 'model_no', title: __('Model_no')},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'industry', title: __('Industry')},
                        {field: 'category', title: __('Category')},
                        {field: 'name', title: __('Name')},
                        {field: 'is_valid', title: __('Is_valid')},
                        {field: 'time', title: __('Time')},
                        {field: 'grade', title: __('Grade')},
                        {field: 'forecast_num', title: __('Forecast_num')},
                        {field: 'createby', title: __('Createby')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'updateby', title: __('Updateby')},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange'},
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