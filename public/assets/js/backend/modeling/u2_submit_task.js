define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
var source_no = 1;
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'modeling/u2_submit_task/index' + location.search,
                    add_url: 'modeling/u2_submit_task/add',
                    // edit_url: 'modeling/u2_submit_task/edit',
                    del_url: 'modeling/u2_submit_task/del',
                    multi_url: 'modeling/u2_submit_task/multi',
                    download_url: 'u2_submit_task/template',
                    import_url: 'modeling/u2_submit_task/import',
                    table: 'sms_u2_submit_task',
                }
            });
            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                $("form select[name='source_no']").val(1);
                source_no = 1;
            });

            $(document).on("click", ".btn-download", function () {
                var options = table.bootstrapTable('getOptions');
                window.location.href = options.extend.download_url+"?source_no="+source_no;
                //Fast.api.open(options.extend.download_url+"?source_no="+source_no, __('模板导出'), $(this).data() || {});
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        // {field: 'type', title: __('Type')},
                        {field: 'file_name', title: __('File_name')},
                        {field: 'file_path', title: __('File_path'),operate:false,},
                        {field: 'source_no', title: __('Source_no'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'U2',2:'TL',3:'DPI',4:'TY',5:'TS',6:'TD',7:'TW'}
                        },
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange'},
                        {field: 'remark', title: __('Remark')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs  btn-download',
                                url:'modeling/u2_submit_task/download',

                            }],
                            formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            var parenttable = table.closest('.bootstrap-table');
            //Bootstrap-table配置
            var options = table.bootstrapTable('getOptions');
            //Bootstrap操作区
            var toolbar = $(options.toolbar, parenttable);
            // 导入按钮事件
            if ($(Table.config.importbtn, toolbar).size() > 0) {
                require(['upload'], function (Upload) {
                    Upload.api.plupload($(Table.config.importbtn, toolbar), function (data, ret) {
                        Fast.api.ajax({
                            url: options.extend.import_url,
                            data: {file: data.url,source_no:source_no},
                        }, function (data, ret) {
                            table.bootstrapTable('refresh');
                        });
                    });
                });
            }

            //绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // var options = table.bootstrapTable(tableOptions);
                var typeStr = $(this).attr("href").replace('#', '');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    // params.filter = JSON.stringify({type: typeStr});
                    if( typeStr === 'type' ){
                        params.type = 1;
                    }else {
                        params.source_no = typeStr;
                        source_no = typeStr;
                    }
                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;

            });

            // 为表格绑定事件
            Table.api.bindevent(table) ;
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
        import:function () {
            $("#btn-import-file").data("upload-success", function(data, ret){
                let file_json = JSON.parse(data.row.extparam);
                console.log(file_json);
                $("input[name='file_name']").val(file_json.name);
            });
            // var table = $("#table");
            // //Bootstrap-table的父元素,包含table,toolbar,pagnation
            // var parenttable = table.closest('.bootstrap-table');
            // //Bootstrap-table配置
            // var options = table.bootstrapTable('getOptions');
            // //Bootstrap操作区
            // var toolbar = $(options.toolbar, parenttable);
            // // 导入按钮事件
            // if ($(Table.config.importbtn, toolbar).size() > 0) {
            //     require(['upload'], function (Upload) {
            //         Upload.api.plupload($(Table.config.importbtn, toolbar), function (data, ret) {
            //             Fast.api.ajax({
            //                 url: options.extend.import_url,
            //                 data: {file: data.url,a:'123456'},
            //             }, function (data, ret) {
            //                 table.bootstrapTable('refresh');
            //             });
            //         });
            //     });
            // }
            Controller.api.bindevent();
        },

    };
    return Controller;
});