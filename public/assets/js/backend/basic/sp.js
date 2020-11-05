define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic/sp/index' + location.search,
                    add_url: 'basic/sp/add',
                    edit_url: 'basic/sp/edit',
                    del_url: 'basic/sp/del',
                    multi_url: 'basic/sp/multi',
                    table: 'sms_info_sp',
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
                        {field: 'sp_no', title: __('Sp_no'),operate:'like'},
                        {field: 'sp_name', title: __('Sp_name'),operate:'like'},
                        {field: 'vendor_id', title: __('Vendor_id'),
                            formatter: Table.api.formatter.normal,
                            custom: {0: 'info', 1: 'success',2:'danger',3:'warning'},
                            searchList:{0:'中国电信',1:'中国移动',2:'中国联通',3:'通用'},
                        },
                        {field: 'speed', title: __('Speed'),operate:'like'},
                        {field: 'price', title: __('Price'),operate:false},
                        {field: 'local_account', title: __('Local_account'),operate:'like'},
                        {field: 'remote_account', title: __('Remote_account'),operate:'like'},
                        {field: 'corp_id', title: __('Corp_id'),operate:'like'},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            custom: {0: 'info', 1: 'success',3:'warning'},
                            searchList:{1:'CRM平台通道',2:'外部平台通道',3:'小沃HTTP通道'},
                        },
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange',operate:false},
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
            }
        }
    };
    return Controller;
});