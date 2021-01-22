define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/filter_history/index' + location.search,
                    add_url: 'data_deal/filter_history/add',
                    //edit_url: 'data_deal/filter_history/edit',
                    del_url: 'data_deal/filter_history/del',
                    //multi_url: 'data_deal/filter_history/multi',
                    table: 'sms_filter_history',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'company', title: __('Company'),operate:'like'},
                        {field: 'bank', title: __('Bank'),operate:'like'},
                        {field: 'business', title: __('Business'),operate:'like'},
                        {field: 'use_time', title: __('Use_time'), formatter: Table.api.formatter.date, operate: '', type: 'date', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'creator', title: __('Creator')},
                        {field: 'total_num', title: __('Total_num'),operate:false},
                        {field: 'source_name', title: __('Source_name'),operate:'like'},
                        {field: 'file_name', title: __('File_name'),operate:false,visible:false},
                        {field: 'create_time', title: __('Create_time'), operate:false,visible:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs btn-download',
                                url:'data_deal/filter_history/download',
                            }],
                            formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $("#plupload-files").data("upload-success", function (data, ret) {
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("#textarea").val($("#textarea").val()+file_json.name+"\n");
                $("#files_list").val($("#files_list").val()+file_json.name+","+data.row.url+"|");
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
        }
    };
    return Controller;
});