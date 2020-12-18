define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/lable/index' + location.search,
                    add_url: 'access/lable/add',
                    edit_url: 'access/lable/edit',
                    del_url: 'access/lable/del',
                    import_url: 'access/lable/import',
                    multi_url: 'access/lable/multi',
                    table: 'access_lable',
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
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'code', title: __('Code')},
                        {field: 'host_url', title: __('Host_url'), formatter: Table.api.formatter.url},
                        {field: 'is_https', title: __('Is_https'),
                            formatter: Table.api.formatter.normal,
                            searchList:{'N':'N','Y':'Y',},
                        },
                        {field: 'host', title: __('Host')},
                        {field: 'path', title: __('Path')},
                        {field: 'key', title: __('Key')},
                        {field: 'site', title: __('Site')},
                        {field: 'type', title: __('Type')},
                        {field: 'name', title: __('Name')},
                        {field: 'lable', title: __('Lable')},
                        {field: 'class', title: __('Class')},
                        {field: 'subclass', title: __('Subclass')},
                        {field: 'priority', title: __('Priority')},
                        {field: 'number', title: __('Number')},
                        {field: 'info', title: __('Info')},
                        {field: 'username', title: __('Username')},
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange',visible:false},
                        {field: 'update_time', title: __('Update_time'), operate:false, addclass:'datetimerange',visible: false},
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
                    index_url: 'access/lable/select',
                }
            });
            var selectArr = [];
            var table = $("#table");
            //在普通搜索渲染后
            // table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
            //     if (e.type == 'check' || e.type == 'uncheck') {
            //         row = [row];
            //     } else {
            //         selectArr = [];
            //     }
            //     $.each(row, function (i, j) {
            //         if (e.type.indexOf("uncheck") > -1) {
            //             var index = selectArr.indexOf(j.code);
            //             if (index > -1) {
            //                 selectArr.splice(index, 1);
            //             }
            //         } else {
            //             selectArr.indexOf(j.code) == -1 && selectArr.push(j.code);
            //         }
            //     });
            // });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'code', title: __('Code')},
                        // {field: 'host_url', title: __('Host_url'), formatter: Table.api.formatter.url},
                        // {field: 'is_https', title: __('Is_https'),
                        //     formatter: Table.api.formatter.normal,
                        //     searchList:{'N':'N','Y':'Y',},
                        // },
                        // {field: 'host', title: __('Host')},
                        // {field: 'path', title: __('Path')},
                        // {field: 'key', title: __('Key')},
                        // {field: 'site', title: __('Site')},
                        {field: 'type', title: __('Type')},
                        {field: 'name', title: __('Name')},
                        {field: 'lable', title: __('Lable'),operate:false},
                        {field: 'class', title: __('Class')},
                        {field: 'subclass', title: __('Subclass')},
                        // {field: 'priority', title: __('Priority')},
                        // {field: 'number', title: __('Number')},
                        {field: 'info', title: __('Info')},
                        {field: 'username', title: __('Username')},
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange',visible:false},
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
                    selectArr.push(j.code);
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