//index.js
//获取应用实例
var app = getApp()
Page({
  data: {

    // 页面控制数据
    flag : 0,
    URL: app.globalData.baseUrl+'wxapp/product/product/product_detail.html',
    addURL: app.globalData.baseUrl+"wxapp/checkout/cart/addToCart.html",
    getProductStorageURL: app.globalData.baseUrl+"wxapp/product/product/get_product_storage.html",
    
    header: app.globalData.header,
    num: 1,
    showPop: false,
    id: '',
    storage:0,
    time: { "day": "", "hour": "", "min": "", "sec": "" },
    remaining_time: [],
    "detailList":{
      "main_image_array": [],
      "detail_image_array": [],
      "final_price": '',
      "goods_name": '',
      "goods_id": '',
      "goods_shape_code": '',
      "b_goods_normal_price": '',
      "storage": '',
    }
  },

  backData: {
    add_button_lock : false,
    decrease_button_lock : false,
  },

  // 初次加载
  onLoad: function(params){

    var that = this;

    wx.showShareMenu({
      withShareTicket: true
    });

    var url = this.data.URL+'?data={"goods_id":"'+params.id+'"}';

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

      if (res.data.repsoneContent.goods_box_info[0]) {
        that.setData({
          detailList: res.data.repsoneContent.goods_box_info[0]
        });
      }

      if (res.data.repsoneContent.goods_box_info[0].storage <= 0) {
          that.setData({num:0});
      }

      var len = 1;

      if (res.data.repsoneContent.goods_box_info[0].remaining_time > 0) {
        for (var i = 0; i < len; i++) {
          that.data.remaining_time.push(that.data.time);
        }

        that.changeTime();
        that.countTime();

      }

      that.setData({flag:1});

    }

    app.netApply(url, {}, success, that, []);

  },

  // 邀请好友助力
  onShareAppMessage : function(e){
    // 分享商品
    return {
      title: this.data.detailList.goods_name,
      path: 'pages/detail/detail?id=' + this.data.detailList.goods_id,
      imageUrl: this.data.detailList.main_image_array[0],
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

  // 显示加入购物车弹窗
  showPop: function(e){
    this.setData({
      showPop: !this.data.showPop,
      id: e.currentTarget.id
    })
  },

  // 库存不足或已限购提示
  showTip: function(e) {
    var title = '';
    if (this.data.detailList.storage <= 0) {
      title = '商品库存不足';
    } else {
      title = '已达到商品限购数量';
    }
    wx.hideToast();
    setTimeout(function () {
      wx.showToast({
        title: title,
        icon: 'none',
        duration: 1500//持续的时间
      });
      return false;
    },50)
  },

  // 关闭所有弹窗
  closeAll: function(){
    this.setData({
      showPop: false
    })
  },

  // 点击加号
  add: function(){
      if (this.backData.add_button_lock == true) {
        return false;
      }
      this.backData.add_button_lock = true;
      // 如果当前没有库存,就不进入查询了
      if (this.data.detailList.storage <= 0) {
          wx.showToast({
              title: '商品库存不足',
              icon: 'none',
              duration: 1000//持续的时间
          });
        this.backData.add_button_lock = false;
          return false;
      }

      var that = this;
      var id = this.data.id;
      var url = this.data.getProductStorageURL+'?data={"goods_id":"'+id+'"}';

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

        that.backData.add_button_lock = false;

        var returnData = res.data;
        if (returnData.status == 'success') {
            // 如果库存不足
            if (returnData.repsoneContent.storage <= 0) {
                wx.showToast({
                    title: '商品库存不足',
                    icon: 'none',
                    duration: 1500//持续的时间
                });
                return false;
            }
            // 如果加入购物车数量大于库存
            if (returnData.repsoneContent.storage < that.data.num + 1) {
                wx.showToast({
                    title: '商品库存剩余' + returnData.repsoneContent.storage + '件',
                    icon: 'none',
                    duration: 1500//持续的时间
                });
                return false;
            }

            var goods_purchase_limit = returnData.repsoneContent.goods_purchase_limit;
            var activity_goods_purchase_limit = returnData.repsoneContent.activity_goods_purchase_limit;

            if (goods_purchase_limit < that.data.num + 1) {
              wx.showToast({
                title: '活动商品限购' + activity_goods_purchase_limit 
                + '件, 您已经购买过' + (activity_goods_purchase_limit - goods_purchase_limit) + '件',
                icon: 'none',
                duration: 1500//持续的时间
              });
              return false;
            }

            var num = that.data.num
            num++;

            that.setData({
                num: num
            });

        }

      }
      app.netApply(url, {}, success, that, false, true);
  },

  // 点击减号
  reduce: function(){
    if (this.data.detailList.storage <= 0) {
        wx.showToast({
            title: '商品库存不足',
            icon: 'none',
            duration: 1500//持续的时间
        });
        return false;
    }

    if (this.backData.decrease_button_lock == true) {
      return false;
    }

    this.backData.decrease_button_lock = true;

    var that = this;
    var id = this.data.id;
    var url = this.data.getProductStorageURL+'?data={"goods_id":"'+id+'"}';

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

      that.backData.decrease_button_lock = false;

      var returnData = res.data;
      if (returnData.status == 'success') {
          // 如果库存不足
          if (returnData.repsoneContent.storage <= 0) {
              wx.showToast({
                  title: '商品库存不足',
                  icon: 'none',
                  duration: 1000//持续的时间
              });
              return false;
          }
          var num = that.data.num;
          if( num>1 ){
              num--;
          }
          that.setData({
              num: num,
          });
      }

    }

    app.netApply(url, {}, success, that, false, true);

  },

  //添加到购物车
  addCart : function(e){

    var that = this;
    var num = this.data.num;

    if (num <= 0) {
        wx.showToast({
            title: '库存不足,加入购物车失败',
            icon: 'none',
            duration: 1500//持续的时间
        });
        return false;
    }
    
    var id = this.data.id;
    var url = this.data.addURL+'?data={"goods_id":"'+id+'","qty":"'+num+'"}'

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

      var data = res.data;
      if (data.status == 'success') {
          wx.showToast({
            title: '加入成功',
            icon: 'none',
            duration: 1500//持续的时间
          })
      } else {
          wx.showToast({
              title: data.longMessage,
              icon: 'none',
              duration: 1500//持续的时间
          });
          // 如果确认商品库存不足
          if (data.resultCode == 110016) {
              that.setData({
                  num: 0
              });
          }
      }

      that.setData({showPop:false})

      return false;

    }

    app.netApply(url, {}, success, that, false, true); 

  },

  // 复制
  copy: function(){
    wx.setClipboardData({
      data: this.data.detailList.goods_shape_code,
      success:function(){
        wx.showToast({
          title: '复制成功',
          icon: 'none',
          duration: 1000
        })
      }
    });
  },

  // 跳转到详情页
  toCart: function(){
    wx.switchTab({url:'../cart/cart'})
  },

  // 时间戳格式转换并赋值
  changeTime: function () {
    
    var timeStamp = [];

    for (var i = 0; i < 1; i++) {

      timeStamp.push(this.data.detailList.remaining_time);
      
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
        if (that.data.detailList.remaining_time > 0) {
          that.data.detailList.remaining_time -= 1;
        }
      }

      that.changeTime();
      that.countTime();

    }, 1000)
  },

})
