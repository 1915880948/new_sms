define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_source/index' + location.search,
                    add_url: 'access/task_source/add',
                    edit_url: 'access/task_source/edit',
                    del_url: 'access/task_source/del',
                    multi_url: 'access/task_source/multi',
                    table: 'sms_source_task',
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
                        {field: 'carrier', title: __('Carrier')},
                        {field: 'province', title: __('Province')},
                        {field: 'city', title: __('City')},
                        {field: 'number', title: __('Number')},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'上传完毕',2:'入库中',3:'入库完毕',4:'已删除'},
                        },
                        {field: 'AI_status', title: __('Ai_status')},
                        {field: 'file_path', title: __('File_path')},
                        {field: 'before_price', title: __('Before_price')},
                        {field: 'last_price', title: __('Last_price')},
                        {field: 'cost_num', title: __('Cost_num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'paydays', title: __('Paydays')},
                        {field: 'remark', title: __('Remark')},
                        {field: 'handle', title: __('Handle')},
                        {field: 'handle_remark', title: __('Handle_remark')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/task_source/select'+"?mod="+Config.params.mod+'&start_time='+Config.params.start_time+'&end_time='+Config.params.end_time,
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
                        var index = selectArr.indexOf(j.task_id);
                        if (index > -1) {
                            selectArr.splice(index, 1);
                        }
                    } else {
                        selectArr.indexOf(j.task_id) == -1 && selectArr.push(j.task_id);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'task_id',
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'number', title: __('Number')},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'nickname', title: __('Nickname'),
                            formatter: Table.api.formatter.normal,
                            searchList:Config.modList,
                        },
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'上传完毕',2:'入库中',3:'入库完毕',4:'已删除'},
                        },
                        {field: 'remark', title: __('Remark')},
                        // {
                        //     field: 'operate', title: __('Operate'), events: {
                        //         'click .btn-chooseone': function (e, value, row, index) {
                        //             var multiple = Backend.api.query('multiple');
                        //             multiple = multiple == 'true' ? true : false;
                        //             Fast.api.close({row: row, multiple: multiple});
                        //         },
                        //     }, formatter: function () {
                        //         return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                        //     }
                        // }
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                var selectArr = [];
                $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                    //console.log(j);
                    selectArr.push(j.task_id);
                });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({select_arr: selectArr.join("|"), multiple: multiple});
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