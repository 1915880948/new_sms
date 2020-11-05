define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var provinceAll={},citysAll={},bankAll;

    $.ajax({
        url : "basic/province/getProvince",
        async : false,
        type  : 'GET',
        success : function (obj) {
            var list = obj.data;
            for (let index in list){
                provinceAll[list[index].province_id] = list[index].province_name;
            }
        }
    });
    $.ajax({
        url : "basic/province/getCity",
        async : false,
        type  : 'GET',
        success : function (obj) {
            var list = obj.data;
            for (let index in list){
                citysAll[list[index].city_id] = list[index].city_name;
            }
        }
    });
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'basic/mobile_attribute/index' + location.search,
                    add_url: 'basic/mobile_attribute/add',
                    edit_url: 'basic/mobile_attribute/edit',
                    del_url: 'basic/mobile_attribute/del',
                    multi_url: 'basic/mobile_attribute/multi',
                    table: 'sms_mobile_attribute',
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
                searchFormVisible: false,
                searchFormTemplate: 'mobilesearch',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'number_head', title: __('Number_head'),operate:'like'},
                        {field: 'province_id', title: __('Province_id'), formatter: Table.api.formatter.normal, //addclass:'selectpicker',
                            searchList:provinceAll, //data:'data-live-search= 1',  visible:true, // operate:'FIND_IN_SET'
                            //searchList:await provenceList(),
                        },
                        {field: 'city_id', title: __('City_id'),formatter: Table.api.formatter.normal,//addclass:'selectpicker',
                            searchList: citysAll, //data:'data-live-search= 1', //
                              //searchList: await cityList(),
                        },
                        {field: 'vendor_id', title: __('Vendor_id'),
                            formatter: Table.api.formatter.normal,
                            custom: {0: 'warning', 1: 'success',2:'danger',3:'info'},
                            searchList:{0:'中国电信',1:'中国移动',2:'中国联通',3:'通用'},
                        },
                        {field: 'create_time', title: __('Create_time'), operate:false, addclass:'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
            },

        }
    };
    return Controller;
});