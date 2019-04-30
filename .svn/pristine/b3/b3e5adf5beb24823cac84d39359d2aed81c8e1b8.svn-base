var ajaxGoodsClassUrl = Yfj.HOST + '/new-yfj-admin-center/goods/goods_add/ajax_goods_class';
layui.use(['form'], function(){
    var form = layui.form;
    form.render();
    form.on('select(gc1)', function(data) {
        loadGc2(data.value,0,form);
    });

    form.on('select(gc2)', function(data) {
        loadGc3(data.value,0,form);
    });
});

function loadGc2(gc_id,selectGcId,form) {
    var loadClassObject = $("#gc2");
    var loadClass3Object = $("#gc3");
    $.getJSON(
        ajaxGoodsClassUrl
        , {gc_id: gc_id, deep: 1}
        , function (data) {
            loadClassObject.empty();       //清空之前的分类
            loadClass3Object.empty();      //清空之前的分类
            loadClassObject.append("<option value=''>请选择二级类目</option>");
            loadClass3Object.append("<option value=''>请选择三级类目</option>");
            $.each(data, function (index, data) {
                var selected ="";
                if(parseInt(selectGcId) === parseInt(data.gc_id)){
                    selected ="selected";
                }
                loadClassObject.append("<option "+selected+" value='" + data.gc_id + "' title='" + data.gc_description + "'>" + data.gc_name + "</option>");
            });
            form.render();
        }
    );
}

function loadGc3(gc_id,selectGcId,form) {
    var loadClassObject = $("#gc3");
    $.getJSON(
        ajaxGoodsClassUrl
        , {gc_id: gc_id, deep: 2}
        , function (data) {
            loadClassObject.empty();       //清空之前的分类
            loadClassObject.append("<option value=''>请选择三级类目</option>");
            $.each(data, function (index, data) {
                var selected ="";
                if(selectGcId !== undefined && parseInt(selectGcId) === parseInt(data.gc_id)){
                    selected ="selected";
                }
                loadClassObject.append("<option "+selected+" value='" + data.gc_id + "' title='" + data.gc_description + "'>" + data.gc_name + "</option>");
            });
            form.render();
        }
    );
}