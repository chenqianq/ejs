var app = getApp();

Page({

  data: {
    flag: 0, // 全页面显示控制
    isEmpty: false,
    params: {}, // 入口带过来的信息
    hasUserInfo: false,

    shareInfo: {},  // 分享需要的信息
    voucherInfo: {}, // 红包信息
    logArr: {},      // 红包领取信息
    is_self: false,  // 是否用户自己分享的红包
    status: -1,      // 1:第一次领取, 2:已获得, 3: 已领完 4: 已失效, 5: 用户当天领取超过5个
    timer1: null,

    // 接口路径
    codeURL: app.globalData.baseUrl+"wxapp/user/activity/share_red_envelope.html",
    url: app.globalData.baseUrl+"wxapp/user/activity/click_red_envelope.html",

  },

  // 初次加载
  onLoad: function(params){
   
    var that = this;
    this.data.params = params;

    if( params.code ){
      this.data.shareInfo['code']=params.code;
    }

    wx.getSetting({
      success: function(res){

        if( !res.authSetting['scope.userInfo'] ){
          // 无code, 生成
          if( !that.data.shareInfo.code ){
            wx.redirectTo({
             url: '../authorize/authorize?from=bonuses/bonuses&envelope_id='+params.envelope_id+'&order_sn='+params.order_sn
            })
          }else{
            // 有 code, 从分享链接进来
            wx.redirectTo({
              url: '../authorize/authorize?from=bonuses/bonuses&code='+that.data.shareInfo.code
            })
          }
          
        }else{
          that.setData({ hasUserInfo: true })
        }

      }
    })

  },

  // 生命周期函数
  onShow: function () {
    
    var that = this;

    // 第一次, 生成code
    this.data.timer1 = setInterval(function(){
      // 没有code, 才请求code
      if( that.data.hasUserInfo && !that.data.shareInfo.code ){
        clearInterval(that.data.timer1);
        var codeURL = that.data.codeURL+'?data={"envelope_id":"'+that.data.params.envelope_id+'","order_sn":"'+that.data.params.order_sn+'"}';
        app.netApply(codeURL, {}, codeSuccess, that);
      }
    }, 500)
    
    function codeSuccess(res, that) {
      
      if (res.data.status == "failed" ){
        wx.redirectTo({
          url: "../pageInvalid/pageInvalid"
        })
        return false;
      }

      that.data.shareInfo = res.data.repsoneContent;

    }
    
    // 用 code 换取信息
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
        is_self: res.data.repsoneContent.is_self,
        logArr: res.data.repsoneContent.logArr,
        voucherInfo: res.data.repsoneContent.voucherInfo,
        status: res.data.repsoneContent.status,
        flag: 1,
      })

      that.data.shareInfo.share_title = res.data.repsoneContent.share_info.share_title;
      that.data.shareInfo.share_image = res.data.repsoneContent.share_info.share_image;

    }

    var timer = setInterval(function(){

      var code = that.data.shareInfo.code;

      if( code ){
        clearInterval(timer);
        var data = {
          "code": that.data.shareInfo.code,
          "name": app.globalData.userInfo.nickName,
          "avatar": app.globalData.userInfo.avatarUrl
        }
        app.netApply(that.data.url, data, success, that, {}, false, 'POST');
      }

    },500)

  },

  // 关闭页面
  onUnload: function(){
    this.data.timer1 = null;
  },

  // 分享按钮
  onShareAppMessage: function () {
    return {
      title: this.data.shareInfo.share_title,
      path: 'pages/bonuses/bonuses?code='+this.data.shareInfo.code,
      imageUrl: this.data.shareInfo.share_image,
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

  // 回到首页
  toIndex: function(){
    wx.switchTab({
      url: '../index/index'
    })
  }

})