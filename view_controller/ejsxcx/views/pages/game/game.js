var app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    "winningGameUrl": ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (params) {
    var that = this;
    var userInfo = app.globalData.userInfo;
    var avatarUrl = '';
    var nickName = '';
    var openid = app.openid;
    // console.log(userInfo, openid);
    // 设置当前页标题
    wx.setNavigationBarTitle({
      title: params.title
    });

    // 验证授权获取用户信息
    if (userInfo) {
      // console.log('已授权信息');
      getWinningGameUrl(openid, userInfo);
    } else {
      // console.log('未授权信息');
      wx.getSetting({
        success: function (res) {
          // 判断是否获取过授权信息
          if (!res.authSetting['scope.userInfo']) {
            // 未经授权，跳到授权页面
            wx.redirectTo({
              url: '../authorize/authorize?from=game/game'
            });
            return false;
          } else {
            wx.getUserInfo({
              success: function (res) {
                // console.log('第一步已授权信息补充');
                app.globalData.userInfo = res.userInfo;
                userInfo = app.globalData.userInfo;
                getWinningGameUrl(openid, userInfo);
              },
              fail: function() {
                wx.showToast({
                  title: '获取微信授权信息失败,请重试',
                  duration: 1000,
                  icon: none,
                  success: setTimeout(function () {
                    wx.switchTab('/pages/index/index');
                  }, 1000)
                });
              }
            })
          }
        },
        fail: function () {
          wx.showToast({
            title: '获取微信授权信息失败,请重试',
            duration: 1000,
            icon: none,
            success: setTimeout(function () {
              wx.switchTab('/pages/index/index');
            }, 1000)
          });
        }
      });
    }


    function getWinningGameUrl(openid, userInfo) {
      avatarUrl = encodeURIComponent(userInfo.avatarUrl);
      nickName = encodeURIComponent(userInfo.nickName);
      if (openid) {
        // console.log('已有openid');
        that.setData({
          winningGameUrl: app.globalData.baseUrl + 'wxapp/checkout/winning_order/winning_game_index.html?openid=' + openid + '&avatar_url=' + avatarUrl + '&nick_name=' + nickName
        });
        // console.log(that.data.winningGameUrl);
        return true;
      } else {
        // console.log('如果是公众号点击进入小程序情况,这时候需要获取openid');

        app.wxLogin().then((res) => {
          openid = app.openid;
          that.setData({
            winningGameUrl: app.globalData.baseUrl + 'wxapp/checkout/winning_order/winning_game_index.html?openid=' + openid + '&avatar_url=' + avatarUrl + '&nick_name=' + nickName
          });
          return true;
        }).catch((res) => {
          wx.showToast({
            title: '获取微信id失败,程序员哥哥正在修复',
            duration: 1000,
            icon: none,
            success: setTimeout(function () {
              wx.switchTab('/pages/index/index');
            }, 1000)
          });
          return false;
        })
      }
    }
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
    
  },

  binderrorFunction: function() {
    wx.showToast({
      title:'跳转到抽奖页面失败,请刷新重试~如果还是不能访问,程序员哥哥会火速处理的',
      duration: 1000,
      icon: none,
      success:setTimeout(function() {
          wx.switchTab('/pages/index/index');
      }, 1000)

    });
  }
})
