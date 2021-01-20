define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'access/hit/index' + location.search,
                    //add_url: 'access/hit/add',
                    //edit_url: 'access/hit/edit',
                    del_url: 'access/hit/del',
                    multi_url: 'access/hit/multi',
                    table: 'sms_hit_task',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'task_id',
                sortName: 'task_id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'date', title: __('Date'), operate:false,visible:false},
                        {field: 'source_no', title: __('Source_no')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'number', title: __('Number'), operate:false},
                        {field: 'result_num', title: __('Result_num'), operate:false},
                        {field: 'status', title: __('Status'),formatter: Table.api.formatter.status,
                            searchList:{1:'上传完毕',2:'转换导入到大数据表完毕',3:'上传ftp完毕',4:'撞库匹配完毕',}
                                },
                        {field: 'file_path', title: __('File_path'),operate:false,visible:false},
                        {field: 'down_file_path', title: __('Down_file_path')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'remark', title: __('Remark'),operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs btn-download',
                                url:'access/hit/download',
                            },{
                                name: 'click',
                                title: '黑名单下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-primary btn-xs btn-blackDownload',
                                url:'access/hit/black_download',
                            }],
                            formatter: Table.api.formatter.operate
                        }
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