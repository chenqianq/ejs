//index.js
//获取应用实例
var app = getApp();

Page({
  data: {
    // 页面控制数据
    flag : 0,
    URL: app.globalData.baseUrl+'wxapp/front/home/index.html',
    header: app.globalData.header,
    time: {"day": "", "hour": "", "min": "", "sec": ""},
    remaining_time:[],
    // 请求加载到的数据
    "banner_box_info":[],
    "activity_box_info":[]
  },

  // 初次加载
  onLoad: function(){
    // 倒计时开始
    this.changeTime();
    this.countTime();
  },
  
  // 显示页面时执行
  onShow: function(){
    
    var that = this;

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
        banner_box_info: res.data.repsoneContent.banner_box_info,
        activity_box_info: res.data.repsoneContent.activity_box_info
      })

      // 初始化 remaining_time
      var len = that.data.activity_box_info.length;
      for (var i = 0; i < len; i++) {
        that.data.remaining_time.push(that.data.time);
      }

      that.setData({flag: 1})

    }

    app.netApply(this.data.URL, {}, success, this)

  },

  // 时间戳格式转换并赋值
  changeTime: function(){

    var activity = this.data.activity_box_info;
    var timeStamp = [];

    for( var i=0; i<activity.length; i++ ){

      timeStamp.push( activity[i].remaining_time  );
      // 拼接key
      var day = 'remaining_time['+i+'].day';
      var hour = 'remaining_time['+i+'].hour';
      var min = 'remaining_time['+i+'].min';
      var sec = 'remaining_time['+i+'].sec';

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
  countTime: function(){

    var that = this;
    var activity = this.data.activity_box_info;

    setTimeout(function(){
      for( var i=0; i<activity.length; i++ ){
        if( that.data.activity_box_info[i].remaining_time > 0 ){
          that.data.activity_box_info[i].remaining_time -= 1;
        }
      }
      that.changeTime();
      that.countTime();
    }, 1000)

  },

  // 扫码
  toScanCode: function () {
    wx.scanCode({
      success: (res) => {
        // 发送请求: 
        var url = app.globalData.baseUrl 
        + 'wxapp/front/home/change_goods_shape_code_to_goods_id.html?' 
        +'data={"goods_shape_code":"' + res.result + '"}';
      
        function success(res) {
          if (res.data.repsoneContent.status == 1 ) {
            wx.navigateTo({
              url: '../detail/detail?id=' + res.data.repsoneContent.goods_id
            })
          } else {
            wx.navigateTo({
              url: '../noResult/noResult'
            })
          }
        }

        app.netApply(url, "", success);

      }
    })
  }

})
