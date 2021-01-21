define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree'], function ($, undefined, Backend, Table, Form, undefined) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/filter_black/index' + location.search,
                    add_url: 'data_deal/filter_black/add',
                    //edit_url: 'data_deal/filter_black/edit',
                    //del_url: 'data_deal/filter_black/del',
                    multi_url: 'data_deal/filter_black/multi',
                    filter_url: 'data_deal/filter_black/index?is_filter=',
                    download_beach_url: 'filter_black/downloadBatch',
                    table: 'sms_filter_black',
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
                is_filter===1? $('.btn-filter').text('显示全部'): $('.btn-filter').text('隐藏空来源');
                is_filter = (is_filter === 1?'':1);
                var url = options.extend.filter_url+is_filter;
                table.bootstrapTable('refresh',{url:url});
            });
            // 打包下载
            $(document).on("click", ".btn-downloadbatch", function () {
                var options = table.bootstrapTable('getOptions');
                var ids = Table.api.selectedids(table);
                Layer.confirm('<h4>你确定打包下载以下项吗？</h4><div>'+ids.join(',')+'</div>', {icon: 3, title: __('Warning'), offset: 100, shadeClose: true},
                    function (index) {
                        window.location.href = options.extend.download_beach_url+"?ids="+ids;
                        Layer.close(index);
                    }
                );
            });
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'source_name', title: __('Source_name'),operate:'like'},
                        {field: 'num', title: __('Num'),operate:false},
                        {field: 'total_num', title: __('Total_num'),operate:false},
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'end_time', title: __('End_time'), operate:false},
                        {field: 'status', title: __('Status'),formatter: Table.api.formatter.status,
                            searchList:{1:'待处理',2:'处理中',3:'处理完毕',4:'已删除',}},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-danger btn-xs btn-download',
                                url:'data_deal/filter_black/download',
                            }],
                            formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $("#plupload-files").data("upload-success", function (data, ret) {
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("#textarea").val($("#textarea").val()+file_json.name+"\n");
                $("#files_list").val($("#files_list").val()+file_json.name+","+data.row.url+"|");
            });
            // 选择需过滤的历史文件:
            $(document).on("click", "#fachoose-filter_history_ids", function () {
                var that = this;
                var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                parent.Fast.api.open("filter_history/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
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
        }
    };
    return Controller;
});