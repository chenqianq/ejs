//index.js
//获取应用实例
var app = getApp()

Page({
  data: {
    flag: 0,
    URL: app.globalData.baseUrl+"wxapp/checkout/Confirmation/index.html",
    subUrl: app.globalData.baseUrl+"wxapp/checkout/confirmation/process.html",
    header: app.globalData.header,
    invitationUrl: app.globalData.baseUrl + "wxapp/checkout/confirmation/check_invitation_code.html",
   
    id: 0,

    "address":{},
    "productInfos": [],
    "cartTotal": "",
    "order_total":"",
    "total_goods_num": "",
    voucher_msg: '',
    authentication:'',
    isAuthen : 1,
    switchIndex:1,
    invitationCode:'',
    formContinue:0,//表单是否继续提交
    isShow: false,
    trueName:'',
    trueCard: '',
    isError:false,
    hasEnvelope:0,
  },

  onLoad: function(params){
   
    var id = params.id;
    var that = this;
    var url = this.data.URL;
    var authen = (params.authen && params.authen!=undefined ) ? params.authen:1;

    var typeName = "";
    typeName = params.type > 0 ? "自提" : "二加三派送";

    var address={
      "name": params.name,
      "tel": params.tel,
      "typeName":typeName,
      "type": params.type
    }
    
    var invitationCodeValue = (params.invitation && params.invitation !=undefined) ? params.invitation:'';
    var index = (authen>0)?1:2;
   
    this.setData({ 
      address:address,
      invitationCode: invitationCodeValue,
      isAuthen: authen, 
      switchIndex: index,
    });
    
    if( id ){
      this.setData({ id: id })
      url += '?data={"b_wx_common_address_id":"'+id+'"}'
    }
    
    function success(res, that,param) {
      
      if (res.data.status == 'failed') {
        wx.hideToast();
        setTimeout(function () {
          if (!res.data.longMessage ){
            res.data.longMessage = "获取购物车信息失败,请重试"
          }

          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 1400,
          })

          setTimeout(function () {
            wx.switchTab({
              url: '../cart/cart',
            })
          },1500)

        },50)
        return false;
      }

      // 商品列表赋值
      that.setData({
        productInfos:res.data.repsoneContent.product_infos,
        cartTotal: res.data.repsoneContent.cart_total,
        order_total: res.data.repsoneContent.order_total,
        total_goods_num: res.data.repsoneContent.total_goods_num,
        voucher_msg: res.data.repsoneContent.voucher_msg,
        authentication: res.data.repsoneContent.unrealized_authentication,
        hasEnvelope: res.data.repsoneContent.hs_envelope,
        flag:1
      });

    }

    app.netApply(url, {}, success, this); 

  },

  // 关闭身份认证弹窗
  closeBox: function(){
    console.log(111);
    this.setData({
      isShow: false
    })
  },

  // 提交订单
  submitOrder: function(e){
   
    var that = this;
    this.setData({
      invitationCode: e.detail.value.inviteCodeName,
    });
    var invitationCodeValue = that.data.invitationCode;//邀请码
   
    if (((
      this.data.id && this.data.id != 'undefined' && (that.data.address.type == 0)) 
      || (that.data.address.type == 1)) 
      && (that.data.address.name) 
      && that.data.address.name != 'undefined') {
      
      //符合提交表单的条件时，进行邀请码的单独验证
    

      //如果有邀请码，则进行邀请码的验证，然后再继续下去
      function invitationSuccess(res, that) {
    
        if (res.data.status == "failed") {
          that.setData({
            formContinue: 2,
          });
        
          wx.showToast({
            title: '邀请码错误或不存在',
            icon: 'none',
            duration: 2000,
          });
      
          return false;
        }else{
          //判断实名认证的弹窗是否出现
        
          if (that.data.isAuthen==1 && that.data.authentication==0) {
            that.setData({
              isShow: true,
            });
            return false;
          };
          
          that.nomalOrder();
        }
      }

     
      if (invitationCodeValue && invitationCodeValue != undefined) {
        app.netApply(that.data.invitationUrl, { invitation_code: invitationCodeValue }, invitationSuccess, this, '', true);
      }else{
        //判断实名认证的弹窗是否出现
        if (that.data.isAuthen == 1 && that.data.authentication == 0) {
          that.setData({
            isShow: true,
          });
          return false;
        };
        that.nomalOrder();
      }
    
      //如果邀请码错误的话，直接返回
      if (that.data.formContinue == 0){
        return false;
      }
    }else{

      wx.redirectTo({ url: '../consignee/consignee?invitation=' + invitationCodeValue+"&authen="+that.data.isAuthen});

    }
  },
  //实名认证 是
  setSwith(e){
  
    //进行颜色的换绑
    var index = parseInt(e.target.dataset.index);
    if(this.data.isAuthen == 1){
      var newAuthen = 0;
    }else{
      var newAuthen = 1;
    }
    this.setData({
      isAuthen: newAuthen,
      voucherUrl: this.data.voucherUrl + "&nnn=" +'11',
      switchIndex: index,
    });
  
  },
  /**
   * 填写邀请码的
   */
  inputInvitation(e){
  
    this.setData({
      invitationCode: e.detail.value,
  
       });
   
  },
  /**
   * 提交订单且实名认证
   */
  submitOrderWithAuthen(e){
    //进行实名认证的初步错误判断
    var cardName = e.detail.value.cardName;
    var cardNum = e.detail.value.cardNum;
    if(!cardName || !cardNum){
      this.setData({
        isError:true,
      });
      return false;
    };
    if (!(/^[\u4E00-\u9FA5]+$/.test(cardName))){
      this.setData({
        isError: true,
      });
      return false;
    }
    if (!(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(cardNum))) {
      this.setData({
        isError: true,
      });
      return false;
    }

    if (cardNum.length!=18) {
      this.setData({
        isError: true,
      });
      return false;
    }

    this.setData({
      trueName: cardName,
      trueCard: cardNum,
    });
    this.setData({
      isError: false,
    });
    this.nomalOrder();
  },
  /**
   * 提交订单但是不实名认证
   */
  processOrderBtn(e){
    this.nomalOrder();

  },
  /**
   * 通用的提交订单的方法
   */
  nomalOrder:function (){
    
    var url = this.data.subUrl;
    var paramArray = null;
    var that = this;
    if (that.data.address.type == 0) {
      paramArray = {
        'address_id': that.data.id,
        "type": that.data.address.type
      }
    } else {
      paramArray = {
        'name': that.data.address.name,
        'type': that.data.address.type,
        "phone": that.data.address.tel
      };
    }
    //通用的上传的信息
    paramArray.invitation_code = that.data.invitationCode;
    paramArray.true_name = that.data.trueName;
    paramArray.true_card = that.data.trueCard;
    

    function success(res, that) {

      var b_order_sn = res.data.repsoneContent.b_order_sn;

      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {
          // 当resultCode等于110011时，出现实名认证弹窗
          if (res.data.resultCode == "110011") {
            wx.showModal({
              title: '提示',
              content: '根据海关要求, 请完善您的实名认证信息',
              showCancel: true,
              confirmText: '去认证',
              success: function (res) {
                if (res.confirm) {
                  wx.redirectTo({ url: '../certificate/certificate?id=1' })
                }
              }
            });
            return false;
          }
          // 当resultCode等于110009时，直接跳转至支付失败页面
          if (res.data.resultCode == "110009") {
            wx.redirectTo({ url: '../payfail/payfail?id=' + b_order_sn })
            return false;
          }
          // 当resultCode等于110010时，显示地址信息错误
          if (res.data.resultCode == "110010") {
            res.data.longMessage = "地址有误,请重新选择收货人";
            wx.showToast({
              title: res.data.longMessage,
              icon: 'none',
              duration: 1400
            });
            return false;
          }
          //当实名认证有错误时，只要提示出来就好了
          if (res.data.resultCode == "7000003" || res.data.resultCode == "7000004") {
            wx.showToast({
              title: res.data.longMessage,
              icon: 'none',
              duration: 1400
            });
            return false;
          }


          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 1400
          })

          setTimeout(function () {
            wx.switchTab({
              url: '/pages/cart/cart'
            })
          }, 1500)

        }, 50)
        return false;
      }

      wx.redirectTo({ url: '../paysuccess/paysuccess?id=' + b_order_sn });

    }
   
    app.netApply(url, { 'data': JSON.stringify(paramArray) }, success, this, false, true, 'POST'); 
  }


})
