define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var  link_id='';
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms/link/index' + location.search,
                    add_url: 'sms/link/add',
                    edit_url: 'sms/link/edit',
                    del_url: 'sms/link/del',
                    import_url: 'sms/link/import',
                    multi_url: 'sms/link/multi',
                    table: 'sms_link',
                }
            });

            var table = $("#table");
            Fast.config.openArea = ['1000px','600px'];

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
                        {field: 'link', title: __('Link'),operate:'like',formatter:Table.api.formatter.url,
                            cellStyle:{
                                css:{
                                    // 'min-width':'10px',"max-width":'100px',
                                    // 'white-space':'nowrap',"text-overflow": "ellipsis",
                                    // 'overflow':'hidden','cursor':'pointer',
                                }
                            }
                        },
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
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            //console.log(table),
                            buttons: [{
                                title: '生成短链',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-link',
                                classname: 'btn btn-info btn-xs btn-dialog ',
                                url:'sms/link/short',
                                // callback:function (data) {
                                //     console.log(data);//控制输出回调数据
                                // }
                                // click: function (e, data) {
                                //     //Layer.alert("点击按钮执行的事件");
                                //     //location.href = '/admin.php/sms/link_short/index?link_id='+data.ids+'&ref=addtabs';
                                //     Fast.api.open('sms/link_short/index?link_id='+data.ids, '生成短链', $(this).data() || {});
                                // },
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
        },

        short_add:function(){
            $(document).on("click", "#submit", function () {
                var link_id = $("input[name='link_id']").val();
                Fast.api.ajax({
                    type:'post',
                    url: 'sms/link/short_add?link_id=' + link_id,
                    data: {
                        'transfer_link':  $("select[name='row[transfer_link]']").val(),
                        'short_link':  $("select[name='row[short_link]']").val(),
                        'remark':$("input[name='row[remark]']").val()
                    },
                    dataType: "json",
                }, function (data) {
                    top.Toastr.success(data.msg);
                    Fast.api.close(data);
                    parent.$("#short").bootstrapTable('refresh',{});
                    //return false;
                }, function (data) {
                    top.Toastr.error(data.msg);
                    Fast.api.close();
                });

            });

        },

        short:function () {
            $(document).on("click", ".btn-short", function () {
                link_id = Fast.api.query('ids');
                Fast.api.open('sms/link/short_add?link_id=' + link_id, '生成短链');

            });
            $(document).on("click", ".btn-refresh", function () {
                $("#short").bootstrapTable('refresh',{});
            });

            $("#short").bootstrapTable({
                url:"sms/link/short?link_id="+Fast.api.query('ids'),
                extend: {
                    index_url: "sms/link/short?link_id="+Fast.api.query('ids'),
                    //edit_url: 'sms/link_short/edit?link_id='+Fast.api.query('ids'),
                    table: '',
                },
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                showColumns: false,                 //3.字段显示
                showExport: false,                  //4.导出按钮
                commonSearch: false,                //5.通用搜索框
                pagination: true,                   //6.是否显示分页条
                // onlyInfoPagination: true,           //7.只显示总数据数
                // showHeader: false,                  //8.是否显示列头
                // paginationVAlign: 'top',            //9.指定分页条垂直位置
                // showRefresh:false,
                sidePagination:'server',
                pageSize:20,
                pageList:[20,50,100,'all'],
                columns: [
                   {field: 'id', title: __('Id'),operate:false},
                   {field: 'remark', title: __('Remark')},
                   {field: 'link_id', title: __('Link_id'),},
                   {field: 'short_link', title: __('Short_link'),operate:false},
                   {field: 'send_num', title: __('Send_num')},
                   {field: 'send_success_num', title: __('Send_success_num')},
                   {field: 'click_num', title: __('Click_num')},
                   {field: 'task_send_num', title: __('Task_send_num')},
                   {field: 'create_time', title: __('Create_time'), operate:false, },
                    {field: 'operate', title: __('Operate'), table: $("#short"), events: Table.api.events.operate,
                        buttons: [{
                            name: 'click',
                            //text: '发送短信',
                            title: '发送短信',
                            extend: 'data-toggle="tooltip"',
                            icon: 'fa fa-paper-plane',
                            classname: 'btn btn-info btn-xs btn-click',
                            click:function(data,row){
                                console.log(row);
                                //Layer.alert('暂不支持此处发送短信。。');
                                //location.href = 'sms/task_send/add?link_form=1&ref=addtabs';
                                Fast.api.open('sms/task_send/add?link_from=1&ids='+row.id);
                            },
                        },{
                            title: '外部发送',
                            extend: 'data-toggle="tooltip"',
                            icon: 'fa fa-paper-plane',
                            classname: 'btn btn-danger btn-xs btn-click',
                            click:function(data,row){
                                Fast.api.open('sms/task_send/add?link_from=2&ids='+row.id);
                            },
                        }],
                        formatter: Table.api.formatter.operate
                    }
                ]
            });
            Controller.api.bindevent();

        },
    };
    return Controller;
});