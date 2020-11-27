define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'file/file_reply/index' + location.search,
                    add_url: 'file/file_reply/add',
                    multi_url: 'file/file_reply/detail',
                    table: 'sms_file_reply',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                search: false,
                showToggle: false,
                showExport: false,
                //showColumns: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'file_name', title: __('File_name')},
                        {field: 'file_path', title: __('File_path')},
                        {field: 'creator', title: __('Creator')},
                        {field: 'useror', title: __('Useror')},
                        {field: 'num', title: __('Num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-info btn-xs btn-download',
                                url: 'file/file_reply/download',
                            },{
                                name: 'detail',
                                text: __('Detail'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'file/file_reply/detail',
                                callback: function (data) {
                                    Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                },
                                visible: function (row) {
                                    //返回true时按钮显示,返回false隐藏
                                    return true;
                                }
                            }],
                            formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            $(document).on("click", ".btn-download", function () {
                var abc = $(this).parent();
                var num = parseInt(abc.prev().prev().prev().html())+1;
                abc.prev().prev().prev().html(num);
            });
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
        detail:function () {
            $(document).on("click", ".btn-refresh", function () {
                $("#detail").bootstrapTable('refresh',{});
            });

            $("#detail").bootstrapTable({
                url:"file/file_reply/detail?task_id="+Fast.api.query('ids'),
                extend: {
                    index_url: "file/file_reply/detail?task_id="+Fast.api.query('ids'),
                    //edit_url: 'sms/link_short/edit?link_id='+Fast.api.query('ids'),
                    table: 'sms_file_reply_detail',
                },
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                showColumns: false,                 //3.字段显示
                showExport: false,                  //4.导出按钮
                commonSearch: false,                //5.通用搜索框
                pagination: true,                   //6.是否显示分页条
                // onlyInfoPagination: true,           //7.只显示总数据数
                // showHeader: false,                  //8.是否显示列头
                // paginationVAlign: 'top',            //9.指定分页条垂直位置
                // showRefresh:false,
                sidePagination:'server',
                pageSize:20,
                pageList:[20,50,100,'all'],
                columns: [
                    {field: 'id', title: __('Id'),operate:false},
                    {field: 'useror', title: __('Useror')},
                    {field: 'create_time', title: __('Create_time'), operate:false, },
                ]
            });
            Controller.api.bindevent();

        },
    };
    return Controller;
});