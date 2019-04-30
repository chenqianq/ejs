/**
 * Created by Administrator on 2018/1/30.
 */
layui.use('element', function(){

    //优惠券发布
    $(document).on("click","#voucher_publish",function () {
        var voucherTemplateId = $(this).attr("data-id");
        $.ajax({
            type: 'post',
            url: 'ajax_operation',
            data: {
                type: "publish",
                voucher_template_id: voucherTemplateId
            },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    history.go(0);
                } else {
                    layer.msg(data.msg);
                }
            }
        });
    });

    // 下架
    $(document).on("click","#voucher_unshelve",function () {
        var voucherTemplateId = $(this).attr("data-id");

        layer.confirm('确定要下架?',{
            btn:['确定','取消']
        },function() {
            $.ajax({
                type: 'post',
                url: 'ajax_operation',
                data: {
                    type: "unshelve",
                    voucher_template_id: voucherTemplateId
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'success') {
                        history.go(0);
                    } else {
                        layer.msg(data.msg);
                    }
                }
            });
        });

    });

    $(document).on("click","#exchange_code",function () {
        var voucherTemplateId = $(this).attr("data-id");
        layer.prompt(
            {
                title: '请输入兑换码数量',
                value: ""
            }, function (value, index) {
                if(isNaN(value) || parseInt(value) <1 || parseInt(value) >10000){
                    layer.msg("请输入1到10000的正整数");
                }else {
                    $.ajax({
                        type: 'post',
                        url: 'ajax_operation',
                        data: {
                            type: "exchange_code",
                            voucher_template_id: voucherTemplateId,
                            code_num: value
                        },
                        beforeSend: function () {
                            i = ityzl_SHOW_LOAD_LAYER();
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status === 'success') {
                                layer.msg(data.msg);
                                setTimeout(function () {
                                    history.go(0);
                                },1500);
                            } else {
                                layer.msg(data.msg);
                            }
                            layer.close(i);
                        },
                        error:function (e) {
                        }
                    });
                    layer.close(index);
                }
            })
    });

    $(document).on("click","#voucher_delete",function () {
        var voucherTemplateId = $(this).attr("data-id");
        layer.open({
            title: '提示',
            content: '确认删除该优惠券？',
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function (index) {
                layer.close(index);
                $.ajax({
                    type: 'post',
                    url: 'ajax_operation',
                    data: {
                        type: "delete",
                        voucher_template_id: voucherTemplateId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'success') {
                            history.go(0);
                        } else {
                            layer.msg(data.msg);
                        }
                    }
                });
            }
        });
    });

    $(document).on("click","#use_scope",function () {
        var voucherTemplateId = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        if(parseInt(type)!==voucherUseItemLimit){
            return false;
        }
        layer.open({
            type : 2,
            btn:['确认'],
            area: ['500px', '300px'],
            title:'SKU明细',
            content: ['voucher_item?voucher_template_id='+voucherTemplateId+"&use_state="+voucherUsable],
            shadeClose: false,
            yes: function(index, layero) {
                layer.close(index);
            }
        });
    });

    $(document).on("click","#unusable_scope",function () {
        var voucherTemplateId = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        // console.log(voucherTemplateId+":"+type+"：");
        if (parseInt(type) !== voucherUnusableItemLimit) {
            return false;
        }
        layer.open({
            type: 2,
            btn: ['确认'],
            area: ['500px', '300px'],
            title: 'SKU明细',
            content: ['voucher_item?voucher_template_id=' + voucherTemplateId + "&use_state=" + voucherUnavailable],
            shadeClose: false,
            yes: function (index, layero) {
                layer.close(index);
            }
        });
    });

    $(document).on("click","#import_code",function () {
        var voucherTemplateId = $(this).attr("data-id");
        layer.open({
            title: '提示',
            content: '是否导入兑换码？',
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function (index) {
                layer.close(index);
                var object = $('#file_voucher_excel');
                object.attr("data-id", voucherTemplateId);
                object.click();
            }
        });
    });

    //excel上传
    $(document).on("change", "#file_voucher_excel", function () {
        var file_name = $(this).prop('name');
        var voucher_template_id= $(this).attr("data-id");
        $.ajaxFileUpload(
            {
                url: 'upload_excel?fileName=' + file_name,   //处理图片的脚本路径
                type: 'post',       //提交的方式
                secureuri: false,   //是否启用安全提交
                fileElementId: file_name,     //file控件ID
                dataType: 'json',  //服务器返回的数据类型
                success: function (data) {  //提交成功后自动执行的处理函数
                    if (data.status === "success") {
                        var excelName = data.excelName;
                        $.ajax({
                            type: 'get',
                            url: 'create_third_party_voucher_code',
                            data: {
                                voucher_exchange_code: excelName,
                                voucher_template_id: voucher_template_id
                            },
                            dataType: 'json',
                            success: function (data) {
                                layer.msg(data.msg);
                            }
                        });
                    } else {
                        layer.msg(data.error_msg);
                    }
                },
                error: function (data, status, e) {   //提交失败自动执行的处理函数
                    layer.msg(e);
                }
            }
        )
    });
});

function ityzl_SHOW_LOAD_LAYER(){
    return layer.msg('努力中...', {icon: 16,shade: [0.5, '#f5f5f5'],scrollbar: false,offset: '0px', time:100000}) ;
}