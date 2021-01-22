define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'filter_history/index' + location.search,
                    add_url: 'filter_history/add',
                    edit_url: 'filter_history/edit',
                    del_url: 'filter_history/del',
                    multi_url: 'filter_history/multi',
                    table: 'sms_filter_history',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'company', title: __('Company'),operate:'like'},
                        {field: 'bank', title: __('Bank'),operate:'like'},
                        {field: 'business', title: __('Business'),operate:'like'},
                        {field: 'use_time', title: __('Use_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'creator', title: __('Creator')},
                        {field: 'total_num', title: __('Total_num'),operate:false},
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
        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'filter_history/select',
                }
            });

            var selectArr = [];
            var table = $("#table");
            //在普通搜索渲染后
            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
                if (e.type == 'check' || e.type == 'uncheck') {
                    row = [row];
                } else {
                    selectArr = [];
                }
                $.each(row, function (i, j) {
                    if (e.type.indexOf("uncheck") > -1) {
                        var index = selectArr.indexOf(j.id);
                        if (index > -1) {
                            selectArr.splice(index, 1);
                        }
                    } else {
                        selectArr.indexOf(j.task_id) == -1 && selectArr.push(j.id);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'company', title: __('Company'),operate:'like'},
                        {field: 'bank', title: __('Bank'),operate:'like'},
                        {field: 'business', title: __('Business'),operate:'like'},
                        {field: 'use_time', title: __('Use_time')},
                        {field: 'total_num', title: __('Total_num'),operate:false},
                        {field: 'source_name', title: __('Source_name')},
                        //{field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                var selectArr = [];
                $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                    //console.log(j);
                    selectArr.push(j.id);
                });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({select_arr: selectArr.join(","), multiple: multiple});
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

    };
    return Controller;
});