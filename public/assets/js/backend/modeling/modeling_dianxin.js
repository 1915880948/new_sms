define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'modeling/modeling_dianxin/index' + location.search,
                    add_url: 'modeling/modeling_dianxin/add',
                    edit_url: 'modeling/modeling_dianxin/edit',
                    del_url: 'modeling/modeling_dianxin/del',
                    multi_url: 'modeling/modeling_dianxin/multi',
                    table: 'sms_modeling_dianxin',
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
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'dpi_no', title: __('Dpi_no')},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'启用',2:'暂停'},
                        },
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name:'ajax',
                                title: '暂停',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-pause',
                                classname: 'btn btn-warning btn-xs btn-ajax',
                                confirm: '确认暂停任务？',
                                url:'modeling/modeling_dianxin/stop',
                                success: function (data, ret) {
                                    table.bootstrapTable('refresh',{});
                                    //如果需要阻止成功提示，则必须使用return false;
                                    //return false;
                                },
                                error: function (data, ret) {
                                    Layer.alert(ret.msg);
                                    return false;
                                }

                            },{
                                name:'ajax',
                                title: '启用',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-play',
                                classname: 'btn btn-info btn-xs btn-ajax',
                                confirm: '确认启用任务？',
                                url: 'modeling/modeling_dianxin/start',
                                success: function (data, ret) {
                                    //console.log(ret);
                                    table.bootstrapTable('refresh',{});
                                    //如果需要阻止成功提示，则必须使用return false;
                                    //return false;
                                },
                                error: function (data, ret) {
                                    console.log(data, ret);
                                    Layer.alert(ret.msg);
                                    return false;
                                }
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