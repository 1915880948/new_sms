define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'plot/datapage/index' + location.search,
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                    table: 'sms_pass_data',
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
                        {field: 'url_link', title: __('Url_link'),operate: 'like'},
                        {field: 'create_time', title: __('Day'),formatter: Table.api.formatter.date, operate: 'like', type: 'date', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'second', title: __('Second'),operate: false},
                        {field: 'is_show', title: __('Is_show'),operate:false},
                        {field: 'is_top', title: __('Is_top'),operate:false},
                        {field: 'is_draw', title: __('Is_draw'),operate:false},
                        {field: 'is_retrie', title: __('Is_retrie'),operate:false},
                        {field: 'is_start', title: __('Is_start'),operate:false},
                        {field: 'is_jump', title: __('Is_jump'),operate:false},
                        {field: 'is_jump_click', title: __('Is_jump_click'),operate:false},
                        {field: 'is_prize', title: __('Is_prize'),operate:false},
                        {field: 'is_jump_close', title: __('Is_jump_close'),operate:false},
                        {field: 'other_click', title: __('Other_click'),operate:false},
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