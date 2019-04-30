//index.js
//获取应用实例
var app = getApp()
Page({
  
  data: {
  	// 页面控制数据
    flag : 0,
  	isEdit: false,
    isEmpty: true,
    listData : [],

    url: app.globalData.baseUrl+'wxapp/checkout/cart/index.html',
    changeNumUrl: app.globalData.baseUrl+'wxapp/checkout/cart/updateCartQty.html',
    deleteUrl: app.globalData.baseUrl+'wxapp/checkout/cart/removeCart.html',
    checkUrl: app.globalData.baseUrl+'wxapp/checkout/cart/selectCart.html',
    header: app.globalData.header,
    checkList: [],
    deleteCheckBoxList: [],
    checkAll: 0,
    tmpCheckAll: 0,

  	// 接口数据
    "cart_box_info":[
    	{
        "cart_sn": "",
        "cart_id": "",
	    	"goods_image": "",
	    	"goods_name": "",
	    	"goods_num": "",
	    	"final_price": ""
    	}
    ],
    "cartTotal": "",
    "cartQty" : ""
  },

  backData : {
      add_button_lock : [],
      decrease_button_lock : [],
  },

  // 发送请求
  onShow: function(){
   
    this.setData({
      checkList: [],
      flag: 0
    })

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
        cart_box_info: res.data.repsoneContent.cart_box_info,
        cartTotal: res.data.repsoneContent.cartTotal,
        cartQty: res.data.repsoneContent.cartQty,
        checkAll: res.data.repsoneContent.cart_select_all,
        isEdit: false,
      })

      // 设置初始值
      for (var i = 0; i < that.data.cart_box_info.length; i++) {
        if (that.data.cart_box_info[i].cart_sn > 0) {
          that.data.checkList.push(1);
        } else {
          that.data.checkList.push(0);
        }
      }

      that.setData({
        checkList: that.data.checkList
      })

      if (that.data.cart_box_info.length < 1) {
        that.setData({
          isEmpty: true
        })
      } else {
        that.setData({
          isEmpty: false
        })
      }

      that.setData({flag: 1});

    }

    app.netApply(this.data.url, {}, success, this);

  },

  // 编辑
  edit: function(){
    //　点击编辑
    if (this.data.isEdit == false) {

      for (var i = 0; i < this.data.checkList.length; i++) {
        this.data.deleteCheckBoxList[i] = 0;
      }

      this.data.tmpCheckAll = this.data.checkAll;

      this.setData({
        deleteCheckBoxList: this.data.deleteCheckBoxList,
        isEdit: !this.data.isEdit,
        checkAll: false
      })

    } else {

      this.setData({
        isEdit: !this.data.isEdit,
        checkAll: this.data.tmpCheckAll
      })

    }

  },

  // 增加
  add: function(e){

      var that = this;
      var param = new Object();
      param.index = e.currentTarget.dataset.index;

      if (this.backData.add_button_lock[param.index] == true) {
          return false;
      }
      
      this.backData.add_button_lock[param.index] = true; // 锁定按钮

      var id = e.currentTarget.id;
      var num = this.data.cart_box_info[param.index].goods_num;
      num++;

    var changeNumUrl = this.data.changeNumUrl+'?data={"qty":"'+num+'", "cart_id": "'+id+'"}';

    function success(res, that, param) {

      that.backData.add_button_lock[param.index] = false;  // 解锁按钮
      that.backData.decrease_button_lock[param.index] = false;
      
      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {
          // 当resultCode不存在时
          if (!res.data.resultCode){
            res.data.longMessage = "修改购物车数量失败,请稍后重试";
          }
          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 2000
          })
        }, 50)
        return false;
      }

      var reCheck = "checkList[" + param.index + "]";
      var str = 'cart_box_info[' + param.index + ']';

      that.setData({
        [reCheck] : 1,
        [str]: res.data.repsoneContent.cart_box_info[param.index],
        cartTotal: res.data.repsoneContent.cartTotal,
        cartQty: res.data.repsoneContent.cartQty
      })

    }

    app.netApply(changeNumUrl, {}, success, this, param, true);

  },

  // 减少
  reduce: function(e){

      var that = this;
      var param = new Object();
      param.index = e.currentTarget.dataset.index;

      if (this.backData.decrease_button_lock[param.index]) {
          return false;
      }

      this.backData.decrease_button_lock[param.index] = true;

      var id = e.currentTarget.id;
      var num = this.data.cart_box_info[param.index].goods_num;

      if (num == 1) {
          this.backData.decrease_button_lock[param.index] = true;
          return false;
      } else if (num > 1) {
        num--;
      }


    var changeNumUrl = this.data.changeNumUrl+'?data={"qty":"'+num+'", "cart_id": "'+id+'"}';

    function success(res, that, param) {

      that.backData.decrease_button_lock[param.index] = false;
      that.backData.add_button_lock[param.index] = false;

      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {
          // 当resultCode不存在时
          if (!res.data.resultCode) {
            res.data.longMessage = "修改购物车数量失败,请稍后重试";
          }
          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 2000
          })
        }, 50)
        return false;
      }

      var reCheck = "checkList["+param.index+"]";
      var str = 'cart_box_info['+param.index+']';

      that.setData({
          [reCheck]: 1,
          [str]: res.data.repsoneContent.cart_box_info[param.index],
          cartTotal: res.data.repsoneContent.cartTotal,
          cartQty: res.data.repsoneContent.cartQty
      })

    }

    app.netApply(changeNumUrl, {}, success, this, param, true);

  },

  // checkbox 的值
  checkboxChange: function(e){

    var param = new Object();
    param.index = e.currentTarget.dataset.index;
    param.id = e.currentTarget.id;
    var is_select_all = this.data.checkAll;

    if(this.data.isEdit == true) {
        var str = "checkList["+param.index+"]";
        var val = Number(!this.data.checkList[param.index]);
        this.setData({
            [str]: val,
        })
        return false;
    }

    // 回传服务端
    var checkUrl = this.data.checkUrl+'?data={"cart_id":"' + param.id + '","is_select_all":"'+0+'"}';

    function success(res, that, param) {
      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {
          // 当resultCode不存在时
          if (!res.data.resultCode) {
            res.data.longMessage = "选择购物车商品失败,请稍后重试";
          }
          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 1400
          })
          // 当resultCode等于特定的code时,刷新购物车
          if (res.data.resultCode == '110020' || res.data.resultCode == '110019' ){
            setTimeout(function () {
              wx.reLaunch({
                url: '../cart/cart',
              })
            }, 1500);
          }

        }, 50)
        return false;
      }

      var str = "checkList["+param.index+"]";
      var val = Number(!that.data.checkList[param.index]);
        
      that.setData({
          [str]: val,
          cart_box_info: res.data.repsoneContent.cart_box_info,
          cartTotal: res.data.repsoneContent.cartTotal,
          cartQty: res.data.repsoneContent.cartQty,
          checkAll: res.data.repsoneContent.cart_select_all
      })

    }

    app.netApply(checkUrl, {}, success, this , param, true);
    
  },

  // 编辑状态点击单选函数
  deleteCheckboxChange: function (e) {

    var index = e.currentTarget.dataset.index;
    var id = e.currentTarget.id;
    var val = Number(!this.data.deleteCheckBoxList[index]);

    this.setData({
      ["deleteCheckBoxList[" + index + "]"]: val
    });
    
    var allSelect = true;

    for (var i = 0; i < this.data.deleteCheckBoxList.length; i++) {
      if (this.data.deleteCheckBoxList[i] == 0) {
        allSelect = false;
      }
    }

    this.setData({
      checkAll: allSelect
    });

    return false;

  },

  // 全选
  checkAll: function(e){

    // 如果是编辑勾选删除状态
    if (this.data.isEdit == true) {

      for (var i = 0; i < this.data.checkList.length; i++) {
        this.data.deleteCheckBoxList[i] = (this.data.checkAll == false) ? 1 : 0;
      }

      this.setData({
        deleteCheckBoxList: this.data.deleteCheckBoxList,
        checkAll: !this.data.checkAll
      })

      return false;

    }

    // 回传服务端
    var is_select_all = this.data.checkAll ? 1 : 2;
    var that = this;
    var checkUrl = this.data.checkUrl+'?data={"is_select_all":"'+is_select_all+'"}';
    function success(res, that) {

      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {

          if (res.data.resultCode == '110016') {
            res.data.longMessage = "部分商品库存不足,请删除后全选"
          }

          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 1400
          })

          setTimeout(function () {
            wx.reLaunch({ url: '../cart/cart' });
          }, 1500);

        }, 50)
        return false;
      }

      that.setData({
        cart_box_info: res.data.repsoneContent.cart_box_info,
        cartTotal: res.data.repsoneContent.cartTotal,
        cartQty: res.data.repsoneContent.cartQty
      });

      var val = Number(!that.data.checkAll);
      var temp = [];

      for( var i=0; i<that.data.checkList.length; i++ ){
          temp.push( val );
      }

      that.setData({
          checkAll: val,
          checkList: temp
      })

    }

    app.netApply(checkUrl, {}, success, this, false, true); 

  },

  // 删除
  delete: function(){

    var delarr = [];

    for (var i = 0; i < this.data.deleteCheckBoxList.length; i++) {
      if (this.data.deleteCheckBoxList[i] > 0) {
        delarr.push(this.data.cart_box_info[i].cart_id);
      }
    }

    var cart_id = delarr.toString();
    var that = this;
    var newDate = [];
    var deleteUrl = this.data.deleteUrl+'?data={"cart_id":"'+cart_id+'"}';

    function success(res, that) {

      if (res.data.status == "failed") {
        wx.hideToast();
        setTimeout(function () {

          wx.showToast({
            title: res.data.longMessage,
            icon: 'none',
            duration: 1400
          })

          setTimeout(function () {
            wx.reLaunch({
              url: "/pages/cart/cart"
            })
          }, 1500)

        }, 50)
        return false;
      }

      // 重新赋值
      that.setData({
        flag: 0,
        cart_box_info: res.data.repsoneContent.cart_box_info,
        cartTotal: res.data.repsoneContent.cartTotal
      })

      wx.showToast({
        title: '删除成功',
        icon: 'none',
        duration: 500,
      });

      that.setData({
        isEdit: !that.data.isEdit
      });

      setTimeout(function(){
        wx.reLaunch({
          url: "/pages/cart/cart"
        })
      },500)
		  
    }

    app.netApply(deleteUrl, {}, success, that, false, true); 

  },

  // 提交购物车
  submitCart:function() {
    var that = this;
    var hasItems = false;
    var goodsNum = 0;
    // 设置初始值
    for (var i = 0; i < that.data.cart_box_info.length; i++) {
        if (that.data.checkList[i] > 0) {
          hasItems = true;
          goodsNum += parseInt(that.data.cart_box_info[i]['goods_num']);
        }
    }
    
    if(goodsNum >9){
       wx.showToast({
         title: '亲亲~单笔订单合计购买数量不可超出9件哟~',
         icon: 'none',
         duration: 2000,
       });

    }else if (hasItems == true) {
      wx.navigateTo({
          url: "../checkout/checkout",
      })
    } else {
      wx.showToast({
          title: '尚未选择商品',
          icon: 'none',
          duration: 2000,
      });
    }

  }

})
