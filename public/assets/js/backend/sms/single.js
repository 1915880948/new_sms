define(['jquery', 'bootstrap', 'backend', 'table', 'form','bootstrap-table-fixed-columns'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sms/single/index' + location.search,
                    add_url: 'sms/task_send/add?channel_from=3&link_from=1',
                    edit_url: 'sms/task_send/edit',
                    check_url: 'sms/single/check',
                    clicklist_url: 'sms/single/clicklist',
                    //del_url: 'sms/task_send/del',
                    multi_url: 'sms/task_send/multi',
                    filter_url: 'sms/single/index?is_filter=',
                    failed_url: 'task_send/failedDownload',
                    success_url: 'task_send/successDownload',
                    click_url: 'task_send/clickDownload',
                    stop_url: 'sms/task_send/stop',
                    start_url: 'sms/task_send/start',
                    startall_url: 'task_send/startAll',
                    table: 'sms_task_send',
                }
            });
            var is_filter = '';
            var table = $("#table");
            // 指定搜索条件
            $(document).on("click", ".btn-filter", function () {
                var parenttable = table.closest('.bootstrap-table');
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                //Bootstrap操作区
                var toolbar = $(options.toolbar, parenttable);
                is_filter===1? $('.btn-filter').text('过滤空记录'): $('.btn-filter').text('显示全部');
                is_filter = (is_filter === 1?'':1);
                var url = options.extend.filter_url+is_filter;
                table.bootstrapTable('refresh',{url:url});
            });
            // 失败下载
            $(document).on("click", ".btn-filed_download", function () {
                var _this = this;
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                Layer.confirm('你确定失败下载选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    function (index) {
                        window.location.href = options.extend.failed_url+"?ids="+ids;
                        Layer.close(index);
                    }
                );
            });
            // 成功下载
            $(document).on("click", ".btn-success_download", function () {
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                Layer.confirm('你确定成功下载选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    function (index) {
                        window.location.href = options.extend.success_url+"?ids="+ids;
                        Layer.close(index);
                    }
                );
            });
            // 点击下载
            $(document).on("click", ".btn-click_download", function () {
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                //Layer.confirm('你确定点击下载选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                Layer.confirm('<h4>你确定点击下载以下项吗？</h4><div style="margin-left: -20px; width: 300px;"><textarea id="txt-Ids" cols="3" style="width:290px;height:90px;border-radius:5px;" >'+ids.join(',')+'</textarea></div>', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    function (index) {
                        window.location.href = options.extend.click_url+"?ids="+$("#txt-Ids").val();
                        Layer.close(index);
                    }
                );
            });
            // 批量开始发送任务
            $(document).on("click", ".btn-all_start", function () {
                var _this = this;
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                Layer.confirm('你确定开始发送选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    function (index) {
                        window.location.href = options.extend.startall_url+"?ids="+ids;
                        Layer.close(index);
                    }
                );
            });
            //批量空号检测
            $(document).on("click", ".btn-check", function () {
                var _this = this;
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                //Layer.confirm('你确定空号检测选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    //function (index) {
                        Fast.api.open(options.extend.check_url+"?type=1&ids="+ids, '空号检测', $(this).data() || {});
                        /*window.location.href = options.extend.check_url+"?ids="+ids;
                        Layer.close(index);*/
                    //}
                //);
            });
            //批量小号检测
            $(document).on("click", ".btn-small", function () {
                var _this = this;
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                //Layer.confirm('你确定空号检测选中的'+ids.length+'项吗？', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                //function (index) {
                Fast.api.open(options.extend.check_url+"?type=2&ids="+ids, '小号检测', $(this).data() || {});
                /*window.location.href = options.extend.check_url+"?ids="+ids;
                Layer.close(index);*/
                //}
                //);
            });
            //在普通搜索渲染后
            table.on('post-common-search.bs.table', function (event, table) {
                var form = $("form", table.$commonsearch);
                //$("form select[name='channel_from']").val(2);
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

                // $fixedHeaderColumns:true,

                // fixedColumns: true,//固定列
                // fixedNumber:2,//固定前两列

                columns: [
                    [
                        {checkbox: true},
                        {field: 'task_id', title: __('Task_id')},
                        {field: 'title', title: __('Title'),operate:'like',
                            // cellStyle: cellStyle(),
                            // formatter: paramsMatter,
                        },
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
                        {field: 'total_click', title: __('Total_click'),formatter:function(value,row){
                                var url="sms/single/clicklist?ids="+row.sm_task_id;
                                var str='<a href="'+url+'" class="btn btn-xs btn-dialog" title="点击列表" data-table-id="table" data-area=["90%","90%"]><i class="fa fa-message"></i> '+value+'</a>';
                                return str;
                            },operate:false,sortable:true},
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
                        {field: 'phone_path', title: __('Is_space'),formatter:function(value){if(value){return '是';}else{return '否';}},visible: false},
                        {field: 'small', title: __('Is_small'),formatter:function(value){if(value){return '是';}else{return '否';}},visible: false},
                        {field: 'send_time', title: __('Send_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'finish_time', title: __('Finish_time'), operate:'RANGE', addclass:'datetimerange',visible: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '一键复发',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-random',
                                classname: 'btn btn-primary btn-xs btn-click',
                                click:function(data,row){
                                    //console.log(row);
                                    if(row.total_num < 1 ){
                                        Layer.msg('没有需要复发的短信！',{icon: 2});
                                        return false;
                                    }
                                    Fast.api.open('sms/task_send/relapse?ids='+row.ids,'一键复发----'+'源ID：'+row.task_id+'--复发数量：'+row.total_num);
                                },
                            },{
                                title: '失败复发',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-retweet',
                                classname: 'btn btn-danger btn-xs btn-click',
                                click:function(data,row){
                                    if( row.status === 6 ){ Layer.msg('任务被删除',{icon:2}); return false;}
                                    if( row.total_send < 1 ){ Layer.msg('发送任务总量小于1，无需复发',{icon:2}); return false;}
                                    if( row.total_send === row.total_receive ){ Layer.msg('没有失败的短信，无需复发',{icon:2}); return  false;}
                                    Fast.api.open('sms/task_send/repeat?ids='+row.ids,'失败复发------'+'源ID：'+row.task_id+'--复发数量：'+row.total_num);
                                },
                            },{
                                name:'ajax',
                                title: '停止任务',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-pause',
                                classname: 'btn btn-warning btn-xs btn-ajax',
                                confirm: '确认停止任务？',
                                url: 'sms/task_send/stop',
                                success: function (data, ret) {
                                    //console.log(ret);
                                    table.bootstrapTable('refresh',{});
                                    //如果需要阻止成功提示，则必须使用return false;
                                    //return false;
                                },
                                error: function (data, ret) {
                                    //console.log(data, ret);
                                    Layer.alert(ret.msg);
                                    return false;
                                }
                            },{
                                name:'ajax',
                                title: '开始发送',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-play',
                                classname: 'btn btn-info btn-xs btn-ajax',
                                confirm: '确认开始发送？',
                                url: 'sms/task_send/start',
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
                            },{
                                title: '空号检测',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-link',
                                classname: 'btn btn-info btn-xs btn-dialog ',
                                url:'sms/single/check',
                                // callback:function (data) {
                                //     console.log(data);//控制输出回调数据
                                // }
                                // click: function (e, data) {
                                //     //Layer.alert("点击按钮执行的事件");
                                //     //location.href = '/admin.php/sms/link_short/index?link_id='+data.ids+'&ref=addtabs';
                                //     Fast.api.open('sms/link_short/index?link_id='+data.ids, '生成短链', $(this).data() || {});
                                // },
                            }],
                            // formatter:function (value,row,index) {
                            //     var table = this.table;
                            //     // 操作配置
                            //     var options = table ? table.bootstrapTable('getOptions') : {};
                            //     // 默认按钮组
                            //     var buttons = $.extend([], this.buttons || []);
                            //     // 所有按钮名称
                            //     var names = [];
                            //     buttons.forEach(function (item) {
                            //         names.push(item.name);
                            //     });
                            //     if (options.extend.dragsort_url !== '' && names.indexOf('dragsort') === -1) {
                            //         buttons.push(Table.button.dragsort);
                            //     }
                            //     if (options.extend.edit_url !== '' && names.indexOf('edit') === -1) {
                            //         Table.button.edit.url = options.extend.edit_url;
                            //         buttons.push(Table.button.edit);
                            //     }
                            //     if (options.extend.del_url !== '' && names.indexOf('del') === -1) {
                            //         buttons.push(Table.button.del);
                            //     }
                            //     if ( $.inArray(row.status,[1,2,3]) !== -1 ){
                            //         buttons.push({
                            //             name:'ajax',
                            //             title: '停止任务',
                            //             extend: 'data-toggle="tooltip"',
                            //             icon: 'fa fa-pause',
                            //             classname: 'btn btn-danger btn-xs btn-ajax',
                            //             // confirm: '确认停止任务？',
                            //             url: options.extend.stop_url,
                            //             success: function (data, ret) {
                            //                 alert(1);
                            //                 console.log(ret);
                            //                 row.status = ret.status;
                            //                 //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                            //                 table.bootstrapTable('refresh',{});
                            //                 //如果需要阻止成功提示，则必须使用return false;
                            //                 //return false;
                            //             },
                            //             error: function (data, ret) {
                            //                 console.log(data, ret);
                            //                 Layer.alert(ret.msg);
                            //                 return false;
                            //             }
                            //         });
                            //     }
                            //     if ( row.status === 6 ){
                            //         buttons.push({
                            //             title: '开始任务',
                            //             extend: 'data-toggle="tooltip"',
                            //             icon: 'fa fa-play',
                            //             classname: 'btn btn-danger btn-xs btn-ajax',
                            //             // confirm: '确认开始任务？',
                            //             url: options.extend.start_url,
                            //             success: function (data, ret) {
                            //                 alert(2);
                            //                 row.status = ret.status;
                            //                 //Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                            //                 table.bootstrapTable('refresh',{});
                            //                 //如果需要阻止成功提示，则必须使用return false;
                            //                 //return false;
                            //             },
                            //             error: function (data, ret) {
                            //                 console.log(data, ret);
                            //                 Layer.alert(ret.msg);
                            //                 return false;
                            //             }
                            //         });
                            //     }
                            //     return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                            // }
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

            // 选择短信模板
            $(document).on("click", "#fachoose-sms", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                parent.Fast.api.open("basic/sms_template/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var urlArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                urlArr.push(inputObj.val());
                            }
                            urlArr.push(data.url)
                            var result = urlArr.join(",");
                            if (maxcount > 0) {
                                var nums = value === '' ? 0 : value.split(/\,/).length;
                                var files = data.url !== "" ? data.url.split(/\,/) : [];
                                var remains = maxcount - nums;
                                if (files.length > remains) {
                                    Toastr.error(__('You can choose up to %d file%s', remains));
                                    return false;
                                }
                            }
                            inputObj.val(result).trigger("change").trigger("validate");
                        } else {
                            $("#" + input_id).val(data.row.copy).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(data.row.id).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });
            $("#plupload-files").data("upload-success", function(data, ret){
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='file_name']").val(file_json.name);
            });

            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
            var spList = Config.spList;
            let price = 0;
            let selectID = $("select[name='row[sms_gate_id]']").val();
            spList.forEach(function(item){
                if( item.id == selectID ){
                    price = (item.price) ; return;
                }
            });
            $("#price").html(price);
            $("select[name='row[sms_gate_id]']").change(function () {
                var sms_gate_id = this.value;
                spList.forEach(function(item){
                    if( item.id == sms_gate_id ){
                        price = (item.price) ; return;
                    }
                });
                //console.log(sms_gate_id);
                $("#price").html(price);
            });

            // 选择短信模板
            $(document).on("click", "#fachoose-sms", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                parent.Fast.api.open("basic/sms_template/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var urlArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                urlArr.push(inputObj.val());
                            }
                            urlArr.push(data.url)
                            var result = urlArr.join(",");
                            if (maxcount > 0) {
                                var nums = value === '' ? 0 : value.split(/\,/).length;
                                var files = data.url !== "" ? data.url.split(/\,/) : [];
                                var remains = maxcount - nums;
                                if (files.length > remains) {
                                    Toastr.error(__('You can choose up to %d file%s', remains));
                                    return false;
                                }
                            }
                            inputObj.val(result).trigger("change").trigger("validate");
                        } else {
                            $("#" + input_id).val(data.row.copy).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(data.row.id).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });
            $("#plupload-files").data("upload-success", function(data, ret){
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='file_name']").val(file_json.name);
            });

        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        },
        relapse:function () {
            Controller.api.bindevent();
            var spList = Config.spList;
            let price = 0;
            let selectID = $("select[name='row[sms_gate_id]']").val();
            spList.forEach(function(item){
                if( item.id == selectID ){
                    price = (item.price) ; return;
                }
            });
            $("#price").html(price);
            $("select[name='row[sms_gate_id]']").change(function () {
                var sms_gate_id = this.value;
                spList.forEach(function(item){
                    if( item.id == sms_gate_id ){
                        price = (item.price) ; return;
                    }
                });
                //console.log(sms_gate_id);
                $("#price").html(price);
            });

            // 选择短信模板
            $(document).on("click", "#fachoose-sms", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                parent.Fast.api.open("basic/sms_template/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var urlArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                urlArr.push(inputObj.val());
                            }
                            urlArr.push(data.url)
                            var result = urlArr.join(",");
                            if (maxcount > 0) {
                                var nums = value === '' ? 0 : value.split(/\,/).length;
                                var files = data.url !== "" ? data.url.split(/\,/) : [];
                                var remains = maxcount - nums;
                                if (files.length > remains) {
                                    Toastr.error(__('You can choose up to %d file%s', remains));
                                    return false;
                                }
                            }
                            inputObj.val(result).trigger("change").trigger("validate");
                        } else {
                            $("#" + input_id).val(data.row.copy).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(data.row.id).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });

        },
        repeat:function () {
            Controller.api.bindevent();
            var spList = Config.spList;
            let price = 0;
            let selectID = $("select[name='row[sms_gate_id]']").val();
            spList.forEach(function(item){
                if( item.id == selectID ){
                    price = (item.price) ; return;
                }
            });
            $("#price").html(price);
            $("select[name='row[sms_gate_id]']").change(function () {
                var sms_gate_id = this.value;
                spList.forEach(function(item){
                    if( item.id == sms_gate_id ){
                        price = (item.price) ; return;
                    }
                });
                //console.log(sms_gate_id);
                $("#price").html(price);
            });

            // 选择短信模板
            $(document).on("click", "#fachoose-sms", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                parent.Fast.api.open("basic/sms_template/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var urlArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                urlArr.push(inputObj.val());
                            }
                            urlArr.push(data.url)
                            var result = urlArr.join(",");
                            if (maxcount > 0) {
                                var nums = value === '' ? 0 : value.split(/\,/).length;
                                var files = data.url !== "" ? data.url.split(/\,/) : [];
                                var remains = maxcount - nums;
                                if (files.length > remains) {
                                    Toastr.error(__('You can choose up to %d file%s', remains));
                                    return false;
                                }
                            }
                            inputObj.val(result).trigger("change").trigger("validate");
                        } else {
                            $("#" + input_id).val(data.row.copy).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(data.row.id).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });

        },
        check:function () {
            $(document).on("click", ".btn-refresh", function () {
                $("#check").bootstrapTable('refresh',{});
            });
            var checktable = $("#check");
            checktable.bootstrapTable({
                url:"sms/single/check?type="+Fast.api.query('type')+"&ids="+Fast.api.query('ids'),
                extend: {
                    index_url: "sms/single/check?type="+Fast.api.query('type')+"&ids="+Fast.api.query('ids'),
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
                    {field: 'id', title: __('Id'),operate:false,visible: false},
                    {field: 'task_id', title: __('Task_id')},
                    {field: 'total', title: __('Total'),},
                    {field: 'success', title: __('Success'),operate:false},
                    {field: 'error', title: __('Error')},
                    {field: 'unkown', title: __('Unkown')},
                    {field: 'operate', title: __('Operate'), table: checktable, events: Table.api.events.operate,
                        buttons: [{
                            name: 'click',
                            title: '下载',
                            extend: 'data-toggle="tooltip"',
                            icon: 'fa fa-download',
                            classname: 'btn btn-danger btn-xs  btn-download',
                            url:'sms/single/export?type='+Fast.api.query('type')+"&task_id="+Fast.api.query('ids'),
                        }],
                        formatter: Table.api.formatter.operate
                    }
                ]
            });
            Controller.api.bindevent(checktable);
        },
        clicklist:function () {
            $(document).on("click", ".btn-refresh", function () {
                $("#clicklist").bootstrapTable('refresh',{});
            });
            var clicklist = $("#clicklist");
            clicklist.bootstrapTable({
                url:"sms/single/clicklist?ids="+Fast.api.query('ids'),
                extend: {
                    index_url: "sms/single/clicklist?ids="+Fast.api.query('ids'),
                    table: '',
                },
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                showColumns: false,                 //3.字段显示
                showExport: true,                  //4.导出按钮
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
                    {field: 'id', title: __('Id'),operate:false,visible: false},
                    {field: 'shortlink_id', title: __('Shortlink_id')},
                    {field: 'phone_sec', title: __('Phone_sec'),},
                    {field: 'ip', title: __('Ip'),operate:false},
                    {field: 'province_name', title: __('Rrovince_name')},
                    {field: 'city_name', title: __('City_name')},
                    {field: 'phone_model', title: __('Phone_model')},
                    {field: 'click_time', title: __('Click_time')},
                ]
            });
            Controller.api.bindevent(clicklist);
        },
    };
    return Controller;
});