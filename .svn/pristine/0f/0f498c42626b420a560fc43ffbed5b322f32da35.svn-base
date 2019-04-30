var app = getApp();

Page({

  data: {
    params: {},
    flag: 0,
    isEmpty: false,
    isCheck: -1,
    url:  app.globalData.baseUrl+"wxapp/checkout/confirmation/choose_use_voucher.html",
    useURL: app.globalData.baseUrl+"wxapp/checkout/confirmation/use_voucher.html",
    ruleList: [],
    noRuleList: [],
    code: '',
    // 数据
    "valid_voucher": [ ],
    "unvalid_voucher": [ ]
  },

  onLoad: function(params){
    this.data.params = params;
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
        "valid_voucher": res.data.repsoneContent.valid_voucher,
        "unvalid_voucher": res.data.repsoneContent.unvalid_voucher,
        flag: 1
      })

      // 规则展示列表初始化
      for( var i=0; i<that.data.valid_voucher.length; i++ ){
        that.data.ruleList.push(false)
      }

    }

    app.netApply(this.data.url, {}, success, that);

  },

  // 选定优惠券
  toggleCheck: function(e){
    var index = e.currentTarget.id;
    // 当未选择优惠券时或者此处选择的不为当前被选中的
    if (this.data.isCheck == -1 || this.data.isCheck != index ){
      this.setData({
        isCheck: index,
        code: this.data.valid_voucher[index].voucher_code
      });
    } else {
      this.setData({
        isCheck: -1,
        code: ""
      })
    }
  },

  // 提交优惠券信息
  select: function(e){
    var that = this;
    function codeSuccess(res){

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

      wx.redirectTo({
        url: "../checkout/checkout?id=" + that.data.params.id 
        + "&name=" + that.data.params.name + "&tel=" + that.data.params.tel 
        + "&type=" + that.data.params.type+"&invitation="+that.data.params.invitation+"&authen="+that.data.params.authen
      })
      
    }

    if( this.data.code ){
      var useURL = this.data.useURL+'?data={"voucher_code":"'+this.data.code+'"}'
      app.netApply(useURL, {}, codeSuccess, this)
    }else{
      var useURL = this.data.useURL + '?data={"voucher_code":"-1"}';
      app.netApply(useURL, {}, codeSuccess, this)
    }

  },

  // 点击显示规则
  toggleRule: function(e){

    var val = "ruleList";
    var index = e.currentTarget.id;

    if (e.target.dataset.type == "ok_click" ){
      val = "ruleList"
    } else {
      val = "noRuleList"
    }

    this.data[val][index] = !this.data[val][index];
    this.setData({
      [val]: this.data[val]
    })

  }


})