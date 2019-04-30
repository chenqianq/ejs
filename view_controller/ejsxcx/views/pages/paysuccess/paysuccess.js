
//获取应用实例
var app = getApp();

Page({

  data: {
    flag:0,
    // 页面控制数据
    URL: app.globalData.baseUrl+'wxapp/checkout/confirmation/orderSuccess.html',
    header: app.globalData.header,

    // 后台数据
    order_amount: '126.20',
    order_sn: ''
  },

  //生命周期函数--监听页面显示
  onLoad: function (param) {
    
  	var order_sn = param.id;
  	var that = this;
  	var url = this.data.URL + '?data={"order_sn":"' + order_sn + '"}';

    function success(res, that) {

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
        order_amount: res.data.repsoneContent.order_amount,
        order_sn: res.data.repsoneContent.order_sn,
        flag: 1
      })

    }

    app.netApply(url, {}, success, that);

  },

  // 跳转至首页
  gotoIndex: function(){
    wx.switchTab({url:'../index/index'})
  }

})