layui.use(['form','upload',"element"], function() {
    var $ = layui.jquery,
        form = layui.form,
        element = layui.element,
        upload = layui.upload;
    form.render();
    var body = $("body");
    isDelete = 0;
    form.on('radio(property_select)', function (data) {
        if (data.value === literalType) {
            $(this).parents(".property").find(".property-txt").show();
            $(this).parents(".property").find(".add-property-img").hide();
            $(this).parents(".property").find(".show-property-img").hide();
        } else {
            $(this).parents(".property").find(".property-txt").hide();
            $(this).parents(".property").find(".add-property-img").show();
            $(this).parents(".property").find(".show-property-img").show();
        }
    });

    addNewExclusiveImg(upload, '.add-property-img');
    paddingData();
    $(".layui-tab-content").on("click",".close-tab",function () {
        var id = $(this).attr("data-id");
        var tabNum = $(".layui-tab-content").children(".layui-tab-item").length;
        // console.log(tabNum);
        if(tabNum <=1){
            layer.msg("只有一个标签不允许删除");
            return false;
        }
        if(tabNum !==($(this).parents(".layui-tab-item").index()+1)){
            isDelete = 1;
        }
        layer.open({
            content: '您确认删除该选项活动？',
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function () {
                $.ajax({
                    url: newExclusiveDelete,
                    type: 'get',
                    data:{group_id:id},
                    success: function (data) {
                        layer.closeAll();
                        if(data.status==="success"){
                            element.tabDelete('demo', id);
                        }else{
                            isDelete = 0;
                            layer.msg(data.repsoneContent.msg);
                        }
                    },
                    error: function () {
                        isDelete = 0;
                        layer.msg("新增失败！");
                    }
                });
            }
        });
    });

    $(".layui-tab-content").on("click",".add-tab",function () {

        var len = $(".layui-tab-content").find(".layui-tab-item").length;
        if (len > 5) {
            layer.msg("目前标签已超过6个，不能再添加！");
            return false;
        }
        //新增一个Tab项
        $.ajax({
            url: newExclusiveProductList,
            type: 'get',
            success: function (data) {
                html = data.repsoneContent.pageHtml;
                var id = new Date().getTime();
                element.tabAdd('demo', {
                    title: '新标签' //用于演示
                    , content: html
                    , id: id
                });
                form.render();
                addNewExclusiveImg(upload, ".add-property-img-" + data.requestTime);
                element.tabChange('demo', id);
            },
            error: function () {
                layer.msg("新增失败！");
            }
        });
    });

    /**
     * 向左移动
     */
    $(".layui-tab-content").on("click",".move-left",function () {
        var tabIndex = $(this).parents(".layui-tab-item").index();
        if (tabIndex !== 0) {
            var $div = $(this).parents(".layui-tab-item");
            var $li = $(".layui-tab-title").find("li").eq(tabIndex);
            $div.prev().before($div);
            $li.prev().before($li);
            moveDisplay();
        }
    });

    /**
     * 向右移动
     */
    $(".layui-tab-content").on("click",".move-right",function () {
        var tabIndex = $(this).parents(".layui-tab-item").index();
        var len = $(".layui-tab-content").find(".layui-tab-item").length;
        if (tabIndex !== (len-1)) {
            var $div = $(this).parents(".layui-tab-item");
            var $li = $(".layui-tab-title").find("li").eq(tabIndex);
            $div.next().after($div);
            $li.next().after($li);
            moveDisplay();
        }
    });

    body.on("click","#new-exclusive-add",function () {
        var xianshiName = $(this).parents(".layui-tab-item").find("#xianshi_name").eq(0).val();
        var xianshiLabel = $(this).parents(".layui-tab-item").find("#xianshi_label").eq(0).val();
        var imgName = $(this).parents(".layui-tab-item").find("#new-exclusive-img-url").eq(0).val();
        var id = $(this).data("groupId");
        var requestUrl = newExclusiveEdit;
        var currentTabIndex = $(this).parents(".layui-tab-item").index();
        if(parseInt(id) === 0 || id ==="") {
            requestUrl = newExclusiveAdd;
        }
        $.ajax({
            url: requestUrl,
            type: 'post',
            data: {
                xianshi_name: xianshiName,
                xianshi_label: xianshiLabel,
                img_name: imgName,
                group_id: id
            },
            success: function (data) {
                layer.msg(data.repsoneContent.msg);
                if(data.status==="success" && (parseInt(id) === 0 || id ==="")){
                    groupId = data.repsoneContent.groupId;
                    $(".layui-tab-title li").eq(currentTabIndex).empty().append(xianshiName);
                    $(".layui-tab-title li").eq(currentTabIndex).attr("lay-id",groupId);
                    paddingData(currentTabIndex);
                }
            },
            error: function () {
                layer.msg("操作失败！");
            }
        });
    });

    body.on("click","#AddNewProduct",function () {
        var currentGroupId = $(this).data("groupId");
        var currentTabIndex = $(this).parents(".layui-tab-item").index();
        var index = layer.open({
            type : 2,
            btn:['关闭'],
            title:'添加商品',
            closeBtn: 0,
            content: [addNewExclusiveProductList+'?xianshi_id='+currentGroupId],
            shadeClose: false,
            yes: function(index) {
                layer.close(index);
                groupId = currentGroupId;
                paddingData(currentTabIndex);
            }
        });
        layer.full(index);
    });

    
    body.on("click","#property-add",function () {
        var propertyType = $(".property .property-select:checked").val();
        var propertyName = $("#property-name").val();
        var describe = ""
            ,image_url = "";
        if (propertyName === "") {
            layer.msg("初始标签不能为空");
            return false;
        }
        var propertyImgUrl = $("#property-img-url");
        switch (propertyType) {
            case literalType:
                describe = $(".property  .property-txt").val();
                if (describe === "") {
                    layer.msg("设置 说明文本不能为空");
                    return false;
                }
                break;
            case imageType:
                image_url = propertyImgUrl.val();
                if (image_url === "") {
                    layer.msg("请上传属性图片");
                    return false;
                }
                propertyImgUrl.val("");
                break;
        }
        layer.load(1, {shade: [0.2, '#393D49']});
        $.ajax({
            url: newExclusiveSettingSave,
            type: 'post',
            data: {
                "type": propertyType,
                "inital_label": propertyName,
                "describe": describe,
                "image_url": image_url
            },
            success: function (data) {
                layer.closeAll('loading'); //关闭加载层
                layer.msg(data.repsoneContent.msg);
            },
            error: function () {
                layer.msg("保存新人商品信息失败！");
            }
            , complete: function () {
                layer.closeAll('loading'); //关闭加载层
            }
        });
    });

    function addNewExclusiveImg(upload, elem) {
        upload.render({
            elem: elem,
            url: Yfj.HOST + '/new-yfj-admin-center/goods/new_exclusive/upload_new_exclusive_image?image_name=file'
            , before: function (obj) {
                layer.load(1, {shade: [0.2, '#393D49']});
                var item = this.item;
                obj.preview(function (index, file, result) {
                    item.parents("div").find("img").eq(0).attr("src", result);
                })
            }
            , done: function (res, index, upload) {
                if (res.imagename === "") {
                    return false;
                }
                var item = this.item;
                item.parents("div").find("input[type='hidden']").eq(0).val(res.imagename);
                layer.closeAll('loading'); //关闭加载层
                layer.msg("图片上传成功", {time: 1000});
            }
        });
    }

    function paddingData(index) {
        if(typeof (index)==="undefined"){
            index = $(".layui-tab-title .layui-this").index();
        }
        $.ajax({
            url: newExclusiveProductList,
            type: 'get',
            data: {
                group_id:groupId
            },
            success: function (data) {
                $(".layui-tab-content .layui-tab-item").eq(index).html("");
                $(".layui-tab-content .layui-tab-item").eq(index).html(data.repsoneContent.pageHtml);
                form.render();
                addNewExclusiveImg(upload,".add-property-img-"+data.requestTime);
            },
            error: function () {
            }
        });
    }


    body.on("click",".edit",function() {
        var $tr = $(this).parents("tr");
        if($(this).text()==="保存"){
            var xianshiGoodsId = $tr.find("input[name='xianshi_goods_id[]']").val();
            var weight = $tr.find("#weight input").eq(0).val();
            var higherLimit = $tr.find("#higher_limit input").eq(0).val();
            var leftLimit = $tr.find("#left_limit input").eq(0).val();
            var lowerLimit = $tr.find("#lower_limit").find("input[type='text']").val();
            var snapUpLabel = $tr.find("#snap_up_label").find("input[type='text']").val();
            var xianshiPrice = $tr.find("#xianshi_price input").eq(0).val();
            var isFreeShipping = $tr.find("#is_free_shipping").is(':checked');
            var isFreeTax = $tr.find("#is_free_tax").is(':checked');
            var couponIsAvailable = $tr.find("#coupon_is_available").is(':checked');
            if (isFreeShipping === false) {
                isFreeShipping = 0;
            } else {
                isFreeShipping = 1;
            }
            if (isFreeTax === false) {
                isFreeTax = 0;
            } else {
                isFreeTax = 1;
            }
            if (couponIsAvailable === false) {
                couponIsAvailable = 0;
            } else {
                couponIsAvailable = 1;
            }
            layer.load(1, {shade: [0.2, '#393D49']});
            $.ajax({
                url: updateSnapUpProduct,
                type: 'post',
                data: {
                    xianshi_goods_id:xianshiGoodsId,
                    weight: weight,
                    higher_limit: higherLimit,
                    left_limit: leftLimit,
                    xianshi_price: xianshiPrice,
                    is_free_shipping:isFreeShipping,
                    is_free_tax:isFreeTax,
                    coupon_is_available:couponIsAvailable
                    , lower_limit:lowerLimit
                    , snap_up_label:snapUpLabel
                },
                success: function (data) {
                    layer.msg(data.repsoneContent.msg);
                    if (data.status === "success") {
                        $tr.find(".cancel").hide();
                        $tr.find(".edit").text("编辑");
                        $tr.find("input[name='show']").attr("disabled","");
                        $tr.find("input[name='show']").each(function () {
                            var dataId = 0;
                            if ($(this).is(':checked') === true) {
                                dataId = 1;
                            }
                            $(this).attr("data-id", parseInt(dataId));
                        });
                        enableTableRow($tr, form,true);
                    }
                },
                error: function () {
                    layer.msg("修改商品信息失败！");
                }
                ,complete:function () {
                    layer.closeAll('loading');
                }
            });
        }else {
            $tr.find(".cancel").show();
            $(this).text("保存");
            $tr.find("input[name='show']").attr("disabled",false);
            enableTableRow($tr, form);
        }
    });

    body.on("click",".cancel",function() {
        var $tr = $(this).parents("tr");
        $(this).hide();
        $tr.find(".edit").text("编辑");
        $tr.find("input[name='show']").attr("disabled",true);
        $tr.find("input[name='show']").each(function () {
            if(parseInt($(this).attr("data-id"))===1){
                $(this).attr("checked", true);
            }else{
                $(this).attr("checked", false);
            }
        });
        enableTableRow($tr,form);
    });

    element.on('tab(demo)', function(data) {
        groupId = $(this).attr("lay-id");
        var html = $(".layui-tab-content .layui-tab-item").eq(data.index).html();
        if (html === "") {
            paddingData(data.index-isDelete);
        }
        isDelete = 0;
    });

    body.on("click","#page a",function(){
        var jumpUrl = $(this).attr("href");
        var objectThis = $(this);
        $.ajax({
            url: jumpUrl,
            type: 'get',
            success: function (data) {
                objectThis.parents(".layui-tab-item").eq(0).html(data.repsoneContent.pageHtml);
                addNewExclusiveImg(upload,".add-property-img-"+data.requestTime);
                form.render();
            },
            error: function () {
            }
        });
        return false;
    });

    /**
     * 删除限时商品
     * @param xianshiGoodsId
     * @param isRefresh
     * @returns {boolean}
     */
    function deleteXianshiProduct(xianshiGoodsId,$tr) {
        layer.closeAll();
        var rs = false;
        $.ajax({
            url: deleteSnapUpProduct,
            type: 'post',
            async: false,
            data: {
                "xianshi_goods_id": xianshiGoodsId
            },
            success: function (data) {
                layer.msg(data.repsoneContent.msg);
                if (data.status === "success") {
                    rs = true;
                    $tr.remove();
                }
            },
            error: function () {
                layer.msg("删除活动商品信息失败！");
            }
        });
        return rs;
    }

    body.on("click",".del",function() {
        var $tr = $(this).parents("tr");
        var xianshiGoodsId = $tr.find("input[name='xianshi_goods_id[]']").val();
        layer.open({
            content: '您确认删除该活动商品吗？',
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function () {
                deleteXianshiProduct(xianshiGoodsId,$tr);
            }
        });
    });

    body.on("click","#bitchDelProduct",function () {
        var news_content = $(this).parents(".layui-form").find(".news_content").eq(0);
        layer.open({
            content: '您确认删除活动商品吗？',
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function () {
                var child = news_content.find('input[type="checkbox"]:not([name="show"]):checked');
                child.each(function (index) {
                    var xianshiGoodsId = $(this).parents("tr").find("input[name='xianshi_goods_id[]']").val();
                    var rs = deleteXianshiProduct(xianshiGoodsId,$(this).parents("tr"));
                    if (rs === false) {
                        layer.msg("删除失败");
                        return false;
                    }
                    if((child.length-1) === index) {
                        layer.msg("删除成功！共计"+child.length+"个商品！");
                    }
                });
            }
        });
    });


    /**
     * 排序
     */
    function moveDisplay() {
        var groupIds = [];
        $(".layui-tab-title").find("li").each(function (index, element) {
            var layId = parseInt($(element).attr("lay-id"));
            if (layId < 10000) {     //筛选活动id，避免将新增的时间戳也给放进来
                groupIds.push(layId);
            }
        });
        $.ajax({
            url: updateNewExclusiveSort,
            type: 'post',
            data: {
                "group_ids": groupIds
            },
            success: function (data) {
                if (data.status === "fail") {
                    layer.msg("新人商品排序失败！");
                }
            },
            error: function () {
                layer.msg("新人商品排序失败！");
            }
        });
    }
    
    function enableTableRow(obj,form,rs) {
        if (typeof (rs) === "undefined") {
            rs = false;
        }
        obj.find("td").each(function () {                       // 获取当前行的其他单元格
            obj_text = $(this).find("input:text");            // 判断单元格下是否有文本框
            obj_span = $(this).find("span");                 // 判断单元格下是否有文本框
            obj_img = $(this).find("img");
            if (!obj_text.length && !obj_span.length) {     // 如果没有文本框，则添加文本框使之可以编辑
                var text = trim($(this).text());
                $(this).html("<input type='text' class='layui-input' style='max-width:60px' maxlength='6' data-value='" + text + "' value='" + text + "'>");
            }
            else if (!obj_span.length) {
                if (rs) {
                    $(this).html(obj_text.val());
                } else {
                    $(this).html(obj_text.data("value"));
                }
            }
        });
        form.render();
    }
});