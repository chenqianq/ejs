//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    flag : 0,
    URL: app.globalData.baseUrl+'wxapp/product/product/index.html',
    addURL: app.globalData.baseUrl+"wxapp/checkout/cart/addToCart.html",
    header: app.globalData.header,

    // 页面初始数据
    order_name: "默认排序",
    category_name: "分类",
    brand_name: "品牌",
    con_name: "筛选",
    search_value: "",

    // 页面控制数据
    showShade: false, // 遮罩层
    showOrderFilter: false, // 排序窗口
    showCatFilter: false, // 类目窗口
    showBrandFilter: false, // 品牌窗口
    showConFilter:false, // 价格库存窗口
    catIndex: 0, // 一级分类 active 标识
    subCatIndex: 0, // 二级分类 active 标识
    brandIndex: 0, // 一级品牌 active 标识
    subBrandIndex: 0, // 二级品牌 active 标识
    numIndex: 0, // 库存 active 标识
    priIndex: 0, // 价格 active 标识
    inputValue: "",  // 搜索输入框文字 
    isRequestError: false, //是否请求错误
    
    // 接口数据
    goodsList: [
    	{
        "goods_id": "",
        "goods_name": "",
    		"goods_image_url": "",
    		"final_price": ""
    	}
    ],
    searchList: {
      product_order_by_list:[
        {
          'order_by_name': '',
          'order_by_code': ''
        }
      ],
      product_class_list:[
        {
          "goods_class_id": "",
          "goods_class_name": "",
          "goods_class_level": "",
          "goods_child_class": [
            {
              "goods_class_id": "",
              "goods_class_name": "",
              "goods_class_level": ""
            }
          ]
        }
      ],
      product_brand_list:[
        {
          "brand_initial": "",
          "brand_initial_data":[
            {
              "brand_id": "",
              "brand_name": ""
            }
          ]
        }
      ],
      product_storage_model_list:[
        {
          "model_value": "",
          "model_name": ""
        }
      ],
      product_price_model_list:[
        {
          "model_value": "",
          "model_name": ""
        }
      ]
    }
  },

  backData: {
    is_end: false,
    filter: {
      "data": {
        "page": 1,
        "search_value": "",
        "order_by": "",
        "brand_id": "",
        "class_id": "",
        "class_level": "",
        "storage_model": "",
        "price_model": "",
      }
    },
  },

  // 初次加载
  onLoad: function () {

    var that = this;

    function success(res, that) {

      if (res.data.status == "failed") {
        // 初始加载请求失败时，第二次进入时再次进行请求
        that.setData({
          isRequestError: true
        })
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

      // 商品列表赋值
      that.setData({
        goodsList: res.data.repsoneContent.goods_box_info.goods_list,
        searchList: res.data.repsoneContent.search_box_info,
        flag: 1
      })

    }

    app.netApply(this.data.URL, {}, success, that);

  },

  // 初始加载错误后切换标签时二次请求数据
  onShow: function () {

    var that = this;

    if (that.data.isRequestError == true) {
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

        // 商品列表赋值,及二次请求判断
        that.setData({
          goodsList: res.data.repsoneContent.goods_box_info.goods_list,
          searchList: res.data.repsoneContent.search_box_info,
          flag: 1,
          isRequestError: false
        })

      }

      app.netApply(this.data.URL, {}, success, that);

    }

  },

  // 加入购物车
  addCart: function(e){

    var that = this;
    var id = e.currentTarget.id
    var url = this.data.addURL + '?data={"goods_id":"' + id + '"}';

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

      wx.hideToast();
      setTimeout(function () {
        wx.showToast({
          title: '加入成功',
          icon: 'none',
          duration: 2000//持续的时间
        })
      },50)
      
    }

    app.netApply(url, {}, success, that, false, true);

  },

  // catIndex, 分类二级类目
  changeCatIndex: function(e){
    this.setData({
      catIndex: e.currentTarget.dataset.index
    })
  },

  // 品牌二级类目
  changeBrandIndex: function(e){
    this.setData({
      brandIndex: e.currentTarget.dataset.index
    })
  },

  // 库存筛选 numIndex
  getNumActive: function(e){
    this.setData({
      numIndex: e.currentTarget.dataset.index
    })
  },

  // 价格筛选 priIndex
  getPriActive: function(e){
    this.setData({
      priIndex: e.currentTarget.dataset.index
    })
    
    
  },

  // 重置 库存和价格 筛选
  reset: function(){
    this.setData({
      numIndex: 0,
      priIndex: 0
    })
  },

  // 获取输入的值
  getSearchValue: function(e){

    this.setData({
      inputValue: e.detail.value
    })
    this.setData({
      search_value: e.detail.value
    })
    this.backData.is_end=false;
  },

  // 排序弹窗
  showOrder: function(){
    if (this.data.showOrderFilter ){
      this.closeShade();
      return;
    }
    this.closeShade();
    this.setData({
      showOrderFilter: !this.data.showOrderFilter,
      showShade: !this.data.showShade
    })
    this.backData.is_end=false;
    
  },

  // 类目弹窗
  showCat: function(){
    if (this.data.showCatFilter) {
      this.closeShade();
      return;
    }
    this.closeShade();
    this.setData({
      showCatFilter: !this.data.showCatFilter,
      showShade: !this.data.showShade
    })
    this.backData.is_end=false;
    
  },

  // 品牌弹窗
  showBrand:function(){
    if (this.data.showBrandFilter) {
      this.closeShade();
      return;
    }
    this.closeShade();
    this.setData({
      showBrandFilter: !this.data.showBrandFilter,
      showShade: !this.data.showShade
    })
    this.backData.is_end=false;
    
  },

  // 价格,库存弹窗
  showCon: function(){
    if (this.data.showConFilter) {
      this.closeShade();
      return;
    }
    this.closeShade();
    this.setData({
      showConFilter: !this.data.showConFilter,
      showShade: !this.data.showShade
    })
    this.backData.is_end=false;
    
  },

  // 点击遮罩关闭弹窗
  closeShade: function(){
    this.setData({
      showOrderFilter: false,
      showCatFilter: false,
      showBrandFilter: false,
      showConFilter: false,
      showShade: false
    })
  },

  // 点击选中
  selectFilter: function(e){

    wx.pageScrollTo({
        scrollTop: 0
    })
    
    // 根据e的情况赋值
    var condition = e.currentTarget.id;
    // 搜索
    if( condition == 'search' ){
      this.backData.filter.data.search_value = this.data.search_value;
    }
    // 排序
    if( condition == 'order' ){

      this.setData({
        order_name: e.currentTarget.dataset.name,
      })
      this.backData.filter.data.order_by = e.currentTarget.dataset.order;
    }
    // 分类
    if( condition == 'category' ){

      this.setData({
        // 标记 active
        'subCatIndex': e.currentTarget.dataset.index,
        // 标记 title
        'category_name': e.currentTarget.dataset.name,
        // 后台传值
      });
      this.backData.filter.data.class_level = e.currentTarget.dataset.level;
      this.backData.filter.data.class_id = e.currentTarget.dataset.brand;
    }
    // 品牌
    if( condition == 'brand' ){
      this.setData({
        'subBrandIndex' : e.currentTarget.dataset.index,
        'brand_name' : e.currentTarget.dataset.name,
      });
      this.backData.filter.data.brand_id = e.currentTarget.dataset.brand;
    }
    // 内容
    if( condition == 'content' ){
      this.backData.filter.data.storage_model = this.data.numIndex;
      this.backData.filter.data.price_model = this.data.priIndex;
    }

    // 重置页数为第一页
    this.backData.filter.data.page = 1;

    // 关闭弹窗
    this.closeShade();

    var that = this;

    var url = this.data.URL;

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

      var goodsList = res.data.repsoneContent.goods_box_info.goods_list;
      if( goodsList == false ){
        that.setData({
          goodsList: [],
        });
        that.backData.is_end=true;
      } else {
        // 商品列表赋值
        that.setData({
          goodsList: goodsList,
          searchList: res.data.repsoneContent.search_box_info,
        });
      }
      that.setData({flag: 1});
    }

    // 发送请求: 
    app.netApply(url, this.backData.filter, success, that);

  },

  // 下拉加载更多
  onReachBottom: function(){
    if( !this.backData.is_end ){

      var that = this;

      var arrNow = this.data.goodsList;

      this.backData.filter.data.page = this.backData.filter.data.page + 1;

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

          var arrNew = res.data.repsoneContent.goods_box_info.goods_list;
          if( arrNew == false){
            that.backData.is_end=true;
          } else {
            var goodsList = paramData.arrNow.concat(arrNew);
            that.setData({goodsList:goodsList});
          }

          that.setData({flag: 1});
      }

      app.netApply(this.data.URL, this.backData.filter, success, that, paramData);

    }
  },

  // 清除输入框
  clearText : function(){
    this.setData({
      inputValue : "",
      search_value: ""
    })
  },

  // 下拉刷新
  onPullDownRefresh: function () {

    var that = this;
    var url = this.data.URL;

    that.backData.filter.data.page = 1;

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

      // 当请求为成功时，停止下来刷新
      wx.stopPullDownRefresh();

      var goodsList = res.data.repsoneContent.goods_box_info.goods_list;

      if (goodsList == false) {
        that.setData({
          goodsList: [],
        });
        that.backData.is_end = true;
      } else {
        // 商品列表赋值
        that.setData({
          goodsList: goodsList,
          searchList: res.data.repsoneContent.search_box_info,
        });
      }

      that.setData({ flag: 1 });

    }

    app.netApply(url, that.backData.filter, success, that);
    
  }

})
