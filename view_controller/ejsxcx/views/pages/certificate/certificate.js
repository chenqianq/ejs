var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    // name:"未获取",
    // number: "3501999999999999",
    flag: false,
    items: [],
    startX: 0, //开始坐标
    startY: 0,
    isShow: false,
    showUrl: app.globalData.baseUrl + "wxapp/checkout/Confirmation/get_user_info.html",
    delUrl: app.globalData.baseUrl + "wxapp/checkout/Confirmation/del_card_info.html",
    cardName:'',
    cardId:'',
    status:0,
    param:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
   
    var params = {};
    params.id = (options.id != undefined) ? options.id :0;
    params.name = (options.name != undefined) ? options.name : '';
    params.type = (options.type != undefined) ? options.type : '';
    params.invitation = (options.invitation != undefined) ? options.invitation : '';
    params.authen = (options.authen != undefined) ? options.authen : '';
    
    this.setData({
      param: params,
    })
   
  },

  // 手指触摸动作开始 记录起点X坐标
  touchstart: function(e){
    
    //开始触摸时 重置所有删除
    this.data.items.forEach(function (v, i) {
      if (v.isTouchMove)//只操作为true的
        v.isTouchMove = false;
    })

    this.setData({
      startX: e.changedTouches[0].clientX,
      startY: e.changedTouches[0].clientY,
      items: this.data.items
    })

  },

  //滑动事件处理
  touchmove: function (e) {
    //已认证的不允许删除
    if(this.data.status == 1){
      return false;
    }
    // 定义多个变量
    var that = this,
    index = e.currentTarget.dataset.index,//当前索引
    startX = that.data.startX,//开始X坐标
    startY = that.data.startY,//开始Y坐标
    touchMoveX = e.changedTouches[0].clientX,//滑动变化坐标
    touchMoveY = e.changedTouches[0].clientY;//滑动变化坐标
    /**
      * 计算滑动角度
      * @param {Object} start 起点坐标
      * @param {Object} end 终点坐标
      */
    function angle (start, end) {
      var _X = end.X - start.X;
      var _Y = end.Y - start.Y;
      //返回角度 /Math.atan()返回数字的反正切值
      return 360 * Math.atan(_Y / _X) / (2 * Math.PI);
    }
    //获取滑动角度
    var angle = angle(
      { X: startX, Y: startY },
      { X: touchMoveX, Y: touchMoveY }
    );
    // 遍历所有能滑动的元素
    that.data.items.forEach(function (v, i) {
      v.isTouchMove = false
      //滑动超过30度角 return
      if (Math.abs(angle) > 30) return;
      if (i == index) {
        if (touchMoveX > startX) //右滑
          v.isTouchMove = false
        else //左滑
          v.isTouchMove = true
      }
    })
    //更新数据
    that.setData({
      items: that.data.items
    })
  },

  //删除事件
  del: function (e) {
    var that = this;
    var url = this.data.delUrl;
    // 请求成功的回调，回调内容可能是请求失败
    function success(res, that) {
      if( res.data.status == 'failed' ) {
        wx.hideToast();
        setTimeout(function () {
          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 2000
          })
        }, 50)
        return false;
      } else {
        var url = "../checkout/checkout?id=" 
        + that.data.param.id 
        + "&name=" 
        + that.data.param.name 
        + "&type=" 
        + that.data.param.type 
        + "&invitation=" 
        + that.data.param.invitation 
        + "&authen=" 
        + that.data.param.authen;
        wx.redirectTo({ url: url });
      }
    }
    app.netApply(url, {}, success, that); 
    return false;
    // 下方代码为列表有多个可删除且删除后依然停留在原页面的代码，本次删除身份信息不需要此功能
    this.data.items.splice(e.currentTarget.dataset.index, 1)
    this.setData({
      items: this.data.items,
      isShow: true
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    // 加载
    var that = this;
    var url = this.data.showUrl;

    function success(res, that) {

      for (var i = 0; i < 1; i++) {
        that.data.items.push({
          name: res.data.repsoneContent.trueName,
          number: res.data.repsoneContent.cardId,
          isTouchMove: false //默认隐藏删除
        })
      }
      that.setData({
        items: that.data.items,
        status: res.data.repsoneContent.cardVerifyStatus,
        flag: true,
      });

    }

    app.netApply(url, {}, success, that); 
    
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
    
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    
  }
})