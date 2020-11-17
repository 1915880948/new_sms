define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic/sms_template/index' + location.search,
                    add_url: 'basic/sms_template/add',
                    edit_url: 'basic/sms_template/edit',
                    del_url: 'basic/sms_template/del',
                    multi_url: 'basic/sms_template/multi',
                    table: 'sms_template',
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
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'bank_name', title: __('Bank_name'),operate:'like'},
                        {field: 'business_name', title: __('Business_name'),operate:'like'},
                        {field: 'copy', title: __('Copy'),operate:'like'},
                        {field: 'remark', title: __('Remark'),operate:'like'},
                        {field: 'creator', title: __('Creator'),operate:'like'},
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange'},
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
                    index_url: 'basic/sms_template/select',
                }
            });
            var urlArr = [];

            var table = $("#table");

            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
                if (e.type == 'check' || e.type == 'uncheck') {
                    row = [row];
                } else {
                    urlArr = [];
                }
                $.each(row, function (i, j) {
                    if (e.type.indexOf("uncheck") > -1) {
                        var index = urlArr.indexOf(j.url);
                        if (index > -1) {
                            urlArr.splice(index, 1);
                        }
                    } else {
                        urlArr.indexOf(j.url) == -1 && urlArr.push(j.url);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'bank_name', title: __('Bank_name'),operate:'like'},
                        {field: 'business_name', title: __('Business_name'),operate:'like'},
                        {field: 'copy', title: __('Copy'),operate:'like'},
                        {field: 'remark', title: __('Remark'),operate:'like'},
                        {field: 'creator', title: __('Creator'),operate:'like'},
                        {
                            field: 'operate', title: __('Operate'), events: {
                                'click .btn-chooseone': function (e, value, row, index) {
                                    var multiple = Backend.api.query('multiple');
                                    multiple = multiple == 'true' ? true : false;
                                    Fast.api.close({row: row, multiple: multiple});
                                },
                            }, formatter: function () {
                                return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                            }
                        }
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                // var urlArr = [];
                // $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                //     urlArr.push(j.url);
                // });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({url: urlArr.join(","), multiple: multiple});
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            require(['upload'], function (Upload) {
                Upload.api.plupload($("#toolbar .plupload"), function () {
                    $(".btn-refresh").trigger("click");
                });
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
        }
    };
    return Controller;
});