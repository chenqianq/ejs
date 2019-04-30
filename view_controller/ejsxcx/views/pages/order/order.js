//index.js
var app = getApp()

Page({
  data: {
    flag: 0,
    URL: app.globalData.baseUrl+"wxapp/user/order/orderDetail.html",
    header: app.globalData.header,

    // 接口数据
    "order_sn": '',
    "order_state": '',
    "order_amount": '',
    "shipping_name": '',
    "shipping_phone": '',
    "shipping_address": '',

    "goods_info": [
      {
        "goods_image": "",
        "goods_name": "",
        "goods_price": "",
        "goods_num": ""
      }
    ],
    invitation_code:'',
  },

  onLoad: function(param){

    var that = this;
    var id = param.id;

    if( id ){

      var url = this.data.URL + '?data={"order_sn":"' + id + '"}';

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
          order_sn: res.data.repsoneContent.order_sn,
          order_state: res.data.repsoneContent.order_state,
          order_amount: res.data.repsoneContent.order_amount,
          shipping_name: res.data.repsoneContent.shipping_name,
          shipping_phone: res.data.repsoneContent.shipping_phone,
          shipping_address: res.data.repsoneContent.shipping_address,
          is_self_deliver: res.data.repsoneContent.is_self_deliver,
          goods_info: res.data.repsoneContent.goods_info,
          invitation_code: res.data.repsoneContent.invitation_code,
          flag: 1
        });

      }

      app.netApply(url, {}, success, that, param); 

    }
  }
})
