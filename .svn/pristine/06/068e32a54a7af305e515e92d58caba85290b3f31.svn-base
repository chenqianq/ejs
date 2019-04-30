
var $ = layui.jquery;

timer = null;

// 模块排序
function sort(obj) {
    
    var i = 0;

    clearTimeout(timer);
    timer = setTimeout(function() {
        obj.find('.sort').each(function() {
            $(this).val(i);
            i = i + 1;
        });
        update_sort(obj);

    }, 200);
}

// 更新模块排序
function update_sort(obj) {
    
    var form_data = obj.find('.sort').serialize();
    var activity_id = $('#activity_id').val();
    form_data += '&activity_id='+activity_id;
    form_data += '&activity_type=special_subject';

    $.ajax({
        url:ajax_update_modular_sort,
        type:'post',
        data:form_data,
        dataType:'',
        success:function(data) {
            if ( data.status == 'failed' ) {
                layer.msg(data.msg);
            }
        }
    });

    var modular_id = $('#modular_id').val();

    if ( $('#modular_'+modular_id).data('type') == 'anchor' ) { // 移动锚点tab的时候重新加载编辑页面
        getModularHtml($('#modular_'+modular_id));
    }

}

/**
 * 验证条形码是否存在
 * @param object obj 需要检测条形码的输入控件
 */
function check_sku(obj) {

    var goods_shape_code = obj.val();

    $.ajax({
        type:'GET',
        url:ajaxCheckShapeCode,
        data:{goods_shape_code:goods_shape_code},
        dataType:'json',
        success: function(data) {
            if (data.status == 'false') {
                obj.parent().append('<span class="msg" style="color:red;">'+data.msg+'</span>');
            } else {
                obj.siblings('.msg').remove();
            }
        }
    });
}

function getModularHtml(obj) {
    var type = obj.data('type');
    var modular_id = obj.data('modular_id');
    var activity_id = $('#activity_id').val();
    var activity_type = $('#activity_type').val();
    var sort = obj.find('.sort').val();
    
    $('#modular_id').val(modular_id);

    $.ajax({
         url:Yfj.HOST+'/ejs-admin/goods/activity_modular/get_modular_html',
         type:'POST',
         data:{type:type,modular_id:modular_id,activity_id:activity_id,activity_type:activity_type,sort:sort},
         dataType:'JSON',
         success:function(data) {
             if ( data.status == 'success' ) {
                 $('.module-detail').children('.cont').remove();
                 $('.module-detail').append(data.repsoneContent);
             }
         }
    });
}

$(function(){

    // 模块向上移动
    $('.up').click(function() {
        var modular_id = $('#modular_id').val();

        $('#modular_'+modular_id).insertBefore($('#modular_'+modular_id).prev('.unit'));
        // document.getElementById('modular_'+modular_id).scrollIntoViewIfNeeded(); // 确保只在当前元素不可见的情况下才使其可见
        document.getElementById('modular_'+modular_id).scrollIntoView(false); // 确保只在当前元素不可见的情况下才使其可见
        sort($('.preview'));
    });

    // 模块向下移动
    $('.down').click(function() {
        var modular_id = $('#modular_id').val();
        
        $('#modular_'+modular_id).insertAfter($('#modular_'+modular_id).next('.unit'));
        // document.getElementById('modular_'+modular_id).scrollIntoViewIfNeeded(); // 确保只在当前元素不可见的情况下才使其可见
        document.getElementById('modular_'+modular_id).scrollIntoView(false); // 确保只在当前元素不可见的情况下才使其可见

        sort($('.preview'));

    });

    // 预览
    $('.yulan').click(function() {
        special_subject_id = $('#activity_id').val();
        layer.open({
            type: 2,
            title: false,
            area: ['350px', '95%'],
            // content: specialSubjectPreView+'?special_subject_id='+special_subject_id
            content: 'http://'+G_M_YIFANJIE_COM_DOMAIN + '/zc/special_subject/special_subject/detail.html?special_subject_id='+special_subject_id+'&preview=1'
        });
    });

    // 删除模块
    $('body').on('click', '.delete_modular', function(){

        var modular_id = $('#modular_id').val();

        if ( modular_id == 0 ) {
            layer.msg('请选择要删除的模块');
            return false;
        }

        layer.confirm(
            '确认删除?',
            {btn:['确认删除','取消']},
            function() {
                $.ajax({
                    url:ajax_delete_modular,
                    type:'POST',
                    data:{modular_id:modular_id},
                    dataType:'JSON',
                    success:function(data) {
                        if ( data.status == 'success' ) {
                            
                            $('#modular_id').val(0); // 删除后,标记选中模块id值重置为0
                            $('#modular_'+modular_id).prev().trigger("click"); // 一个模块删除后，自动切换至到上模块
                            $('#modular_'+modular_id).remove();
                            $('.cont').remove();

                        }
                        layer.msg(data.msg);
                    },
                    error:function() {
                        layer.msg('出错了, 请重新尝试');
                    }
                })
            }
        );

    });

    // 图片模块链接为商品条形码时 验证条形码是否存在
    $('.url').on('blur',function() {
        $(this).siblings('.msg').remove();
        
        var url_type = $(this).parents('.layui-form-item').find(".url_type option:selected").val();
        
        if ( url_type != 2 ) { // 当值为1的时候是商品条形码
            return false;
        }

        check_sku($(this));

    });


    $('.unit').on('mousemove',function() {

        $(this).css({
            'padding':'8px',
            'background':'#CCCCCC',
        })
        
        var modular_id = $('#modular_id').val();
        $('#modular_'+modular_id).css({
            'background':'#009688',  
        });
    });

    $('.unit').on('mouseout',function() {
        
        $(this).css({
            // 'box-shadow':'0px 1px 3px rgba(34, 25, 25, 0.2)',
            // 'display':'none',
            // 'margin-left':'10px;',
            'padding':'0',
            'background':'',
        })

        var modular_id = $('#modular_id').val();
        $('#modular_'+modular_id).css({
            'background':'#009688',  
            'padding':'8px',
        });

    });

    /*
	// 拖拽
	var disY = 0;
	$('body').on('mousedown', '.unit', function(ev){

		var oEvent = ev || event;
		// 鼠标点距离 div 顶部距离
		var disY = oEvent.clientY - $(this).offset().top;
		var oldY = oEvent.clientY;
		// 立 flag
		var t=0;
		var line = [];
		var line1 = [];
		// copy 并插入 this
		$('.unit').removeClass('.drag');
		var copyItem = $(this).addClass('drag').prop("outerHTML");
		// 把要拖拽的存起来
		var that = $(this);
		// 幻影标记
		$(this).addClass('abs');
		// 添加新的占位
		$(this).after( copyItem );

		// 定义边界
		for ( var i=0; i<$('.unit').length-2; i++ ){
			line.push( $('.unit').eq(i).offset().top );
		}
		for ( var n=0; n<$('.unit').length-1; n++ ){
			line1.unshift( $('.unit').eq(n).offset().top );
		}

		setTimeout(function(){
			if($('.abs')){
				$('.abs').css('z-index', 99);
			}
		},500)

		document.onmousemove = function(ev){
			var oEvent = ev || event;
			// 当前拖动物体的顶部距离
			t = oEvent.clientY - disY;
			if( t<0 ){
				t=0;
			}else if(t>document.documentElement.clientHeight - that.offset().height){
				t = document.documentElement.clientHeight - that.offset().height;
			}
			that.css('top', t+'px');
		}

		document.onmouseup = function(ev){

			// 判断方向
			var dis = ev.clientY - oldY;
			
			if( dis<-10 ){
				// 向上移动的检测
				for( var j=0; j<line.length; j++ ){
					if (t<line[j]){
						$('.drag').remove();
						$('.unit').eq(j).before( copyItem );
						$('.unit').removeClass('drag');
						document.onmousemove = null;
						document.onmouseup = null;
						sort($('.preview'));
						
						return;
					}
				}
			}else if(dis>10) {
				// 向下移动的检测
				for( var k=0; k<=line.length; k++ ){
					if (t>line1[k]){
						$('.drag').remove();
						$('.unit').eq(line.length-k-1).after( copyItem );
						$('.unit').removeClass('drag');
						document.onmousemove = null;
						document.onmouseup = null;
						sort($('.preview'));
						
						return;
					}
				}
			}
			// 移除幻影
			that.remove();
			document.onmousemove = null;
			document.onmouseup = null;
		}
		return false;
	})
    */
	// 点击模块显示模块的编辑信息
	$('.preview').on('click', '.unit', function(){

        var _this = $(this);

        var modular_id = $('#modular_id').val();

        if ( modular_id > 0 ) {

            var url = '';
            var type = $('#modular_'+modular_id).data('type');

            if ( type == 'add-text' ) {
                url = ajaxSaveModularText;
            } else if ( type == 'two-img' || type == 'one-img' ) {
                url = ajaxSaveModularImage;
            } else if ( 
                type == 'list1' ||
                type == 'list2' ||
                type == 'list3' ||
                type == 'list4' ||
                type == 'list5'
                ) {
                url = ajaxSaveModularList;
            } else if ( type == 'anchor' ) {
                url = ajaxSaveModularAnchor;
            }

            var form_data = $('#form').serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: form_data,
                success:function (data) {

                    if ( data.status == 'success' ) {

                        $('.unit').css({
                            'padding':'0',
                            'background':'',  
                        });

                        // 标记选中
                        _this.css({
                            'padding':'8px',
                            'background':'#009688',
                        })

                        getModularHtml(_this);

                    }

                    layer.msg(data.msg);
                    // return false;
                },
                error:function () {
                    return false;
                }
            });


        } else {
            $('.unit').css({
                'padding':'0',
                'background':'',  
            });

            // 标记选中
            _this.css({
                'padding':'8px',
                'background':'#009688',
            })

            getModularHtml(_this);
        }
	})

    // 插入模块
    $('.insert').click(function() {
        addModule($('#modular_id').val());
    });

	// 添加模块弹窗
	$('.preview .add').on('click', function(){
		addModule(0);
	})

	// 点击弹层 input 新增模块
	$('body').on('click', '.addM-POP input', function(){
		layer.closeAll();
		var name = $(this).attr('class');
        var modular_id = $(this).parents('.addM-POP').data('modular_id');

        var id = $('#activity_id').val(); // 活动id(专题主键id)
        var obj = null;

        if ( modular_id > 0 ) {
            obj = $('#modular_'+modular_id);
        } else {
            obj = $('.preview .add');
        }

		// 添加新增的模块
		$.ajax({
			url:Yfj.HOST+'/ejs-admin/goods/activity_modular/ajax_add_modular',
			type:'post',
			data:{type:name,id:id,activity_type:'special_subject'},
			dataType:'JSON',
			success: function(data) {
				if (data.status == 'success') {
					obj.before(data.activity_modular_html);
					sort($('.preview'));
				}
				layer.msg(data.msg);
			},
			error:function() {
				layer.msg('出错了, 请刷新页面');
			}

		});

	});
});

// 添加模块弹窗
var image_path = Yfj.HOST+'/view_controller/new-admin/views/static/images/special_subject/';
function addModule(modular_id){
	layer.open({
		type: 1,
		title: false,
		closeBtn: 0,
		shadeClose: true,
		area: ['1180px', '100%'],
		offset: 'r',
		content: '<div class="addM-POP" data-modular_id="'+modular_id+'">\
					<h6>图片</h6>\
					<div class="img-wrap">\
						<label><input class="one-img" type="radio" name="imgModule" /><span>单图</span></label>\
						<img class="one" src="'+image_path+'pos.png" />\
						<label><input class="two-img" type="radio" name="imgModule" /><span>2张图</span></label>\
						<img class="two" src="'+image_path+'pos.png" />\
						<img class="two" src="'+image_path+'pos.png" />\
					</div>\
					<h6>文本</h6>\
					<div class="text-wrap">\
						<label><input class="add-text" type="radio" name="textModule" /><span>多行文本</span></label>\
						<img class="text" src="'+image_path+'text.png" />\
					<div>\
					<h6>TAB</h6>\
					<div class="tab-wrap">\
						<label><input class="anchor" type="radio" name="tabModule" /><span>锚点tab</span></label>\
					<div>\
					<h6>商品列表</h6>\
					<div class="list-wrap">\
						<div class="FL">\
							<p>\
								<label><input class="list1" type="radio" name="listModule" />\
								<span>商品列表1</span></label>\
							</p>\
							<img src="'+image_path+'list1.png">\
						</div>\
						<div class="FL">\
							<p>\
								<label><input class="list2" type="radio" name="listModule" />\
								<span>商品列表2</span></label>\
							</p>\
							<img src="'+image_path+'list2.png">\
						</div>\
						<div class="FL">\
							<p>\
								<label><input class="list3" type="radio" name="listModule" />\
								<span>商品列表3</span></label>\
							</p>\
							<img src="'+image_path+'list3.png">\
						</div>\
						<div class="FL">\
							<p>\
								<label><input class="list4" type="radio" name="listModule" />\
								<span>商品列表4</span></label>\
							</p>\
							<img src="'+image_path+'list4.png">\
						</div>\
						<div class="FL">\
							<p>\
								<label><input class="list5" type="radio" name="listModule" />\
								<span>商品列表5</span></label>\
							</p>\
							<img src="'+image_path+'list5.png">\
						</div>\
					<div>\
				</div>'
	})
}

// ---------------modular_list_detail.php页面的 列表新增商品js start---------------------


    // --------------- 样式列表新增商品js end---------------------