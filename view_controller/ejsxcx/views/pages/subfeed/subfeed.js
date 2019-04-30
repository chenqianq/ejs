
var app = getApp();
import WxValidate from '../../utils/WxValidate.js';
Page({
  // 设置验证数据，需要放在page下
  initValidate() {
    const rules = {
      feedback_title: {
        required: true
      },
      feedback_details: {
        required: true
      }
    }

    const messages = {
      feedback_title: {
        required: '请输入标题'
      },
      feedback_details: {
        required: '请输入问题描述'
      }
    }

    // 创建实例对象
    this.WxValidate = new WxValidate(rules, messages)

  },
	data: {
		url: 'wxapp/user/feedback/save_feedback.html',
		uploadUrl: 'wxapp/user/feedback/upload_feedback_image.html',
		tempFilePaths: [],
		imageName: [],
	},

  // 初次加载
  onLoad: function(){
    this.initValidate();
  },

	// 添加图片
	addPic: function(){

		var that = this;
		// 选择图片
		wx.chooseImage({
			count:3,
			sizeType: ['original'],
			sourceType: ['album'],
			success: function(res){

				wx.showToast({
					title: '上传中',
					icon: 'none'
				})

				var tempFilePaths = res.tempFilePaths;
				var uploadUrl = app.globalData.baseUrl + that.data.uploadUrl;

				for( var i=0; i<tempFilePaths.length; i++ ){
					// 将图片上传
					wx.uploadFile({
						url: uploadUrl,
						filePath: tempFilePaths[i],
						name: 'file',
						header: app.globalData.header,
						success: function(response){
							var res = JSON.parse(response.data);
							that.data.tempFilePaths.push(res.repsoneContent.imagepath);
							that.data.imageName.push(res.repsoneContent.imageName);
							that.setData({
								tempFilePaths: that.data.tempFilePaths
							})
							if( i > tempFilePaths.length ){
								wx.hideToast();
							}
						}
					})
				}
			}
		})
	},

	// 删除某图片
	delete: function(e){
		var index = e.currentTarget.dataset.index;
		this.data.tempFilePaths.splice(index, 1)
		this.data.imageName.splice(index, 1)
		this.setData({
			tempFilePaths: this.data.tempFilePaths,
			imageName: this.data.imageName,
		})
	},

	// 提交表单
	subData: function(e){

    var that = this;
    const params = e.detail.value;
    // 表单数据验证处理
    if (!this.WxValidate.checkForm(params)) {

      const error = this.WxValidate.errorList[0];
      wx.showToast({
        title: error.msg,
        icon: 'none',
        duration: 2000
      })
      return false;

    }

		
		var url = app.globalData.baseUrl + this.data.url;
		var data = e.detail.value;
		data.image_arr = this.data.imageName;

    function success(res) {

      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {
          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 2000
          })
        }, 50)
        return false;
      }

      if (res.data.repsoneContent) {
        wx.navigateTo({ url: '../../pages/feedsuccess/feedsuccess' })
      }

    }
		
		app.netApply(url, data, success, this, '', '', 'POST')

	}

})