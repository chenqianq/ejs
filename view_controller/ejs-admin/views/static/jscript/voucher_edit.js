/**
 * Created by Administrator on 2018/1/30.
 */
layui.use(['form','laydate'], function(){
    var laydate = layui.laydate
        ,form = layui.form;
    //日期
    form.render();
    laydate.render({
        elem: '#start_date'
    });

    laydate.render({
        elem: '#end_date'
    });

    //单击删除商品
    form.on('select(use_scope_item)',function (data) {
        var itemName = $(data.elem).find("option:selected").text();
        var goodId = data.value;
        // console.log(goodId);
        if(typeof (goodId) === "undefined"){
            return false;
        }
        layer.open({
            title:'是否确认删除？',
            content: itemName,
            btn: ['确认', '取消'],
            shadeClose: false,
            yes: function(index,layero) {
                layer.close(index); //如果设定了yes回调，需进行手工关闭
                var itemsObject = $("#use_scope_item_array");
                var items = itemsObject.val();
                var itemArray = items.split(",");
                $.each(itemArray,function(index,item) {
                    // index是索引值（即下标）   item是每次遍历得到的值；
                    if (item === goodId) {
                        itemArray.splice(index, 1);
                        return 0;              //退出循环
                    }
                });
                itemsObject.val(itemArray.join(","));
                $('#use_scope_item').find("option:selected").remove();
                form.render();
            }
        });
    });



    //通过条形码新增可使用优惠券商品
    $("#btn_use_shape").on("click",function () {
        var shape_code = $.trim($("#use_shape").val());
        if (shape_code === "") {
            layer.msg("请输入条形码");
            return false;
        }
        if (isNaN(shape_code)) {
            layer.msg("输入的条形码格式不正确，请重新输入！");
            return false;
        }
        addNewProduct(shape_code, "shape_code", $("#use_scope_item"),$("#use_scope_item_array"),'use_scope_item');  //新增商品
    });

    // //通过sku新增可使用优惠券商品
    // $("#btn_use_sku").on("click",function () {
    //     var skuId = $.trim($("#use_sku").val());
    //     if (skuId === "") {
    //         layer.msg("请输入sku的id");
    //         return false;
    //     }
    //     if (isNaN(skuId)) {
    //         layer.msg("输入的sku id格式不正确，请重新输入！");
    //         return false;
    //     }
    //     addNewProduct(skuId, "sku", $("#use_scope_item"),$("#use_scope_item_array"));  //新增商品
    // });

    //通过条形码新增不可使用优惠券商品
    $("#btn_unusable_shape").on("click",function () {
        var shape_code = $.trim($("#unusable_shape").val());
        if (shape_code === "") {
            layer.msg("请输入条形码");
            return false;
        }
        if (isNaN(shape_code)) {
            layer.msg("输入的条形码格式不正确，请重新输入！");
            return false;
        }
        addNewProduct(shape_code, "shape_code", $("#unusable_scope_item"),$("#unusable_scope_item_array"),'unusable_scope_item');  //新增商品
    });

    // //通过sku新增不可使用优惠券商品
    // $("#btn_unusable_sku").on("click",function () {
    //     var skuId = $.trim($("#unusable_sku").val());
    //     if (skuId === "") {
    //         layer.msg("请输入sku的id");
    //         return false;
    //     }
    //     if (isNaN(skuId)) {
    //         layer.msg("输入的sku id格式不正确，请重新输入！");
    //         return false;
    //     }
    //     addNewProduct(skuId, "sku", $("#unusable_scope_item"),$("#unusable_scope_item_array"));  //新增商品
    // });

    //分类一改变
    form.on("select(class_one)",function (data) {
        var classId = data.value;
        if(classId === ""){
            return false;
        }
        loadClassification(classId,$("#class_two"));
        var loadClassObject = $("#class_three");
        loadClassObject.empty();
        loadClassObject.append("<option value=''>全选</option>");      //新增一个全选
    });


    //分类二改变
    form.on("select(class_two)",function (data) {
        var classId = data.value;
        // if(classId === ""){
        //     $("#class_three").empty();
        //     console.log(classId);
        //     console.log('classId');
        //     return false;
        // }
        loadClassification(classId,$("#class_three"));      //加载分类
    });

    // 分类三改变
    form.on("select(class_three)",function(data) {
        var classId = data.value;
        if ( classId != '' ) {
            $('#class_id').val(classId);
        }
    });

    /**
     * 可用活动列表
     */
    form.on("select(use_activity)",function (data) {
        var entrepotId = $(data.elem).find("option:selected").attr("data-entrepot-id");
        if(parseInt(entrepotId)<=0 && entrepotId !== $("#voucher_postage").val()) {
            layer.msg("该活动不在你选择的仓库中");
            $("#use_activity").val('');
            form.render();
            return false;
        }
    });

    /**
     * 不可用活动列表
     */
    form.on("select(unusable_activity)",function (data) {
        var entrepotId = $(data.elem).find("option:selected").attr("data-entrepot-id");
        if (parseInt(entrepotId)<=0 && entrepotId !== $("#voucher_postage").val()) {
            layer.msg("该活动不在你选择的仓库中");
            $("#use_activity").val('');
            form.render();
            return false;
        }
    });

    //优惠券保存
    $("#voucherSave").on("click",function () {
        var enable = $(this).attr("data-enable");
        if(parseInt(enable) ===0){
            return false;
        }
        if (checkVoucherSubmit() === false) {
            return false;
        }
        $(this).attr("data-enable",0);
        $("#voucherForm").submit();
    });

    //更改仓库
    form.on("select(voucher_postage)",function (data) {
												showPlateData( ); 
        $("#use_scope_item").empty();
        $("#use_scope_item_array").val("");
        $("#unusable_scope_item").empty();
        $("#unusable_scope_item_array").val("");
        $("#use_activity").empty();
        $("#unusable_activity").empty();
        var entrepot_id = data.value;
        $.ajax({
            type: 'get',
            url: 'ajax_operation',
            data: {
                type: "group",
                entrepot_id:entrepot_id
            },
            dataType: 'json',
            success: function (data) {
                if(data.status==="success") {
                    $.each(data.list, function (index, xianshiInfo) {
                        $("#use_activity").append("<option value='" + xianshiInfo.xianshi_id + "' data-entrepot-id='" + xianshiInfo.entrepot_id + "' title=''>" + xianshiInfo.xianshi_name + "</option>");
                        $("#unusable_activity").append("<option value='" + xianshiInfo.xianshi_id + "' data-entrepot-id='" + xianshiInfo.entrepot_id + "' title=''>" + xianshiInfo.xianshi_name + "</option>");
                    });
                    form.render();
                }
            }
        });
        form.render();
    });

	 
    // 保存优惠券
    form.on("submit(voucher-submit)",function (data) {
        var voucherName = $("#voucher_name").val();
        if ($.trim(voucherName) === "") {
            layer.msg("请输入优惠券名称");
            return false;
        }
        var voucherType= $("input[name='voucher_type']:checked").val();
        var re = /^(-1)|(\d+(\.\d+)?)$/;
        var regexInt = /^[0-9]*[1-9][0-9]*$/;
        switch (parseInt(voucherType)) {
            case  1:
                var fc_limit_price = $("#fc_limit_price").val();
                var fc_price = $("#fc_price").val();
                if (fc_limit_price==="" || (parseInt(fc_limit_price)<0) || (parseFloat(fc_limit_price) > 9999)) {
                    layer.msg("优惠券满减门槛金额应在0到9999之间");
                    return false;
                }
                // if (!regexInt.test(fc_price) || !regexInt.test(fc_limit_price))
                // {
                //     layer.msg("优惠券满减金额应为整数");
                //     return false;
                // }
                if (fc_price==="" || parseFloat(fc_price) < 0) {
                    layer.msg("优惠券满减金额应大于0元");
                    return false;
                }
                // if (parseFloat(fc_price) > parseFloat(fc_limit_price)) { // 最新规则优惠金额允许大于门槛
                //     layer.msg("优惠券优惠金额不能大于满减门槛");
                //     return false;
                // }
                break;
            case  2:
                var discount_limit_price = $("#discount_limit_price").val();
                var discount = $("#discount").val();
                if (discount_limit_price==="" || parseInt(discount_limit_price) < 1 || parseInt(discount_limit_price) > 9999) {
                    layer.msg("优惠券折扣门槛金额应在1到9999之间");
                    return false;
                }
                if(typeof (discount.toString().split(".")[1]) !== "undefined"){
                    var length = discount.toString().split(".")[1].length;
                    if(length >1){
                        layer.msg("优惠券折扣小数点应该为1位");
                        return false;
                    }
                }
                if (discount==="" || parseFloat(discount) < 0.1 || parseFloat(discount) > 10) {
                    layer.msg("优惠券折扣应在0.1到10之间");
                    return false;
                }
                if (!regexInt.test(discount_limit_price))
                {
                    layer.msg("优惠券折扣门槛金额应为数字");
                    return false;
                }
                if (!re.test(discount) || !re.test(discount))
                {
                    layer.msg("优惠券折扣应为数字");
                    return false;
                }
                break;
            case  3:
            case  4:
                break;
            case  5:
                var bonded_warehouse_subsidy = $("#bonded_warehouse_subsidy").val();
                if (bonded_warehouse_subsidy==="" || parseFloat(bonded_warehouse_subsidy) > 9999 || parseFloat(bonded_warehouse_subsidy) < -1) {
                    layer.msg("保税仓税费补助最多9999元");
                    return false;
                }
                if (!re.test(bonded_warehouse_subsidy))
                {
                    layer.msg("保税仓税费补助金额应为整数");
                    return false;
                }
                if(parseFloat(bonded_warehouse_subsidy) === 0){
                    layer.msg("保税仓税费补助不能为0");
                    return false;
                }
                break;
            case  6:
                var direct_purchase_subsidy = $("#direct_purchase_subsidy").val();
                if (direct_purchase_subsidy==="" || parseFloat(direct_purchase_subsidy) > 9999 || parseFloat(direct_purchase_subsidy) < -1) {
                    layer.msg("直邮税费补助最多9999元");
                    return false;
                }
                if (!re.test(bonded_warehouse_subsidy))
                {
                    layer.msg("直邮税费补助金额应为整数");
                    return false;
                }
                if(parseFloat(direct_purchase_subsidy) === 0){
                    layer.msg("直邮税费补助不能为0");
                    return false;
                }
                break;
        }
        var effectMode = $("input[name='voucher_give_out_type']:checked").val();
        switch (parseInt(effectMode)) {
            case 1:
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                if (start_date === "" || end_date === "") {
                    layer.msg("时间范围不能为空");
                    return false;
                }
                var start = new Date(start_date.replace("-", "/").replace("-", "/"));
                var end = new Date(end_date.replace("-", "/").replace("-", "/"));
                if (start > end) {
                    layer.msg("结束时间不能小于开始时间");
                    return false;
                }
                break;
            case 2:
                var effectDay = $("input[name='effective_day']").val();
                if (parseInt(effectDay) < 1 || parseInt(effectDay) > 999) {
                    layer.msg("优惠券有效时间为1到999天");
                    return false;
                }
                if (!re.test(effectDay)) {
                    layer.msg("优惠券有效时间应为整数");
                    return false;
                }
                break;
        }
        var use_activity = 0;
        var use_scope = $("input[name='use_scope']:checked").val();
        switch (parseInt(use_scope)){
            case 2:
                if($("#use_activity").val()==="请选择") {
                    layer.msg("请选择活动专场");
                    return false;
                }
                use_activity = $('#use_activity option:selected').val();
                break;
            case 3:
                // if($("#use_scope_item_array").val()==="") {
                //     layer.msg("请选择活动商品");
                //     return false;
                // }
                break;
        }
        var unusable_activity = 0;
        var unusable_scope = $("input[name='unusable_scope']:checked").val();
        switch (parseInt(unusable_scope)){
            case 1:
                if($("#unusable_activity").val()==="请选择") {
                    layer.msg("请选择活动专场");
                    return false;
                }

                unusable_activity = $('#unusable_activity option:selected').val();

                if ( unusable_activity && use_activity && unusable_activity == use_activity ) {
                    layer.msg('可使用与不可使用范围不能选择同一个活动! ');
                    return false;
                }

                break;
            case 2:
                // if($("#unusable_scope_item_array").val()==="") {
                //     layer.msg("请选择活动商品");
                //     return false;
                // }
                break;
        }

        var form_data =  $('.layui-form').serialize();

        // console.log(form_data);

        $.ajax({
            url:'',
            type:'get',
            data:form_data,
            dataType:'json',
            success:function(data) {
                layer.msg(data.msg);
                if ( data.status == 'success' ) {
                    setTimeout(function() {
                        window.location.href="voucher_apply";
                    }, 1000);
                }
            },
            error: function() {
                layer.msg('出错了,请重新再试! ');
            }

        });


        setTimeout(function () {
            $(data.elem).attr("disabled", "disabled");
        },100);
        setTimeout(function () {
            $(data.elem).removeAttr("disabled");
        },10000);
        return false;
    });


    /**
     * 新增商品
     * @param inputValue   输入的内容
     * @param type         输入的类型    
     * @param appendObject 追加商品存放对象
     * @param itemsObject  商品id存放对象
     * @param inputName    名称:unusable_scope_item,use_scope_item
     */
    function addNewProduct(inputValue,type,appendObject,itemsObject,inputName) {
        if ($.trim(inputValue) === "" || $.trim(type) === "" || typeof (appendObject) === "undefined") {
            layer.msg("新增商品失败！");
            return false;
        }
        // console.log(inputName);
        var entrepot_id = $("#voucher_postage").val();
        $.ajax({
            type: 'post',
            url: 'ajax_operation',
            data: {
                type: type,
                input_name:inputName+'_'+secondary, // secondary 次键值,用于标记可用优惠券商品与不可用优惠券商品的cookie
                shape_code: inputValue,
                entrepot_id:entrepot_id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    var items = itemsObject.val();
                    if (items === '') {
                        items += data.content.goods_id;
                    } else {
                        var itemArray = items.split(",");
                        if (jQuery.inArray(data.content.goods_id, itemArray) !== -1) {
                            layer.msg("该商品已添加！");
                            return false;
                        }
                        items += "," + data.content.goods_id;
                    }
                    layer.msg("添加成功！");
                    itemsObject.val(items);
                    appendObject.append("<option value='" + data.content.goods_id + "'>" + data.content.goods_name + "</option>");
                    form.render();
                } else {
                    layer.msg(data.msg);
                }
            }
        });
    }

    /**
     * 加载分类
     * @param classId              分类id
     * @param loadClassObject      加载分类的对象
     */
    function loadClassification(classId,loadClassObject) {
        if (classId === "" || typeof (loadClassObject) === "undefined") {
            loadClassObject.html("<option value=''>全选</option>");      //新增一个全选
            form.render();
            return false;
        }
        $('#class_id').val(classId);
        $.ajax({
            type: 'post',
            url: 'ajax_goods_class',
            data: {
                gc_id: classId
            },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    loadClassObject.empty();       //清空之前的分类
                    loadClassObject.append("<option value=''>全选</option>");      //新增一个全选
                    $.each(data.list,function (index,data) {
                        loadClassObject.append("<option value='" + data.gc_id + "' title='"+data.gc_description+"'>" + data.gc_name + "</option>");
                    });
                    form.render();
                } else {
                    layer.msg(data.msg);
                }
            }
        });
    }
});

function showPlateData(){
	voucherPostage = $('#voucher_postage').val();
	 
		$("input[name='voucher_type']").each(function(){
						currentType = $(this).val();	
						$(this).attr('disabled',false);
						if( currentType == 6 ){
								$(this).attr('disabled',true);
								$(this).attr('checked',false);
								$('#direct_purchase_subsidy').val('');
							}
						if( voucherPostage == 2 ){
							
							
							if( currentType == 5 ){
								$(this).attr('disabled',true);
								$(this).attr('checked',false);
								
								$('#bonded_warehouse_subsidy').val('');
								$('#bonded_warehouse_subsidy').attr('disabled',true);
								
							}
							
							if( currentType == 3 ){
								$(this).attr('disabled',true);
								$(this).attr('checked',false); 
							}
							 
						}
						else{
							if( currentType == 4 ){
								$(this).attr('disabled',true);
								$(this).attr('checked',false);
								$('#direct_purchase_subsidy').val('');
							}
							if( currentType == 5 ){
								$('#bonded_warehouse_subsidy').attr('disabled',false);
							}
							
						}
												  
													  })
	 
}




function get_query() {

    var query = '';
    var use_scope = $('input[name="use_scope"]:checked').val(); // 使用范围类型
    var unusable_scope = $('input[name="unusable_scope"]:checked').val(); // 不可以使用范围类型

    use_scope = parseInt(use_scope);
    unusable_scope = parseInt(unusable_scope);

    query += 'use_scope='+use_scope;

    var use_activity = 0;

    switch(use_scope) {
        case 1: // 优惠券使用范围限制为指定分类
            query += '&class_id='+$('#class_id').val();
            break;
        case 2: // 优惠券使用范围限制为指定活动

            use_activity = $('#use_activity option:selected').val();

            if ( !use_activity ) {
                layer.msg('请选择可使用活动专场! ');
                return false;
            }

            query += '&use_activity='+use_activity;
            break;
    }

    query += '&unusable_scope='+unusable_scope;

    var unusable_activity = 0;

    switch(unusable_scope) {
        case 1: // 不可使用优惠券的活动
            unusable_activity = $('#unusable_activity option:selected').val();

            if ( unusable_activity && use_activity && unusable_activity == use_activity  ) {
                layer.msg('可使用与不可使用范围不能选择同一个活动! ');
                return false;
            }

            if ( !unusable_activity ) {
                layer.msg('请选择不可使用活动专场! ');
                return false;
            }

            query += '&unusable_activity='+unusable_activity;
            break;
    }

    return query;
}

$(function() {
    $('.voucher_use_goods').click(function() {

        var query = get_query();
        var voucher_postage = $('#voucher_postage').val();
        if ( !query ) {
            return false;
        }

        query = "?use_type=-1&secondary="+secondary+'&voucher_template_id='+voucher_template_id+'&voucher_postage='+voucher_postage+'&'+query;

        console.log(query);

        var voucher_name = $('#voucher_name').val();
        var title = '查询'+ voucher_name +'优惠券可用商品';
        var index = layer.open({
            type : 2,
            btn:['关闭'],
            title: title,
            id:'voucher_use_goods_xxx',
            closeBtn:0,
            content: voucherUseGoods+query,
            shadeClose: false,
            yes : function(index) {
                layer.close(index);
            }
        });
        layer.full(index);
    });
})

