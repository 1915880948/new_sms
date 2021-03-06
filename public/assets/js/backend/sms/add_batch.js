define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'upload'], function ($, undefined, Backend, Table, Form, Upload) {

    var Controller = {
        index: function () {

            // 初始化表格参数配置
            Table.api.init({
                search: true,
                advancedSearch: true,
                pagination: true,
                extend: {
                    "index_url": "sms/add_batch/index",
                    "add_url": "",
                    "edit_url": "",
                    "del_url": "",
                    "multi_url": "",
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'id', title: 'ID'},
                        {field: 'title', title: __('Title')},
                        {field: 'url', title: __('Url'), align: 'left', formatter: Table.api.formatter.url},
                        {field: 'ip', title: __('ip'), formatter:Table.api.formatter.search},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                    ]
                ],
                commonSearch: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);//当内容渲染完成后

            // 给上传按钮添加上传成功事件
            $("#plupload-files").data("upload-success", function (data, ret) {
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("#textarea").val($("#textarea").val()+file_json.name+"\n");
                $("#files_list").val($("#files_list").val()+file_json.name+","+data.row.url+"|");
                //$("input[name='file_name']").val(file_json.name);
                /*var url = Backend.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                Toastr.success("上传成功！");*/
            });
            $("#plupload-excels").data("upload-success", function (data, ret) {
                //这里进行后续操作
                console.log(data.row);
                let file_json = JSON.parse(data.row.extparam);
                $("input[name='excel_name']").val(file_json.name);
                /*var url = Backend.api.cdnurl(data.url);
                $(".profile-user-img").prop("src", url);
                Toastr.success("上传成功！");*/
            });
            
            // 给表单绑定事件
            Form.api.bindevent($("#update-form"), function () {
                /*$("input[name='row[password]']").val('');
                var url = Backend.api.cdnurl($("#c-avatar").val());
                top.window.$(".user-panel .image img,.user-menu > a > img,.user-header > img").prop("src", url);*/
                window.location.reload();
                return true;
            });
        },
    };
    return Controller;
});