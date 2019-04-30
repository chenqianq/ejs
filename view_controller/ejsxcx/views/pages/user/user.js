var app = getApp();

Page({

  data: {
    flag: 0,
    sign_key : 1,
    scrollMt : false,
    userHeight : 0,
    page: 1,
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    isTrueName: 0,
    lock : false,

    header: app.globalData.header,
    URL: app.globalData.baseUrl+'wxapp/user/order/index.html',
    confirmUrl: app.globalData.baseUrl+'wxapp/checkout/confirmation/confirmOrder.html',
    buyAgainUrl: app.globalData.baseUrl+'wxapp/checkout/cart/buyOnceAddToCart.html',
    cancelUrl: app.globalData.baseUrl+'wxapp/checkout/confirmation/cancelOrder.html',

    // 数据
    "order_infos": [ ],
  },

  onLoad: function () {
    var that = this;
    wx.getSetting({
      success: function (res) {
        // 判断是否获取过授权信息
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              app.globalData.userInfo = res.userInfo;
              that.setData({
                userInfo: res.userInfo,
                hasUserInfo: true
              })
            }
          })
        }
      }
    })
  },

  // 生命周期函数
  onShow: function () {
    
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
        order_infos: res.data.repsoneContent.order_infos,
        isTrueName: res.data.repsoneContent.unrealized_authentication,
        flag: 1,
        lock: false
      })

      //创建节点选择器
      var query1 = wx.createSelectorQuery();
      var that1 = that;
      query1.select('#userINFO').boundingClientRect()
      query1.exec(function (res) {
        //res就是 所有id为userINFO的元素的信息 的数组
        that1.setData({ userHeight: res[0].height } );
      });

    }

    app.netApply(this.data.URL, {}, success, that);

    // 获取头像
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }

    } else {

      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })

    }

  },

  // 获得用户信息
  getUserInfo: function(e) {

    app.globalData.userInfo = e.detail.userInfo;
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })

  },

  // 滚动监听
  onPageScroll: function(e){
    var distance =  this.data.userHeight;
    if (e.scrollTop >= distance ){
      this.setData({ scrollMt : true });
    } else {
      this.setData({ scrollMt: false });
    }
  },

  //  切换标签, 发送请求
  tabList : function(e){

    var that = this;
    var self_key = e.currentTarget.dataset.key;
    var state = self_key - 1;
    var url = this.data.URL + '?data={"order_state":"' + state + '"}'

    this.setData({ 
      sign_key: self_key
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
        order_infos: res.data.repsoneContent.order_infos
      })

    }
    
    app.netApply(url, {}, success, that); 

  },

  // 按钮操作
  doAction: function(e){

    var lock = this.data.lock;

    if (lock == true) {
        return false;
    }

    this.data.lock = true;

    var that = this;
    var param = new Object();
    var url = ''

    var action_code = e.currentTarget.id;
    var order_sn = e.currentTarget.dataset.order;
    param.order_index = e.currentTarget.dataset.parent_index;

    // 再次购买
    if(action_code == 'buy_again'){

      url = this.data.buyAgainUrl + '?data={"order_sn":"' + order_sn+'"}';

      function success(res, that) {

        if (res.data.status == "failed") {
          wx.hideToast();
          setTimeout(function () {
            wx.showToast({
              title: res.data.longMessage,
              icon: 'none',
              duration: 1500
            })
        
            // 当resultCode等于特定的code时,刷新购物车
            if (res.data.resultCode == '110024') {
              setTimeout(function () {
                wx.switchTab({
                  url: '../cart/cart',
                })
              }, 1400);
            }

          }, 50);
          return false; 
        }

        wx.switchTab({
          url: '../cart/cart',
        })

        return false; 
        
      }
    
      app.netApply(url, {}, success, that, false, true); 

    }

    // 取消订单
    if(action_code == 'cancel_order'){

      url = this.data.cancelUrl + '?data={"order_sn":"' + order_sn + '"}';

      function success(res, that, param) {

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

        var selectOrderNode = 'order_infos[' + param.order_index + ']';

        wx.showToast({
            title: '操作成功',
            icon: 'none',
            duration: 1100
        });

        setTimeout(function() {

          that.setData({
            [selectOrderNode]: res.data.repsoneContent.order_info,
          });

          that.data.lock = false;
          
        },1000);

      }
    
      app.netApply(url, {}, success, that, param, true);

    }

    // 确认收货
    if(action_code == 'confrim_logistics'){

      url = this.data.confirmUrl + '?data={"order_sn":"' + order_sn + '"}';

      // 重新请求订单
      function successTwo(res, that) {

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
            order_infos: res.data.repsoneContent.order_infos,
            isTrueName: res.data.repsoneContent.unrealized_authentication,
            flag: 1
        })


        //创建节点选择器
        var query1 = wx.createSelectorQuery();
        var that1 = that;
        query1.select('#userINFO').boundingClientRect()
        query1.exec(function (res) {
            //res就是 所有id为userINFO的元素的信息 的数组
            that1.setData({ userHeight: res[0].height } );
        });
        that.data.lock = false;

      }

      function success(res, that, param) {

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

        var selectOrderNode = 'order_infos[' + param.order_index + ']';

        wx.showToast({
          title: '操作成功',
          icon: 'none',
          duration: 1000
        });

        app.netApply(that.data.URL, {}, successTwo, that);

      }
  
      app.netApply(url, {}, success, that, param, true);

    }

  },

  // 下拉加载更多
  onReachBottom: function(){
    var that = this;
    var page = this.data.page+1;
    var state = this.data.sign_key - 1;
    var url = this.data.URL + '?data={"page":"' + page + '","order_state":"' + state + '"}';
    var arrNow = this.data.order_infos;

    this.setData({page: page});

    var paramData = new Object();
    paramData.arrNow = arrNow;

    function success(res, that, paramData) {

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

      var arrNew = res.data.repsoneContent.order_infos;
      var order_infos = paramData.arrNow.concat(arrNew);
      that.setData({order_infos:order_infos}) 
    }
  
    app.netApply(url, {}, success, that, paramData);  
  }

})