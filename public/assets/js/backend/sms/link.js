define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms/link/index' + location.search,
                    add_url: 'sms/link/add',
                    edit_url: 'sms/link/edit',
                    del_url: 'sms/link/del',
                    multi_url: 'sms/link/multi',
                    table: 'sms_link',
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
                        {field: 'channel_id', title: __('Channel_id'),operate:'like'},
                        {field: 'channel_code', title: __('Channel_code'),operate:'like'},
                        {field: 'link', title: __('Link'),operate:'like',formatter:Table.api.formatter.url },
                        {field: 'company_name', title: __('Company_name'),operate:'like'},

                        {field: 'bank_name', title: __('Bank_name'),operate:'like'},

                        {field: 'business_name', title: __('Business_name'),operate:'like'},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        // {field: 'sms_content', title: __('Sms_content'),operate:false},
                        {field: 'remark', title: __('Remark'),operate:false},
                        {field: 'creator', title: __('Creator'),operate:'like'},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.status,
                            custom: {1: 'success', 0: 'gray'},
                            searchList:{1:'有效',2:'无效'},
                        },
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange'},
                        //{field: 'update_time', title: __('Update_time'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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