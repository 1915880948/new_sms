define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms/link_short/index' + location.search,
                    add_url: 'sms/link_short/add',
                    edit_url: 'sms/link_short/edit',
                    del_url: 'sms/link_short/del',
                    multi_url: 'sms/link_short/multi',
                    table: 'sms_link_short',
                }
            });

            var table = $("#table");

            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                console.log(Config.link_id);
                $("form input[name='link_id']").val(Config.link_id);
            });
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
                        {field: 'remark', title: __('Remark'),operate:'like',
                            // cellStyle: cellStyle(),
                            // formatter: paramsMatter,
                        },
                        {field: 'link_id', title: __('Link_id'),},
                        // {field: 'business_link', title: __('Business_link'),operate:'like'},
                        // {field: 'transfer_link', title: __('Transfer_link'),operate:'like'},
                        {field: 'short_link', title: __('Short_link'),operate:false},
                        {field: 'send_num', title: __('Send_num')},
                        {field: 'send_success_num', title: __('Send_success_num')},
                        {field: 'click_num', title: __('Click_num')},
                        {field: 'creator', title: __('Creator'),operate:'like'},
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange'},
                        {field: 'task_send_num', title: __('Task_send_num'),operate:false},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.status,
                            custom: {1: 'success', 0: 'gray'},
                            searchList:{1:'有效',0:'删除'},
                        },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'detail',
                                //text: '发送短信',
                                title: '发送短信',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-paper-plane',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'sms/task_send/add?link_from=1'
                            },{
                                title: '外部发送',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-paper-plane',
                                classname: 'btn btn-danger btn-xs btn-detail btn-dialog',
                                url: 'sms/task_send/add?link_from=2'
                            }],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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