
var app = getApp();

Page({
	data: {
		isEmpty: true,
		flag: false,
		url: 'wxapp/user/feedback/feedback_detail.html',
		detail: []
	},

	// 生命周期
	onLoad: function(params){

		var that = this;
		function success(res, that){
      
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

			that.setData({
				detail: res.data.repsoneContent.feedback_detail,
				flag: true
			})

      if ( res.data.repsoneContent.feedback_detail.status == 1 ){
				that.setData({
					isEmpty: false
				})
			}
      
		}

		var url = app.globalData.baseUrl + this.data.url+'?data={"id":"'+params.id+'"}';
		app.netApply(url, {}, success, this)

	},

})