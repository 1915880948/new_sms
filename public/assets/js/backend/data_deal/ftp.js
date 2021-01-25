define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data_deal/ftp/index' + location.search,
                    add_url: 'data_deal/ftp/add',
                    //edit_url: 'data_deal/ftp/edit',
                    del_url: 'data_deal/ftp/del',
                    multi_url: 'data_deal/ftp/multi',
                    table: 'sms_ftp_task',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'),operate:false},
                        {field: 'model_date', title: __('Model_date'),operate:false},
                        {field: 'class_one', title: __('Class_one'),operate:false},
                        {field: 'class_two', title: __('Class_two'),operate:false},
                        {field: 'model_name', title: __('Model_name'),operate:false},
                        {field: 'model_num', title: __('Model_num'),operate:false},
                        {field: 'num', title: __('Num'),operate:false},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'total_num', title: __('Total_num'),operate:false},
                        {field: 'match_percent', title: __('Match_percent'),operate:false,formatter:function (value,row,index) {
                                if( row.total_num >0 ){
                                    return ( row.total_num/row.num*100).toFixed(2)+'%';
                                }else {
                                    return 0+'%';
                                }
                            }},
                        {field: 'end_time', title: __('End_time'), operate:false},
                        {field: 'ntype', title: __('Ntype'),formatter: Table.api.formatter.normal,
                            searchList:{1:'imei匹配pid_new',2:'pid_new匹配imei',3:'泰康匹配序列号'}
                        },
                        {field: 'imei_type', title: __('Imei_type'),formatter: Table.api.formatter.normal,
                            searchList:{0:'未选',1:'14imei',2:'15imei',3:'md14',4:'md15',5:'md15大写'},visible:false
                        },
                        {field: 'type', title: __('Type'),formatter: Table.api.formatter.normal,
                            searchList:{1:'一对一',2:'一对多'},visible:false
                        },
                        {field: 'status', title: __('Status'),formatter: Table.api.formatter.normal,
                            searchList:{1:'待处理',2:'处理中',3:'处理完毕',4:'已删除'}},
                        {field: 'creator', title: __('Creator')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, buttons: [{
                                name: 'click',
                                title: '下载',
                                extend: 'data-toggle="tooltip"',
                                icon: 'fa fa-download',
                                classname: 'btn btn-success btn-xs btn-download',
                                url:'data_deal/ftp/download',
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
                let file_json = JSON.parse(data.row.extparam);
                $("#textarea").val($("#textarea").val()+file_json.name+"\n");
                $("#files_list").val($("#files_list").val()+file_json.name+","+data.row.url+"|");
            });
            $(document).on("change", "select[name='row[ntype]']", function () {
                var ntype = $("select[name='row[ntype]']").val();
                if (ntype == 2) {
                    $("#imei-type").css('display', 'block');
                } else {
                    $("#imei-type").css('display', 'none');
                }

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