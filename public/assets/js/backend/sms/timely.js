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
                    index_url: 'sms/timely/index' + location.search,
                    list_url: 'sms/timely/list',
                    config_url: 'sms/timely/config',
                    edit_url: 'sms/timely/config',
                    del_url: 'sms/timely/del',
                    multi_url: 'sms/timely/config',
                    table: 'sms_task_send',
                }
            });

            var table = $("#table");
            Fast.config.openArea = ['1000px','800px'];

            // 指定搜索条件
            $(document).on("click", ".btn-list", function () {
                var parenttable = table.closest('.bootstrap-table');
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                //Bootstrap操作区
                var toolbar = $(options.toolbar, parenttable);

                var url = options.extend.list_url;
                Fast.api.open(url, __('Config'), $(this).data() || {});
            });

            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                $("form select[name='channel_from']").val(2);
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'task_id',
                sortName: 'task_id',
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
                        {field: 'task_id', title: __('Task_id'),operate:false},
                        {field: 'title', title: __('Title'),operate:'like',
                            // cellStyle: cellStyle(),
                            // formatter: paramsMatter,
                        },
                        {field: 'link_from', title: __('Link_from'),
                            formatter: Table.api.formatter.normal,
                            searchList:{0:'未知',1:'内部',2:'外部'},visible: false
                        },
                        {field: 'sm_task_id', title: __('Sm_task_id'),visible: false},
                        {field: 'company', title: __('Company')},
                        {field: 'bank', title: __('Bank')},
                        {field: 'business', title: __('Business')},
                        {field: 'channel_id', title: __('Channel_id')},
                        {field: 'sp_name', title: __('Sp_name')},
                        {field: 'task_num', title: __('Task_num'),operate:false},
                        {field: 'total_send', title: __('Total_send'),operate:false},
                        {field: 'total_receive', title: __('Total_receive'),operate:false,sortable:true},
                        {field: 'failed_num', title: __('Failed_num'),operate:false},
                        {field: '', title: __('成功率'),operate:false,formatter:function (value,row,index) {
                                if( row.total_receive >0 ){
                                    return ( row.total_receive/row.total_send*100).toFixed(2)+'%';
                                }else {
                                    return 0+'%';
                                }
                            }},
                        {field: 'total_click', title: __('Total_click'),operate:false,sortable:true},
                        {field: '', title: __('点击率'),operate:false,formatter:function (value,row,index) {
                                if( row.total_receive >0 ){
                                    return ( row.total_click/row.total_receive*100).toFixed(2)+'%';
                                }else {
                                    return 0+'%';
                                }
                            }},
                        {field: 'price', title: __('成本'),operate:false,formatter:function (value,row,index) {
                                return (row.total_receive*row.price).toFixed(2);
                            }},
                        {field: 'creator', title: __('Creator'),operate:'like',visible: false},
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.status,
                            searchList:{1:'待生成短链',2:'生成动态短链中',3:'等待发送',4:'发送中',5:'发送完成',6:'已中止',7:'已删除',8 :'无需发送', 9 :'暂存',
                                10: '短链生成完毕',
                                11: '创建超信任务失败',
                                12: '创建超信任务成功',
                                13: '超信任务添加手机号中',
                                14: '超信任务添加手机号成功',
                                15: '超信任务添加手机号失败',
                                16: '超信任务提交失败',
                                17: '入队列完毕',
                                18: '写入发送队列中',
                                19: '通道连接异常',},

                        },
                        {field: 'send_time', title: __('Send_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'finish_time', title: __('Finish_time'), operate:'RANGE', addclass:'datetimerange'},

                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
        list:function () {
            $(document).on("click", ".btn-refresh", function () {
                $("#list").bootstrapTable('refresh',{});
            });
            $(document).on("click", ".btn-add", function () {
                Layer.confirm('确认添加一条自动发送配置吗？',{},function (index) {
                    Fast.api.ajax({url: 'sms/timely/add',}, function (data) {
                        Toastr.success(data.msg);
                        Layer.closeAll('dialog');
                        $("#list").bootstrapTable('refresh',{});
                        return false;
                    });
                });
            });
            $("#list").bootstrapTable({
                url:"sms/timely/list",
                extend: {
                    index_url: "sms/timely/list",
                    add_url: 'sms/timely/add',
                    edit_url: 'sms/timely/config',
                    table: '',
                },
                pk: 'id',
                sortName: 'id',
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
                // pageSize:20,
                // pageList:[20,50,100,'all'],
                columns: [
                    {field: 'id', title: 'ID',operate:false},
                    {field: 'name', title: '业务下标',},
                    {field: 'title', title: '标题',},
                    {field: 'channel_id', title: '渠道号'},
                    {field: 'sp_info_id', title: __('通道'),},
                    {field: 'domain_short', title: __('短链域名'),operate:false},
                    {field: 'sms_content', title: __('短信文案'), cellStyle: cellStyle(), formatter: paramsMatter,},
                    {field: 'city', title: __('城市黑名单')},
                    {field: 'send_start_time', title: __('发送时间区间'),formatter:function (index,row,value) {
                            return row.send_start_time+'~'+row.send_end_time;
                        }},
                    {field: 'send_status', title: __('自动状态'),formatter: Table.api.formatter.normal,
                        searchList:{1:'开启',2:'关闭'}
                    },
                    {field: 'operate', title: __('Operate'), table: $("#list"), events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                ]
            });
            Controller.api.bindevent();
        },
        config: function (){
            Controller.api.bindevent();
        }
    };
    return Controller;
});


