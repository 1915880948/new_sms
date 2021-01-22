define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_in/task_source/index' + location.search,
                    add_url: 'data_in/task_source/add',
                    // edit_url: 'data_in/task_source/edit',
                    // del_url: 'data_in/task_source/del',
                    // multi_url: 'data_in/task_source/multi',
                    table: 'sms_source_task',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'task_id',
                sortName: 'task_id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        // {field: 'carrier', title: __('Carrier')},
                        // {field: 'province', title: __('Province')},
                        // {field: 'city', title: __('City')},
                        {field: 'number', title: __('Number')},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'上传完毕',2:'入库中',3:'入库完毕',4:'已删除'},
                        },
                        // {field: 'AI_status', title: __('Ai_status')},
                        // {field: 'file_path', title: __('File_path')},
                        // {field: 'before_price', title: __('Before_price')},
                        // {field: 'last_price', title: __('Last_price')},
                        {field: 'cost_num', title: __('Cost_num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'paydays', title: __('Paydays')},
                        {field: 'remark', title: __('Remark')},
                        // {field: 'handle', title: __('Handle')},
                        // {field: 'handle_remark', title: __('Handle_remark')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '详情',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-align-justify',
                                classname: 'btn btn-primary btn-xs btn-dialog',
                                url:'data_in/task_source/detail',
                                // click:function(data,row){
                                //     Fast.api.open('data_in/task_source/detail?ids='+row.task_id,'详情----'+'ID：'+row.task_id);
                                // },
                            },{
                                name: 'click',
                                title: '详情分布',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-list-ol',
                                classname: 'btn btn-danger btn-xs  btn-click',
                                click:function(data,row){
                                    Fast.api.open('data_in/task_source/spread?ids='+row.task_id,'详情分布----'+'ID：'+row.task_id);
                                },
                            }],
                            formatter: Table.api.formatter.operate}
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
                    index_url: 'data_in/task_source/select'+"?mod="+Config.params.mod+'&start_time='+Config.params.start_time+'&end_time='+Config.params.end_time,
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
                search: false,
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'number', title: __('Number')},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange'},
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
            $("#plupload-files").data("upload-success", function(data, ret){
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='file_name']").val(file_json.name);
            });

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
        detail:function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_in/task_source/detail'+"?ids="+Config.row.task_id,
                }
            });
            var table = $("#detail");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                //showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'url_no', title: __('URL编号')},
                        {field: 'industry', title: __('行业'), },
                        {field: 'category', title: __('分类')},
                        {field: 'name', title: __('名称'),},
                        {field: 'class', title: __('部门'),},
                        {field: 'num_total', title: __('总数据量'),},
                        {field: 'original_num', title: __('原始数据量'),},
                        {field: 'filter_city_num', title: __('规则过滤后'),},
                        {field: 'not_filter_rule_num', title: __('规则过滤前'),},
                        {field: 'threshold_before_num', title: __('阈值分割前'),},
                        {field: 'threshold_after_num', title: __('阈值分割后'),},
                        {field: 'limit_output_before_num', title: __('AI总数量'),},
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

            Controller.api.bindevent();
        },
        spread:function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_in/task_source/spread'+"?ids="+Config.row.task_id,
                }
            });
            var table = $("#spread");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                console.log(Config.row);
                $("form input[name='task_id']").val(Config.row.task_id);
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                //showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'days', title: __('时间')},
                        {field: 'nickname', title: __('建模源'),
                            formatter:Table.api.formatter.normal,
                            searchList:Config.modList,
                        },
                        {field: 'task_id', title: __('批次号'), },
                        {field: 'cost_num', title: __('数据行数'),operate:false},
                        {field: 'number', title: __('入库行数'),operate:false},
                        {field: 'cost', title: __('总成本'),operate:false},
                        {field: 'bx', title: __('保险'),operate:false},
                        {field: 'bxcost', title: __('保险成本'),operate:false},
                        {field: 'yh', title: __('云海'),operate:false},
                        {field: 'jr', title: __('金融'),operate:false},
                        {field: 'jrcost', title: __('金融成本'),operate:false},
                        {field: 'sfc', title: __('顺风车'),operate:false},
                        {field: 'sfccost', title: __('顺风车成本'),operate:false},
                        {field: 'yx', title: __('游戏'),operate:false},
                        {field: 'yxcost', title: __('游戏成本'),operate:false},
                        {field: 'qt', title: __('其他'),operate:false},
                        {field: 'qtcost', title: __('其他成本'),operate:false},
                    ]
                ]
            });

            Controller.api.bindevent();
        },
    };
    return Controller;
});