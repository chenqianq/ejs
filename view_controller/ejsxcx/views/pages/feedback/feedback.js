
var app = getApp();

Page({
	data: {
		url: 'wxapp/user/feedback/feedback_list.html',
		total_page: 1,
		current_page: 1,
		isEmpty: 0,
		feedbackList: []
	},

	// 生命周期
	onShow: function(){

		var that = this;
		var url = app.globalData.baseUrl+this.data.url;

		function success (res, that){

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

			// 为空的状态
			if( !res.data.repsoneContent.feedback_list[0] ){
				that.setData({
					isEmpty: true
				})
			}else{
				that.setData({
					feedbackList: res.data.repsoneContent.feedback_list,
					total_page: res.data.repsoneContent.total_page,
					current_page: res.data.repsoneContent.current_page
				})
			}

		}

		app.netApply(url, {}, success, this)

	},

	// 下拉分页
	onReachBottom: function(){

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

			var newArr = that.data.feedbackList.concat(res.data.repsoneContent.feedback_list)

			that.setData({
				feedbackList: newArr,
				total_page: res.data.repsoneContent.total_page,
				current_page: res.data.repsoneContent.current_page
			})

		}

		if( this.data.current_page < this.data.total_page ){

			var url = app.globalData.baseUrl+this.data.url+'?data={"page":"'+(this.data.current_page+1)+'"}';
			app.netApply(url, {}, success, this)
      
		}
	}

})