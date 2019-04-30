var app = getApp();

Page({

  data: {
    is_hidden: true,
    isWaiting: true, // 活动进行中
    isFail: false,    // 助力失败
    isSuccess: false, // 助力成功
    moveLeft: "0",
    completeColor: "",
    params: {},
    time: { "day": "", "hour": "", "min": "", "sec": "" },
    remaining_time: [],
    hasUserInfo: false,

    // 获得code接口
    getCodeURL: app.globalData.baseUrl + "wxapp/user/activity/attend_boost_activity.html",
    // 商品信息接口
    url: app.globalData.baseUrl+'wxapp/user/activity/user_boost_detail.html',
    // 助力接口
    helpURL: app.globalData.baseUrl+"wxapp/user/activity/help_boost_price.html",

    // 数据
    goods_info: [],
    boost_log: [],
    boost_spread: [],
    boost_code: '',
  },

  onLoad: function(params){
    
    var that = this;
    this.data.params = params;

    // 从分享链接进来
    if( params.code ){
      this.data.boost_code = params.code;
    }

    wx.getSetting({
      success: function (res) {
        // 判断是否获取过授权信息
        if ( !res.authSetting['scope.userInfo'] ) {
          // 无 code, 生成
          if( !that.data.boost_code ){
            // 未经授权，跳到授权页面
            wx.redirectTo({
             url: '../authorize/authorize?from=friendHelp/friendHelp&goods_id='
             + params['goods_id'] + '&activity_id=' + params['activity_id']
            })
          } else {
            // 有 code, 从分享链接进来
            wx.redirectTo({
             url: '../authorize/authorize?from=friendHelp/friendHelp&code='+that.data.boost_code
            })
          }
        } else {
          that.setData({ hasUserInfo: true })
          that.data.params['avatar'] = app.globalData.userInfo.avatarUrl;
        }
      }
    })

    // 发送请求, 获取code
    var timer1 = setInterval(function () {
      // 有用户信息且无 code 时, 请求code
      if (that.data.hasUserInfo && !that.data.boost_code) {
        clearInterval(timer1)
        app.netApply(that.data.getCodeURL, that.data.params, codeSuccess, that, {}, true, 'POST')
      }
    }, 500)

    // 请求Code返回为失败时
    function codeSuccess(res, that) {
      if (res.data.status == "failed") {
        wx.redirectTo({
          url: "../pageInvalid/pageInvalid"
        })
        return false;
      }
      that.data.boost_code = res.data.repsoneContent.boost_code;
    }

    // 发送请求, 获取商品信息
    var timer = setInterval(function () {
      if (that.data.boost_code) {
        clearInterval(timer);
        that.data.url += '?data={"code":"' + that.data.boost_code + '"}'
        app.netApply(that.data.url, {}, success, that);
      }
    }, 500)

    function success(res, that) {
      if (that.data.hasUserInfo && res.data.repsoneContent.status == "5") {
        wx.redirectTo({
          url: "../pageInvalid/pageInvalid"
        })
        return false;
      }

      that.setData({
        goods_info: res.data.repsoneContent.goods_info,
        boost_log: res.data.repsoneContent.boost_log,
        is_hidden: false
      });

      //修改进度条顶部价格位置  
      var offsetLeftVal = parseInt(535 * (that.data.goods_info.spread));

      if (offsetLeftVal == 535) {
        that.setData({ completeColor: "#f63e65" })
      }

      // 助力成功的判断
      if (res.data.repsoneContent.goods_info.status == 3) {
        that.setData({ isSuccess: true })
      }

      // 助力失败的判断
      if (res.data.repsoneContent.goods_info.status == 4) {
        that.setData({ isFail: true })
      }

      that.setData({
        moveLeft: offsetLeftVal + "rpx"
      })

      // 倒计时
      that.changeTime();
      that.countTime();
    }

  },

  // 生命周期函数
  onShow: function () {

  },

  onUnload: function(){
    this.data.timer1 = null;
  },

  // 时间戳格式转换并赋值
  changeTime: function () {
    
    var timeStamp = [];

    for (var i = 0; i < 1; i++) {
      timeStamp.push(this.data.goods_info.remaining_time);
      // 拼接key
      var day = 'remaining_time[' + i + '].day';
      var hour = 'remaining_time[' + i + '].hour';
      var min = 'remaining_time[' + i + '].min';
      var sec = 'remaining_time[' + i + '].sec';
      // 调用creatTimer传入时间戳返回对象包含天时分秒
      var time = app.creatTimer(timeStamp[i]);
      // 赋值
      this.setData({
        [day]: time.d,
        [hour]: time.h,
        [min]: time.m,
        [sec]: time.s
      })
    }

  },

  // 倒计时, 改变时间戳
  countTime: function () {

    var that = this;
    setTimeout(function () {
      for (var i = 0; i < 1; i++) {
        if (that.data.goods_info.remaining_time > 0) {
          that.data.goods_info.remaining_time -= 1;
        }
      }

      that.changeTime();
      that.countTime();

    }, 1000)
  },
  
  // 分享
  onShareAppMessage: function () {
    return {
      title: this.data.goods_info.goods_name,
      path: 'pages/friendHelp/friendHelp?code='+this.data.boost_code,
      imageUrl: this.data.goods_info.main_image_array[0],
      success: function (res) {
        // 转发成功
        console.log("转发成功:" + JSON.stringify(res));
      },
      fail: function (res) {
        // 转发失败
        console.log("转发失败:" + JSON.stringify(res));
      }
    }
  },

  // 助力
  help: function(){
    var that = this;

    function helpSuccess(res){
      
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
        title: '助力成功',
        icon: 'none',
        duration: 1500,
      });

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
          goods_info: res.data.repsoneContent.goods_info,
          boost_log: res.data.repsoneContent.boost_log
        });

        //修改进度条顶部价格位置  
        var offsetLeftVal = parseInt(535 * that.data.goods_info.spread);
        if (offsetLeftVal == 535) {
          that.setData({ completeColor: "#f63e65" })
        }
        // 助力成功的判断
        if (res.data.repsoneContent.goods_info.status == 3) {
          that.setData({ isSuccess: true })
        }
        // 助力失败的判断
        if (res.data.repsoneContent.goods_info.status == 4) {
          that.setData({ isFail: true })
        }

        that.setData({
          moveLeft: offsetLeftVal + "rpx"
        })

        // 倒计时
        that.changeTime();
        that.countTime();

      }

      app.netApply( that.data.url, {}, success, that,);
      
    }

    var data={
      "code": this.data.boost_code,
      "name": app.globalData.userInfo.nickName,
      "avatar": app.globalData.userInfo.avatarUrl
    }

    app.netApply(this.data.helpURL, data, helpSuccess, this, {}, true, 'POST')
    
  }

})