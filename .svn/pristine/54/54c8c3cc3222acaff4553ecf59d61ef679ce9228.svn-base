//index.js
//获取应用实例
var app = getApp()

Page({
  data: {

    // 控制数据
    flag : 0,
    URL: app.globalData.baseUrl+"wxapp/product/product/activity_detail.html",
    addURL: app.globalData.baseUrl+"wxapp/checkout/cart/addToCart.html",
    header: app.globalData.header,

    is_end: false,
    page: 1,
    params_id: 0,

    // 获取的数据
    type: 1,
    "activity_box_info": {},
    "activity_goods_box_info":[]
  },

  backData: {
    page:1,
    is_end: false,
  },

  onLoad: function(params){

    var that = this;
    this.setData({ params_id: params.id });
    var url = this.data.URL + '?data={"activity_id":' + params.id + '}';

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
          type: res.data.repsoneContent.type,
          "activity_box_info": res.data.repsoneContent.activity_box_info,
          "activity_goods_box_info": res.data.repsoneContent.activity_goods_box_info
        })
      that.setData({ flag: 1 })

    }

    app.netApply(url, {}, success, that);
  },

  //添加到购物车
  addCart : function(e){

    var that = this;
    var id = e.currentTarget.id;

    var addUrl = this.data.addURL+'?data={"goods_id":"'+id+'"}';

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

      wx.showToast({
          title: '加入成功',
          icon: 'none',
          duration: 2000//持续的时间
      })

    }

    app.netApply(addUrl, {}, success, that, false, true);


  },

  // 下拉加载更多
  onReachBottom: function () {
    if (this.backData.is_end == false) {
      var that = this;
      this.backData.page = this.backData.page + 1;
      var url = this.data.URL + '?data={"activity_id":' + this.data.params_id + ',"page":' + this.backData.page + '}';
      var arrNow = this.data.activity_goods_box_info;

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

        var arrNew = res.data.repsoneContent.activity_goods_box_info;
        
        if (arrNew == [] || arrNew == false) {
          that.backData.is_end = true;
          return false;
        }

        var goodsList = arrNow.concat(arrNew);

        that.setData({ 
          "activity_goods_box_info": goodsList 
        })

      }

      app.netApply(url, {}, success, that);

    }

  }

})
