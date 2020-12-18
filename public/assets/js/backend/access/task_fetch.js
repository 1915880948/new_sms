define(['jquery', 'bootstrap', 'backend', 'table', 'form','jstree'], function ($, undefined, Backend, Table, Form,undefined) {
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
                    index_url: 'access/task_fetch/index' + location.search,
                    add_url: 'access/task_fetch/add',
                    //edit_url: 'access/task_fetch/edit',
                    //del_url: 'access/task_fetch/del',
                    multi_url: 'access/task_fetch/multi',
                    table: 'sms_fetch_task',
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
                        {field: 'carrier', title: __('Carrier'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'未知',2:'移动',3:'联通',4:'电信', 5:'虚拟运营商'},
                        },
                        {field: 'batchs', title: __('Batchs'), operate:'like',
                            cellStyle: cellStyle(),
                            formatter: paramsMatter,
                        },
                        {field: 'url_nos', title: __('Url_nos'), operate:'like',
                            cellStyle: cellStyle(),
                            formatter: paramsMatter,
                        },
                        // {field: 'min_num', title: __('Min_num')},
                        // {field: 'max_num', title: __('Max_num')},
                        //{field: 'region', title: __('Region')},
                        // {field: 'black', title: __('Black')},
                        // {field: 'ntype', title: __('Ntype')},
                        {field: 'out_type', title: __('Out_type'),
                            formatter:Table.api.formatter.normal,
                            searchList:{0:'密文',1:'云海出数形式', 2:'AI出数形式'},
                        },
                        {field: 'status', title: __('Status'),
                            formatter: Table.api.formatter.normal,
                            searchList:{1:'提交',2:'出数中', 3:'出数完毕',4:'二次处理中',5:'二次处理完毕'},
                        },
                        // {field: 'file_path', title: __('File_path')},
                        {field: 'total_num', title: __('Total_num')},
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange'},
                        //{field: 'two_total_num', title: __('Two_total_num')},
                        //{field: 'two_end_time', title: __('Two_end_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'remark', title: __('Remark')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '详情',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-align-justify',
                                classname: 'btn btn-primary btn-xs btn-click',
                                click:function(data,row){
                                    //console.log(row);
                                    Fast.api.open('access/task_fetch/detail?ids='+row.id,'详情----'+'ID：'+row.id);
                                },
                            },{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs btn-click',
                                click:function(data,row){
                                    //console.log(row);
                                    Fast.api.open('access/task_fetch/download?ids='+row.id);
                                },
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
            // 选择批次号
            $(document).on("click", "#fachoose-batchs", function () {
                var that = this;
                let mod = $("#c-mod").val();
                let start_time = $("#c-start_time").val();
                let end_time = $("#c-end_time").val();
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                if( start_time > end_time ){
                    Toastr.error('时间区间不合法！！！');
                    return false;
                }
                parent.Fast.api.open("access/task_source/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&mod=" + mod + "&start_time=" + start_time+ "&end_time=" + end_time, __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var selectArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                selectArr.push(inputObj.val());
                            }
                            selectArr.push(data.select_arr);
                            var result = selectArr.join("|");
                            //inputObj.val(result).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(result).trigger("change").trigger("validate");
                        } else {
                            $("#c-" + input_id).val(data.row.task_id).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });
            // 选择URL模型编号
            $(document).on("click", "#fachoose-url_nos", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                parent.Fast.api.open("access/lable/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype , __('Choose'), {
                    callback: function (data) {
                        var button = $("#" + $(that).attr("id"));
                        var maxcount = $(button).data("maxcount");
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                        if (input_id && data.multiple) {
                            var selectArr = [];
                            var inputObj = $("#" + input_id);
                            var value = $.trim(inputObj.val());
                            if (value !== "") {
                                selectArr.push(inputObj.val());
                            }
                            selectArr.push(data.select_arr);
                            var result = selectArr.join("|");
                            //inputObj.val(result).trigger("change").trigger("validate");
                            $("#c-" + input_id).val(result).trigger("change").trigger("validate");
                        } else {
                            $("#c-" + input_id).val(data.row.code).trigger("change").trigger("validate");
                        }
                    }
                });
                return false;
            });


            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                var click_num = 0;
                //渲染权限节点树
                //变更级别后需要重建节点树
                $(document).on("click", '.button_tree', function () {
                    if ( click_num === 0) {
                        click_num++;
                        $.ajax({
                            url: 'access/basic/info_bank_area/tree',//"auth/group/roletree",
                            type: 'post',
                            dataType: 'json',
                            data: {},
                            success: function (ret) {
                                if (ret.hasOwnProperty("code")) {
                                    var data = ret.hasOwnProperty("data") && ret.data != "" ? ret.data : "";
                                    if (ret.code === 1) {
                                        //销毁已有的节点树
                                        $("#treeview").jstree("destroy");
                                        Controller.api.rendertree(data);
                                    } else {
                                        Backend.api.toastr.error(ret.msg);
                                    }
                                }
                            }, error: function (e) {
                                Backend.api.toastr.error(e.message);
                            }
                        });
                    }
                });
                $(document).on('click','.button_tree_visible',function () {
                    $("#treeview").toggle();
                });
                $('#treeview').bind("activate_node.jstree", function (obj, e) {
                    // 处理代码
                    // 获取当前节点
                    //var currentNode = e.node;
                    var treeNode = $('#treeview').jstree(true).get_checked(true); //获取所有 checkbox 选中的节点对象
                    var area = '';
                    $.each(treeNode,function (index,item) {
                        area += item.text+'|';
                    });
                    $('#c-region').val(area.substring(0,area.length-1));
                });

                $(document).on('change',"select[name='bank_area']",function () {
                    $.ajax({
                        url: 'access/basic/info_bank_area/area_ajax',//"auth/group/roletree",
                        type: 'get',
                        dataType: 'json',
                        data: {bank_id:$("select[name='bank_area']").val()},
                        success: function (ret) {
                            let area = '';
                            $.each(ret.data,function (index,item) {
                                area += item+'|';
                            });
                            $('#c-region').val(area.substring(0,area.length-1));
                            //console.log(ret);
                        }, error: function (e) {
                            Backend.api.toastr.error(e.message);
                        }
                    });

                });
            },
            rendertree: function (content) {
                $("#treeview")
                    .on('redraw.jstree', function (e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": {"stripes": true},
                        "checkbox": {
                            "keep_selected_style": false,
                        },
                        "types": {
                            "root": {
                                "icon": "fa fa-folder-open",
                            },
                            "menu": {
                                "icon": "fa fa-folder-open",
                            },
                            "file": {
                                "icon": "fa fa-file-o",
                            }
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content,
                        }
                    });
            }

        },
        detail:function () {
            Controller.api.bindevent();
        }
    };
    return Controller;
});