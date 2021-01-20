define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/filter_black/index' + location.search,
                    add_url: 'data_deal/filter_black/add',
                    //edit_url: 'data_deal/filter_black/edit',
                    //del_url: 'data_deal/filter_black/del',
                    multi_url: 'data_deal/filter_black/multi',
                    filter_url: 'data_deal/filter_black/index?is_filter=',
                    table: 'sms_filter_black',
                }
            });

            var is_filter = '';
            var table = $("#table");
            // 指定搜索条件
            $(document).on("click", ".btn-filter", function () {
                var parenttable = table.closest('.bootstrap-table');
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                //Bootstrap操作区
                var toolbar = $(options.toolbar, parenttable);
                is_filter===1? $('.btn-filter').text('显示全部'): $('.btn-filter').text('隐藏空来源');
                is_filter = (is_filter === 1?'':1);
                var url = options.extend.filter_url+is_filter;
                table.bootstrapTable('refresh',{url:url});
            });
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
                        {field: 'source_name', title: __('Source_name'),operate:'like'},
                        {field: 'num', title: __('Num'),operate:false},
                        {field: 'total_num', title: __('Total_num'),operate:false},
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'end_time', title: __('End_time'), operate:false},
                        {field: 'status', title: __('Status'),formatter: Table.api.formatter.status,
                            searchList:{1:'待处理',2:'处理中',3:'处理完毕',4:'已删除',}},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs btn-download',
                                url:'data_deal/filter_black/download',
                            }],
                            formatter: Table.api.formatter.operate}
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