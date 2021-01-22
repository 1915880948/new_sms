define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    function cellStyle() {
        return {
            css:{
                'min-width':'100px',"max-width":'200px',
                'white-space':'nowrap',"text-overflow": "ellipsis",
                'overflow':'hidden','cursor':'pointer',
            }
        };
    }
    function paramsMatter(value,row,index) {
        var span = document.createElement("span");
        span.setAttribute("title",value);
        span.setAttribute("data-toggle","tooltip");
        span.innerHTML = value;
        return span.outerHTML;
    }
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'modeling/modeling_url/index' + location.search,
                    add_url: 'modeling/modeling_url/add',
                    edit_url: 'modeling/modeling_url/edit',
                    del_url: 'modeling/modeling_url/del',
                    multi_url: 'modeling/modeling_url/multi',
                    import_url: 'modeling/modeling_url/import',
                    export_confirm_url: 'modeling/modeling_url/confirm',
                    export_url: 'modeling/modeling_url/export',
                    match_url: 'modeling/modeling_url/match',
                    table: 'sms_modeling_url',
                }
            });

            var table = $("#table");
            // 批量导入
            $(document).on("click", ".btn-import", function () {
                var _this = this;
                var parenttable = table.closest('.bootstrap-table');
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                //Bootstrap操作区
                var toolbar = $(options.toolbar, parenttable);

                Fast.api.open(options.extend.import_url, __('批量导入'), $(this).data() || {});
            });
            // 导出
            $(document).on("click", ".btn-export", function () {
                var options = table.bootstrapTable('getOptions');
                Fast.api.open(options.extend.export_confirm_url, __('URL导出'), $(this).data() || {});
            });
            // 匹配
            $(document).on("click", ".btn-match", function () {
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                Fast.api.open(options.extend.match_url, __('匹配'), $(this).data() || {});
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
                        // {field: 'source_no_id', title: __('Source_no_id')},
                        // {field: 'source_no', title: __('Source_no')},
                        {field: 'source_name', title: __('Source_name')},
                        // {field: 'source_id', title: __('Source_id')},
                        {field: 'url_no', title: __('Url_no')},
                        {field: 'url', title: __('Url'), formatter: Table.api.formatter.url},
                        {field: 'host', title: __('Host')},
                        {field: 'path', title: __('Path'),
                            cellStyle: cellStyle(),
                            formatter: paramsMatter,
                        },
                        {field: 'key', title: __('Key'),
                            cellStyle: cellStyle(),
                            formatter: paramsMatter,
                        },
                        // {field: 'root', title: __('Root')},
                        {field: 'name', title: __('Name')},
                        {field: 'category', title: __('Category')},
                        {field: 'industry', title: __('Industry')},
                        {field: 'is_valid', title: __('Is_valid')},
                        {field: 'leader', title: __('Leader')},
                        {field: 'time', title: __('Time'), operate:'RANGE', addclass:'datetimerange'},
                        // {field: 'creator', title: __('Creator')},
                        // {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
        import:function () {
            $("#plupload-files").data("upload-success", function(data, ret){
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='file_name']").val(file_json.name);
            });

            Controller.api.bindevent();
        },
        confirm:function () {
            // 导出
            $(document).on("click", "#export", function () {
                let source_no_id = $("select[name='source_no_id']").val();
                window.location.href = "export?source_no_id="+source_no_id;
                //Fast.api.open(options.extend.export_url, __('URL导出'), $(this).data() || {});
            });
            Controller.api.bindevent();
        },
        export:function () {

            Controller.api.bindevent();
        },
        match:function () {
            $("#plupload-files").data("upload-success", function(data, ret){
                let file_json = JSON.parse(data.row.extparam);
                console.log(file_json);
                $("input[name='file_name']").val(file_json.name);
            });
            // 匹配导出
            $(document).on("click", "#match", function () {
                let file_path = $("input[name='row[file_path]']").val();
                // console.log(file_path);
                window.location.href = "match1?file_path="+file_path;
                //Fast.api.open(options.extend.export_url, __('URL导出'), $(this).data() || {});
            });

            Controller.api.bindevent();
        },

    };
    return Controller;
});