define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'blacklist/black_reply_t/index' + location.search,
                    add_url: 'blacklist/black_reply_t/add',
                    edit_url: 'blacklist/black_reply_t/edit',
                    del_url: 'blacklist/black_reply_t/del',
                    multi_url: 'blacklist/black_reply_t/multi',
                    import_url: 'blacklist/black_reply_t/import',
                    table: 'sms_blacklist_reply_t',
                },
                pageSize: 20,
                pageList: [20, 50, 100,'All'],
                searchFormVisible:false,
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                // showColumns: false,                 //3.字段显示
                // showExport: false,                  //4.导出按钮
                commonSearch: true,                //5.通用搜索框
                pagination: true,                   //6.是否显示分页条
                // onlyInfoPagination: true,           //7.只显示总数据数
                // showHeader: false,                  //8.是否显示列头
                // paginationVAlign: 'top',            //9.指定分页条垂直位置
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false,sortable: true},
                        {field: 'phone', title: __('Phone'),operate:'like', sortable:true},
                        {field: 'remark', title: __('Remark'),operate:"LIKE"},
                        {field: 'admin.username', title: __('User_id'),operate:false},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.status,
                            custom: {1: 'success', 2: 'gray'},
                            searchList:{1:'正常',2:'移除'},
                            // operate: false
                            },

                        {field: 'update_time', title: __('Update_time'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime,sortable:true },
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
            },
            // formatter:

        },
    };

    return Controller;
});