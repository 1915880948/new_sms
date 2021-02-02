define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms/foreign/index' + location.search,
                    add_url: 'sms/task_send/add?channel_from=4&link_from=2',
                    edit_url: 'sms/task_send/edit',
                    del_url: 'sms/foreign/del',
                    multi_url: 'sms/foreign/multi',
                    table: 'sms_task_send',
                }
            });

            var table = $("#table");
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                //$("form select[name='channel_from']").val(2);
                // Form.events.cxselect(form);
                // Form.events.selectpage(form);
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
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'title', title: __('Title'),operate:'like',
                            cellStyle: cellStyle(),
                            formatter: paramsMatter,
                        },
                        // {field: 'exclude_recent_sent', title: __('Exclude_recent_sent')},
                        // {field: 'exclude_blacklist', title: __('Exclude_blacklist')},
                        // {field: 'channel_id', title: __('Channel_id')},
                        // {field: 'data_id', title: __('Data_id')},
                        // {field: 'data_pack_no', title: __('Data_pack_no')},
                        // {field: 'send_limit', title: __('Send_limit')},
                        // {field: 'sms_gate_id', title: __('Sms_gate_id')},
                        // {field: 'retry_on_failure', title: __('Retry_on_failure')},
                        // {field: 'retry_sms_gate_id', title: __('Retry_sms_gate_id')},
                        // {field: 'retry_limit_minute', title: __('Retry_limit_minute')},
                        // {field: 'sms_template_id', title: __('Sms_template_id')},
                        // {field: 'sms_content', title: __('Sms_content')},
                        // {field: 'link', title: __('Link')},
                        // {field: 'transfer_link', title: __('Transfer_link')},
                        // {field: 'dynamic_shortlink', title: __('Dynamic_shortlink')},
                        // {field: 'shortlink', title: __('Shortlink')},
                        /*{field: 'channel_from', title: __('Channel_from'),
                            formatter: Table.api.formatter.normal,
                            searchList:{0:'常规短信',1:'动态短信',2:'实时短信'},
                        },*/
                        {field: 'link_from', title: __('Link_from'),
                            formatter: Table.api.formatter.normal,
                            searchList:{0:'未知',1:'内部',2:'外部'},operate:false,visible: false
                        },
                        {field: 'sm_task_id', title: __('Sm_task_id'),visible: false},
                        {field: 'company', title: __('Company')},
                        {field: 'bank', title: __('Bank')},
                        {field: 'business', title: __('Business')},
                        {field: 'channel_id', title: __('Channel_id')},
                        {field: 'sp_name', title: __('Sp_name')},
                        // {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        // {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        // {field: 'schedule_percent', title: __('Schedule_percent')},
                        {field: 'task_num', title: __('Task_num'),operate:false},
                        // {field: 'total_num', title: __('Total_num')},
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
                        // {field: 'sp_num', title: __('Sp_num')},
                        // {field: 'retry_status', title: __('Retry_status')},
                        {field: 'price', title: __('成本'),operate:false,formatter:function (value,row,index) {
                                return (row.total_receive*row.price).toFixed(2);
                            }},
                        // {field: 'file_path', title: __('File_path')},
                        // {field: 'remark', title: __('Remark')},
                        // {field: 'phone_path', title: __('Phone_path')},
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
                        {field: 'finish_time', title: __('Finish_time'), operate:'RANGE', addclass:'datetimerange',visible: false},

                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
            var spList = Config.spList;
            $("#price").html(0);
            $("select[name='row[sms_gate_id]']").change(function () {
                var sms_gate_id = this.value;
                let price = 0;
                spList.forEach(function(item){
                    if( item.id == sms_gate_id ){
                        price = (item.price) ; return;
                    }
                });
                //console.log(sms_gate_id);
               $("#price").html(price);
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