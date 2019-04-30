// 获取实例
var app = getApp();
// 对有需要进行表单验证的页面单独引用
import WxValidate from '../../utils/WxValidate.js';
Page({
  // 设置验证数据，需要放在page下
  initValidate() {
    const rules = {
      shipping_name: {
        required: true
      },
      shipping_phone: {
        required: true,
        tel: true
      },
      pro_city_area: {
        required: true
      },
      shipping_address: {
        required: true
      },
      shipping_detail_address: {
        required: true
      }
    }

    const messages = {
      shipping_name: {
        required: '请输入姓名'
      },
      shipping_phone: {
        required: '请输入手机号',
        tel: "请输入正确的手机号"
      },
      pro_city_area: {
        required: "请选择省市区"
      },
      shipping_address: {
        required: "请选择院校/社区/商城"
      },
      shipping_detail_address: {
        required: "请填写详细地址"
      }
    }

    // 创建实例对象
    this.WxValidate = new WxValidate(rules, messages)

  },
  data: {
    // 页面控制变量
    flag: 0,
    wx_code: '18850422484',
    scrollMt : false,
    userHeight : 0,
    showProvince: false,
    showDetail: false,
    showAddressType: false,
    addressType: ['二加三配送', '自提'],
    addressTypeName:'二加三配送',
    addressTypeIndex:0,
    provinceIndex: 0,
    detailIndex: 0,
    // 需要提交的信息
    name: '',
    tel: '',
    province: '',
    area: '',
    detail: '',
    is_usual: 0,
    invitationCode:'',
    authen:'',
    header: app.globalData.header,
    URL: app.globalData.baseUrl+"wxapp/checkout/Confirmation/getDistrubutionAddress.html",
    addUrl: app.globalData.baseUrl+"wxapp/checkout/Confirmation/saveUserDistrubutionAddress.html",
    delUrl: app.globalData.baseUrl+"wxapp/checkout/Confirmation/deleteUserCommonAddress.html",

    // 接口数据
    "select_address": [
      {
        "address": "",
        "detail": []
      }
    ],
    "common_address": [
      {
        "b_wx_common_address_id": "",
        "shipping_name": "",
        "shipping_phone": "",
        "shipping_address_info": "",
        "is_delete": ""
      }
    ],
  },

  // 初次加载
  onLoad: function (option) {
  
    this.setData({
      authen: option.authen,
      invitationCode: option.invitation
    })
  
    this.initValidate();

  },

  // 加载
  onShow: function () {
    // 加载
    var that = this;
    var url = this.data.URL;

    function success (res, that) {

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
        select_address: res.data.repsoneContent.select_address,
        common_address: res.data.repsoneContent.common_address,
        flag: 1
      })

      //创建节点选择器
      var query1 = wx.createSelectorQuery();
      var that1 = that;
      query1.select('#FIXH').boundingClientRect();
      query1.exec(function (res) {
        //res就是 所有id为userINFO的元素的信息 的数组
        that1.setData({ userHeight: res[0].height });
      }); 

    }

    app.netApply(url, {}, success, that); 

  },

  //滚动监听
  onPageScroll: function (e) {
    var distance = this.data.userHeight;
    if (e.scrollTop >= distance) {
      this.setData({ scrollMt: true });
    } else {
      this.setData({ scrollMt: false });
    }
  },
  
  // 展示派送方式的弹窗
  showAddressType: function(){
    this.setData({
      showAddressType:!this.data.showAddressType
    })
  },

  // 选择派送方式
  selectAddressType: function (e) {

    var index = e.currentTarget.dataset.index;

    this.setData({
      addressTypeIndex: index,
      addressTypeName: this.data.addressType[index]
    })
  
    this.closeAll();
    
  },

  // 是否保存到常用收货人
  selectUs : function(){
    var is_usual = Number(!this.data.is_usual);
    this.setData({ is_usual: is_usual } )
  },

  // 展示一级地址弹窗
  showProvince: function(){
    this.setData({
      showProvince: !this.data.showProvince
    })
  },

  // 展示二级地址弹窗
  showDetail: function(){
    this.setData({
      showDetail: !this.data.showDetail
    })
  },

  // 选择一级地址
  selectAdd: function(e){

    var index = e.currentTarget.dataset.index;
    this.setData({
      provinceIndex: index,
      province: this.data.select_address[index].address
    })
    this.closeAll();
  },

  // 选择二级地址
  selectDetail: function(e){

    var index = e.currentTarget.dataset.index;
    var proIndex = this.data.provinceIndex;

    this.setData({
      detailIndex: index,
      area: this.data.select_address[proIndex].detail[index]
    })

    this.closeAll();
    
  },

  // 关闭所有弹窗
  closeAll: function(){
    this.setData({
      showProvince: false,
      showDetail: false,
      showAddressType:false
    })
  },

  // 删除收货人
  delete: function(e){
    
    var that = this;
    var id = e.currentTarget.id;
    var param = { "id": id };

    var url = this.data.delUrl+'?data={"b_wx_common_address_id":"'+id+'"}';

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

      wx.showToast({
        title: '删除成功',
        icon: 'none',
        duration: 1000
      })

      var finalArr = that.data.common_address;

      for( var i = 0; i < finalArr.length; i++  ){
        if (finalArr[i]["b_wx_common_address_id"] == param.id ){
          finalArr.splice( i,1 );
        }
      }

      that.setData({ 
        common_address : finalArr
      });

    }

    app.netApply(url, {}, success, that, param, true); 

  },

  // 提交选择收货人表单
  addressform: function (e) {
    
    var that = this;
    const params = e.detail.value;
    // 表单数据验证处理
    if (!this.WxValidate.checkForm(params) && this.data.addressTypeIndex  == 0 ) {

      const error = this.WxValidate.errorList[0];
      wx.showToast({
        title: error.msg,
        icon: 'none',
        duration: 2000
      })
      return false;

    } else {

      const error = this.WxValidate.errorList;
      for( var i = 0; i<error.length; i++ ){
        if (error[i]["param"] == "shipping_name" || error[i]["param"] == "shipping_phone" ){
          var indexOf = i;
          wx.showToast({
            title: error[indexOf].msg,
            icon: 'none',
            duration: 2000
          })
          return false;
        }
      }

    }

    var url = this.data.addUrl;
    delete params.addressTypeName;
     
    if (this.data.addressTypeIndex == 0){
      // 二加三配送
      params.is_common = this.data.is_usual;
      var dataParam = params;

      function success(res, that, dataParam){

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

        // 跳转并将 id, name, tel, address 传给结算页
        var id = res.data.repsoneContent.b_wx_common_address_id;

        if (parseInt(id) < 0 || isNaN(parseInt(id))) {
          wx.showToast({
            title: '未获取正确的地址id,请重试',
            icon: 'none',
            duration: 1000
          });
          return false;
        }

        var url = "../checkout/checkout?id=" + id + "&name=" + dataParam.shipping_name + "&type=" + that.data.addressTypeIndex+"&invitation="+that.data.invitationCode+"&authen="+that.data.authen;
        wx.redirectTo({ url : url });

      }

      app.netApply(url, {'data': JSON.stringify(params)}, success, that, dataParam, true, 'POST');

    } else {
      // 自提

      
      var aBurl = "../checkout/checkout?" + "name=" + params.shipping_name 
        + "&tel=" + params.shipping_phone + "&type=" + this.data.addressTypeIndex + "&invitation=" + that.data.invitationCode+"&authen=" + that.data.authen;

      wx.redirectTo({ url: aBurl });

    }

  },

  // 复制
  copy: function(){
    wx.setClipboardData({
      data: this.data.wx_code,
      success:function(){
        wx.showToast({
          title: '复制成功',
          icon: 'none',
          duration: 1000
        })
      }
    });
  }

})