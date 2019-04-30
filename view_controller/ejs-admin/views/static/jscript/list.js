layui.config({
	base : "js/"
}).use(['form','layer','jquery'],function(){
	var form = layui.form,
		$ = layui.jquery;
    form.render();
	//全选
	form.on('checkbox(allChoose)', function(data){
		var child = $(data.elem).parents('table').find('input[type="checkbox"]:not([name="show"])');
		child.each(function(index, item){
		    if(typeof ($(item).attr("disabled"))==="undefined"){
                item.checked = data.elem.checked;
            }
		});
		form.render('checkbox');
	});

	//通过判断文章是否全部选中来确定全选按钮是否选中
	form.on("checkbox(choose)",function(data){
		var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"])');
		var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:not([name="show"]):checked');
        var allChooseChild =$(data.elem).parents('table').find('input.allChoose');
        allChooseChild.each(function(index, item){
            item.checked =  (childChecked.length === child.length);
        });
        var currentChild = $(data.elem).parents('tr').find('input[type="checkbox"]:not([name="show"])');
        var currentChildChecked = $(data.elem).parents('tr').find('input[type="checkbox"]:not([name="show"]):checked');
        var leftMenuChild = $(data.elem).parents('tr').prev('.leftMenu').find('input[type="checkbox"]:not([name="show"])');
        leftMenuChild.each(function(index, item){
            item.checked =  (currentChildChecked.length === currentChild.length);
        });
        form.render('checkbox');
	});

    //局部全选
    form.on('checkbox(leftMenuChoose)', function(data){
        var child = $(data.elem).parents('tr').next().find('input[type="checkbox"]:not([name="show"])');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });
});

function exportExcel(objectThis) {
    var export_excel= $("#export_excel");
    export_excel.val("1");
    $(objectThis).parents("form").submit();
    export_excel.val("0");
}


$(function () {
		$("#file_update_excel").live('change', function () { 
            file_name = $(this).prop('name'); 
            layer.load(1, {shade: [0.2, '#393D49']});
            setTimeout(function () {
                layer.closeAll('loading'); //关闭加载层
            }, 100000);
            $.ajaxFileUpload(
                {
                    url: 'import_dc_orders?fileName=' + file_name,   //处理图片的脚本路径
                    type: 'post',       //提交的方式
                    secureuri: false,   //是否启用安全提交
                    fileElementId: file_name,     //file控件ID
                    dataType: 'json',  //服务器返回的数据类型
                    success: function (data, status) {  //提交成功后自动执行的处理函数
					 
                        if (data.status === "success") {
                            layer.msg("导入成功");
                            setTimeout(function () {
                                history.go(0);
                            }, 2000);
                        } else {
                            layer.msg("导入失败");
			   setTimeout(function () {
                          	layer.closeAll('loading'); //关闭加载层
                            }, 2000);
                        }
                    },
                    error: function (data, status, e) {   //提交失败自动执行的处理函数
                        layer.msg(e);
						
						setTimeout(function () {
                          	layer.closeAll('loading'); //关闭加载层
                            }, 2000);
                    }
                    , complete: function () {
						layer.closeAll('loading'); //关闭加载层
					}
                })

        });

    // if( $('.childrenBody').height() != 0 ){
    //     var height = $('.childrenBody').height()+100;
    //     var par = $('.main_body', window.parent.document)
    //     par.height(height)
    // }

})