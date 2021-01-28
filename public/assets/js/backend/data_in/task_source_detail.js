define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_in/task_source_detail/index' + location.search,
                    add_url: 'data_in/task_source_detail/add',
                    edit_url: 'data_in/task_source_detail/edit',
                    del_url: 'data_in/task_source_detail/del',
                    multi_url: 'data_in/task_source_detail/multi',
                    table: 'sms_source_task_detail',
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
                        {field: 'source_task_id', title: __('Source_task_id')},
                        {field: 'url_no', title: __('Url_no')},
                        {field: 'num_total', title: __('Num_total')},
                        {field: 'original_num', title: __('Original_num')},
                        {field: 'num_30day_new', title: __('Num_30day_new')},
                        {field: 'filter_city_num', title: __('Filter_city_num')},
                        {field: 'not_filter_rule_num', title: __('Not_filter_rule_num')},
                        {field: 'threshold_before_num', title: __('Threshold_before_num')},
                        {field: 'threshold_after_num', title: __('Threshold_after_num')},
                        {field: 'limit_output_before_num', title: __('Limit_output_before_num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'isnormal', title: __('Isnormal')},
                        {field: 'cost', title: __('Cost')},
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
                    index_url: 'data_in/task_source_detail/select'+"?batch_ids="+Config.params.batch_ids,
                }
            });
            var selectArr = [];
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                search: false,
                showToggle: false,
                showExport: false,
                sidePagination:'server',
                columns: [
                    [
                        {checkbox: true},
                        // {field: 'id', title: __('ID'),operate:false},
                        {field: 'url_no', title: __('URL编号')},

                        {field: 'nickname', title: __('源ID')},
                        {field: 'name', title: __('名称')},
                        {field: 'category', title: __('类型'),operate:false},
                        {field: 'industry', title: __('行业')},
                        {field: 'num', title: __('数据量'),operate:'BETWEEN'},
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                var selectArr = [];
                $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                    //console.log(j);
                    selectArr.push(j.url_no);
                });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({select_arr: selectArr.join("|"), multiple: multiple});
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

    };
    return Controller;
});