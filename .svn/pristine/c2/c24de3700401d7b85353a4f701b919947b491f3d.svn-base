/**
 * Created by Administrator on 2018/2/1.
 */
layui.use(['form','laydate',"upload"], function() {
    var laydate = layui.laydate
        ,upload = layui.upload;
    //日期

    laydate.render({
        elem: '#start_date'
    });

    laydate.render({
        elem: '#end_date'
    });

    var file_voucher_excel = $("input[name='voucher_exchange_code']");
    var verification = $("#verification");
    upload.render({
        elem: '#upload_exchange_code'
        , url: 'upload_excel?fileName=file'
        ,accept: 'file' //普通文件
        ,before: function(obj) {
            layer.load(1, {shade: [0.2, '#393D49']});
        }
        , done: function (res) {
            layer.closeAll('loading'); //关闭加载层
            //如果上传失败
            if (res.code > 0) {
                return layer.msg('上传失败');
            }
            layer.msg('上传成功');
            verification.attr("data-code-num",res.codeNum);
            file_voucher_excel.val(res.excelName);
            //上传成功
        }
        , error: function () {
            layer.msg('上传失败8801');
        }
    });

    upload.render({
        elem: '.voucher-img',
        url: 'voucher_img_upload?fileName=file'
        , before: function (obj) {
            layer.load(1, {shade: [0.2, '#393D49']});
            obj.preview(function (index, file, result) {
                $(".show-property-img").attr("src", result);
            })
        }
        , done: function (res) {
            if (res.imagename === "") {
                return false;
            }
            $("#file_voucher_image").val(res.imagename);
            layer.closeAll('loading'); //关闭加载层
            layer.msg("图片上传成功", {time: 1000});
        }
    });

    verification.click(function () {
        var codeNum = verification.attr("data-code-num");
        layer.open({
            title: "验证",
            content: '采集到第三方兑换码' + codeNum + "个",
            btn: ['确认', '放弃导入'],
            shadeClose: false,
            yes: function (index) {
                layer.close(index);
            },
            btn2: function () {
                file_voucher_excel.val("");
                verification.attr("data-code-num", 0);
            }
        });
    });
});