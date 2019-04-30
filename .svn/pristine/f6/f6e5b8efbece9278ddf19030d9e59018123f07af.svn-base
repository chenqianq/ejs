$(document).ready(function(){
	//列表下拉
    $('img[nc_type="flex"]').live('click', function(){
	// $('img[nc_type="flex"]').click(function(){
		var status = $(this).attr('status');
		var gc_parent_id = $(this).parents('tr').data('gc_parent_id');
		if(status == 'open'){

			var pr = $(this).parents('tr');

			var id = pr.data('gc_id');

			var obj = $(this);
			$(this).attr('status','none');
			//ajax
			$.ajax({
				url: 'index?ajax=1&gc_parent_id='+id,
				dataType: 'json',
				success: function(data){

                    if ( !data ) { // 没有子分类
                        obj.attr('src',RESOURCE_SITE_URL+'images/tv-item.gif');   
                        return false;
                    }

                    $('.top_move_'+gc_parent_id).find('.move').hide(); // 隐藏上下移动图标(确保有子分类才隐藏上级的子分类)

					var src='';
					for(var i = 0; i < data.length; i++){
						var tmp_vertline = "<img class='preimg' src='" + RESOURCE_SITE_URL +  "images/vertline.gif' />";
						src += "<tr class='"+pr.attr('class')+" row"+id+"' data-deep='"+data[i].deep+"' data-gc_parent_id='"+data[i].gc_parent_id+"' data-gc_id='"+data[i].gc_id+"'>";
						src += "<td><input type='checkbox' name='check_gc_id[]' value='"+data[i].gc_id+"' class='checkitem'>";
						
						src += "</td>";						
						
						//名称
						src += "<td class='w50pre name'>";
						
						src += '<div class="layui-inline">';

						//打开关闭图片
						if(data[i].have_child == 1){
							src += "<img status='open' nc_type='flex' src='" + RESOURCE_SITE_URL + "images/tv-expandable.gif' />";
						}else{
							src += "<img status='none' nc_type='flex' src='"+ RESOURCE_SITE_URL +"images/tv-item.gif' />";
						}

						src += '  <div class="layui-input-inline">';
						src += '    <input type="" name="gc_name" maxlength="6" data-ori="'+data[i].gc_name+'" value="'+data[i].gc_name+'" class="layui-input layui-btn-disabled gc_name" disabled>';
						src += '  </div>';
						
						//新增下级
						if(data[i].deep < 3){
							src += '  <div class="layui-input-inline">';
							src += '    <a class="layui-btn layui-btn-primary layui-btn-sm add_sub_class" >';
							src += '      新增下级';
							src += '    </a>';
							src += '  </div>';
						}

						src += '</div>';

						src += "</td>";

						//是否显示
						src += "<td>";

						var checked = '';

						if(data[i].gc_show == 1){
							checked = 'checked';
						}

                        src += '<input type="checkbox" data-ori="'+data[i].gc_show+'" value="'+data[i].gc_show+'" '+checked+' class="layui-btn-disabled gc_show" lay-skin="primary" disabled >';
						// src += '<input type="checkbox" data-ori="'+data[i].gc_show+'" value="'+data[i].gc_show+'" name="gc_show" '+checked+' class="layui-btn-disabled gc_show" lay-skin="primary" disabled >';
						src += "</td>";

						// 上传图片
						src += '<td>';
						src += '</td>';

						// 上下箭头
						// src += '<td>';
						// src += '上下箭头';
						// src += '</td>';

						src += '<td class="top_move_'+data[i].gc_parent_id+'">';
                        src += '<input type="hidden" name="sort['+data[i].gc_id+']" value="'+data[i].gc_show+'" class="sort" >';
						src += '<div class="move">';
						src += '<img class="left up" src="" >';
						src += '<img class="right down" src="" >';
						src += '</div>';
						src += '</td>';

						//操作
						src += "<td>";
						src += '<div class="layui-inline">';
						src += '<a class="layui-btn layui-btn-normal layui-btn-sm editBtn">编辑</a>';
						src += '<a style="display: none;" class="layui-btn layui-btn-sm save" >保存</a>';
						src += '<a style="display: none;" class="layui-btn layui-btn-sm layui-btn-primary cancel" >取消</a>';
						src += '<a class="layui-btn layui-btn-danger layui-btn-sm delete">删除</a>';
						src += '</div>';
						src += "</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[nc_type="flex"]').unbind('click');

                    init_move_img('top_move_'+id);
					//重现初始化页面
                    // $.getScript(RESOURCE_SITE_URL+"jscript/jquery.edit.js");
					// $.getScript(RESOURCE_SITE_URL+"jscript/jquery.goods_class.js");
					// $.getScript(RESOURCE_SITE_URL+"jscript/admincp.js");
				},
				error: function(){
					alert('获取信息失败');
				}
			});
		}
		if(status == 'close'){

			$(".row"+$(this).parents('tr').data('gc_id')).remove();
			$(this).attr('src',$(this).attr('src').replace("tv-collapsable","tv-expandable"));
			$(this).attr('status','open');


			var length = $('.top_move_'+gc_parent_id).parents('tr').find('img[status="close"]').length;

			if ( length == 0 ) {
				$('.top_move_'+gc_parent_id).find('.move').show(); // 显示上下移动图标
			}

		}
	})
});


// 新增一级分类
$(function() { 
    $('#add_top_class').click(function() {

        var timestamp = (new Date()).valueOf();

        $('tbody').prepend('\
            <tr class="top_tr" data-gc_parent_id="0">\
                <td>\
                    <input type="checkbox" name="check_gc_id[]" value="" class="checkitem">\
                </td>\
                <td style="text-align: left;">\
                    <div class="layui-inline" style="margin: 0;padding-left: 0;">\
                        <img status="close" nc_type="flex" src="'+ RESOURCE_SITE_URL +'images/tv-item.gif">\
                        <div class="layui-input-inline" style="margin: 0;padding-left: 0;">\
                            <input type="" name="gc_name" data-ori="" maxlength="6" value="" class="layui-input gc_name">\
                        </div>\
                        <div class="layui-input-inline">\
                            <a class="layui-btn layui-btn-primary layui-btn-sm add_sub_class" style="display: none;">\
                                新增下级\
                            </a>\
                        </div>\
                    </div>\
                </td>\
                <td>\
                    <input type="checkbox" data-ori="" value="0" name="gc_show" class="gc_show" lay-skin="primary" >\
                </td>\
                <td>\
                    <input type="hidden" name="image_name" value="" class="image_name">\
                    <button type="button" class="layui-btn gc_image1 gc_image'+timestamp+'" >\
                        <i class="layui-icon">&#xe67c;</i>上传图片\
                    </button>\
                    <img class="image_url" style="width: 30px;height: 30px;display: none;" data-ori="" src="" >\
                </td>\
                <td class="top_move_0">\
                    <input type="hidden" name="sort[0]" value="0" class="sort" >\
                    <div class="move">\
                        <img class="left up" src="" >\
                        <img class="right down" src="" >\
                    </div>\
                </td>\
                <td>\
                    <div class="layui-inline">\
                        <a style="display: none;" class="layui-btn layui-btn-normal layui-btn-sm editBtn">编辑</a>\
                        <a class="layui-btn layui-btn-sm add_new_class" >保存</a>\
                        <a class="layui-btn layui-btn-sm layui-btn-primary cancel" style="display: none;" >取消</a>\
                        <a class="layui-btn layui-btn-danger layui-btn-sm delete_new">删除</a>\
                    </div>\
                </td>\
            </tr>\
            ');

        layui.use('upload', function(){
            var upload = layui.upload;

            //执行实例
            var uploadInst = upload.render({
                elem: '.gc_image'+timestamp, //绑定元素
                url: 'goods_images_upload?image_name=file', //上传接口
                done: function(res){
                    //上传完毕回调
                    var item = this.item;
                    item.parents('tr').find('.image_url').show().attr('src',res.imagepath);
                    item.parents('tr').find('input[name="image_name"]').val(res.imagename);
                },
                error: function(){
                  //请求异常回调
                }
            });
        });
        init_move_img('top_move_0');
    });
});

// 新增下级分类
$(function() {
    $('.add_sub_class').live('click', function() {

        var _gc_parent_id = $(this).parents('tr').data('gc_parent_id'); // 分类自身的上级id
        var gc_parent_id = $(this).parents('tr').data('gc_id'); // 新增分类的上级id
        var deep = $(this).parents('tr').data('deep');
        var add_sub_class = 'add_sub_class';

        deep = parseInt(deep);

        if ( deep >= 3 ) {
            add_sub_class = '';
        } else {
            $(this).parents('tr').find('img[nc_type="flex"]').attr('src', RESOURCE_SITE_URL+'images/tv-collapsable.gif');
            $(this).parents('tr').find('img[nc_type="flex"]').attr('status','close');
        }

        $(this).parents('tr').after('\
            <tr class="top_tr row'+gc_parent_id+'" data-gc_parent_id="'+gc_parent_id+'">\
                <td>\
                    <input type="checkbox" name="check_gc_id[]" value="" class="checkitem">\
                </td>\
                <td>\
                    <div class="layui-inline">\
                        <img status="close" nc_type="flex" src="'+ RESOURCE_SITE_URL +'images/tv-item.gif">\
                        <div class="layui-input-inline">\
                            <input type="" name="gc_name" maxlength="6" data-ori="" value="" class="layui-input gc_name">\
                        </div>\
                        <div class="layui-input-inline">\
                            <a class="layui-btn layui-btn-primary layui-btn-sm '+add_sub_class+'" style="display: none;">\
                                新增下级\
                            </a>\
                        </div>\
                    </div>\
                </td>\
                <td>\
                    <input type="checkbox" data-ori="" value="0" name="gc_show" class="gc_show" lay-skin="primary" >\
                </td>\
                <td>\
                </td>\
                <td class="top_move_'+gc_parent_id+'">\
                    <input type="hidden" name="sort[0]" value="0" class="sort" >\
                    <div class="move">\
                        <img class="left up" src="" >\
                        <img class="right down" src="" >\
                    </div>\
                </td>\
                <td>\
                    <div class="layui-inline">\
                        <a style="display: none;" class="layui-btn layui-btn-normal layui-btn-sm editBtn">编辑</a>\
                        <a class="layui-btn layui-btn-sm add_new_class" >保存</a>\
                        <a class="layui-btn layui-btn-sm layui-btn-primary cancel" style="display: none;" >取消</a>\
                        <a class="layui-btn layui-btn-danger layui-btn-sm delete_new">删除</a>\
                    </div>\
                </td>\
            </tr>\
            ');
        
        $('.top_move_'+_gc_parent_id).find('.move').hide(); // 隐藏上下移动图标
        init_move_img('top_move_'+gc_parent_id);


    });
});
