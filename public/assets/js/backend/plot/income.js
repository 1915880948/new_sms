define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'plot/income/index' + location.search,
                    add_url: 'plot/income/add',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'plot/income/multi',
                    table: 'sms_bank_income',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showExport: false,
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'type', title: __('Type'),formatter: Table.api.formatter.status,searchList:{1:'守护保',2:'得保',3:'豪斯莱广发',4:'麦星广发',5:'顺风车',6:'中信银行'}},
                        {field: 'num', title: __('Num'),operate:false},
                        {field: 'file_name', title: __('File_name'),operate:'like'},
                        {field: 'creator', title: __('Creator')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        /*{field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,
                            buttons: [{
                                name: 'detail',
                                text: __('Detail'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'plot/income/detail'
                            }],
                            formatter: Table.api.formatter.operate
                        }*/
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $("#plupload-files").data("upload-success", function(data, ret){
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='file_name']").val(file_json.name);
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
        },
        detail:function () {
            $(document).on("click", ".btn-refresh", function () {
                $("#detail").bootstrapTable('refresh',{});
            });
            $("#detail").bootstrapTable({
                url:"plot/income/detail?id="+Fast.api.query('ids'),
                extend: {
                    index_url: "plot/income/detail?id="+Fast.api.query('ids'),
                    //edit_url: 'sms/link_short/edit?link_id='+Fast.api.query('ids'),
                    table: '',
                },
                search: false,                       //1.快捷搜索框,设置成false隐藏
                showToggle: false,                  //2.列表和视图切换
                showColumns: true,                 //3.字段显示
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
                    {field: 'channel', title: __('Channel'),},
                    {field: 'enter_time', title: __('Enter Time'),operate:false},
                    {field: 'creator', title: __('Creator')},
                    {field: 'create_time', title: __('Create_time'), operate:false,visible:false},
                ]
            });
            Controller.api.bindevent();

        },
    };
    return Controller;
});