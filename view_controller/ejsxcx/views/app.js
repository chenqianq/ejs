
//app.js
App({
  session_id : false,
  openid: false,

  connect_times : 0,

	// 全局的属性
	globalData: {
		userInfo: null,
    // baseUrl: "https://wx.xinritao.com/",
    baseUrl: "http://b.drpthi.com/",
		header: {
			'content-type': 'json'
		}
	},

  onLaunch: function () {

    // 把用户信息存入 globalData
    var that = this;
    wx.getSetting({
      success: function (res) {
        // 判断是否获取过授权信息
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              that.globalData.userInfo = res.userInfo;
            }
          })
        }
      }
    })
  },

  wxLogin:function() {
    var _this = this;
    return new Promise(function (resolve, reject) {
      // 调用授权接口
      wx.login({
        "timeout": 10000,
        "success": function (res) {
          var url = _this.globalData.baseUrl + 'wxapp/user/login/getWxUserLogin.html?data={"code":"' + res.code + '"}';
          wx.request({
            url: url,
            data: {},
            header: {
              'content-type': 'json',
            },
            success: function (res) {
              var get_session_id = res.data.repsoneContent.session_id;
              var get_openid = res.data.repsoneContent.openid;
              _this.session_id = get_session_id;
              _this.openid = get_openid;
              _this.globalData.header = {
                'Cookie': 'PHPSESSID=' + get_session_id,
                'content-type': 'json',
              }
              resolve(get_session_id);
            },
            error: function() {
              reject('系统异常,请重试');
            }
          })
        }
      });
    });
  },

  setSessionId : function(url, data, successFunc, that, param, notShowLoading) {

    var _this = this;
    param = param?param:false;
    notShowLoading = notShowLoading?true:false;
    _this.connect_times = _this.connect_times + 1;

    if (_this.connect_times > 3){
      wx.showToast({
        title: '网络错误请重试',
        icon: 'none',
        duration: 2000,
      })
    }

    this.wxLogin().then((res)=> {
      if (_this.session_id) {
        _this.netApply(url, data, successFunc, that, param, notShowLoading);
      }

    }).catch((res)=> {
      console.log(res);
    })

  },

  /**
  * 全局网络访问方法
  * @param  {[type]}  url         url
  * @param  {[type]}  data        数据
  * @param  {[type]}  successFunc 成功执行函数
  * @param  {[type]}  that        当前页面对象
  * @param  {Object} param       额外参数
  * @return {[type]}              [description]
  */
  netApply: function (url, data, successFunc, that, param, notShowLoading, method){
    param = param ? param : false;
    notShowLoading = notShowLoading ? true : false;
    method = method ? method : 'GET';
    if (!notShowLoading) {
      wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 10000,
        mask: true
      });
    }

    var _this = this;
    if (this.session_id) {

      var header = {
        'Cookie': 'PHPSESSID=' + this.session_id,
        'content-type': 'json',
      }

      // 图片上传用的 header
      var header1 = {
        'Cookie': 'PHPSESSID=' + this.session_id,
        'content-type': 'application/x-www-form-urlencoded',
      }

      if( method === 'POST' ){
        header = header1;
      }

      wx.request({
        url: url,
        data: data,
        method: method,
        header: header,
        success: function (respon) {
          if (respon.data.resultCode == '110015') {
            _this.setSessionId(url, data, successFunc, that, param);
            return false;
          }

          // 载入页面内容
          if (!param) {
            successFunc(respon, that);
          } else {
            successFunc(respon, that, param);
          }

          // 关闭加载层
          if (!notShowLoading) {
            wx.hideToast();
          }

        },
        error: function (respon) {
          console.log(respon);
        }
      })
      return false;
    }
    this.setSessionId(url, data, successFunc, that, param);
  },

  /**
  * 全局错误上传
  */
  onError: function (err) {
    var _this = this;
    console.log('上报错误啦！');
    wx.request({
      url: _this.globalData.baseUrl + 'wxapp/filter/auth/get_wx_app_error.html',
      method: "POST",
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      data: { err }
    })
  },


  /**
  *  创建时间
  *  @param timeStamp  毫秒数（数值型）
  */
  creatTimer: function (timeStamp) {
    // 获取天时分秒
    var D = Math.floor(timeStamp / 86400);
    var H0 = Math.floor(timeStamp % 86400 / 3600);
    var M0 = Math.floor(timeStamp % 86400 % 3600 / 60);
    var S0 = Math.floor(timeStamp % 60);
    // 补零
    var H = H0 < 10 ? "0" + H0 : H0;
    var M = M0 < 10 ? "0" + M0 : M0;
    var S = S0 < 10 ? "0" + S0 : S0;

    return {
      "d": D,
      "h": H,
      "m": M,
      "s": S
    }
  },

  /**
  *  截取小数区位函数
  *  @param i 被截取的小数,
  *  @param index 保留下的小数位数
  */
  trunc2: function (i, index) {
    var p = Math.pow(10, index);
    var result = Math.floor(i * p) / p;
    return result;
  },

  /**
  *  比较小数数值的大小
  *  @param a 数值1
  *  @param b 数值2 
  *  @param c 需要对比到的小数点位数，默认为对比整个数值
  */
  compareNum: function(a,b,c) {

    if( a == b ){
      return "数值相等";
    }
    
    if( c ){
      if (this.trunc2(a, c) == this.trunc2(b, c)){
        return "数值相等";
      } else {
        return this.trunc2(a, c) > this.trunc2(b, c) ? a : b;
      }
    }
    
    return parseFloat(a) > parseFloat(b) ? a : b;
    
  },

  /**
  *  获取字符串指定位置的字符
  *  @param str 传入的字符
  *  @param num 位置
  */
  strCharAt: function (str,num) {
    if (str.constructor != String ){
      return "传入的对象非字符串";
    }
    return str.charAt(num);
  },
 
  /**
  *  提取字符串中介于两个指定下标之间的字符
  *  @param str 传入的字符
  *  @param start 开始位置
  *  @param end 结束位置
  */
  strSubstring: function (str, start, end) {
    if (str.constructor != String) {
      return "传入的对象非字符串";
    }
    return str.substring(start, end)
  },

  /**
  *  提取数组中介于两个指定下标之间的字符
  *  @param arr 传入的数组
  *  @param start 需要开始位置
  *  @param end 结束位置
  */
  arrSlice: function (arr, start, end) {
    if (arr.constructor != Array) {
      return "传入的对象非数组";
    }
    return arr.slice(start, end)
  },

  /**
  * 字母大小写切换
  * @param str 要处理的字符串
  * @param type 1:首字母大写 2：首页母小写 3：大小写转换 4：全部大写 5：全部小写
  */
  strChangeCase: function (str, type) {
    function ToggleCase(str) {
      var itemText = ""
      str.split("").forEach(
        function (item) {
          if (/^([a-z]+)/.test(item)) {
            itemText += item.toUpperCase();
          }
          else if (/^([A-Z]+)/.test(item)) {
            itemText += item.toLowerCase();
          }
          else {
            itemText += item;
          }
        });
      return itemText;
    }

    switch (type) {
      case 1:
        return str.replace(/^(\w)(\w+)/, function (v, v1, v2) {
          return v1.toUpperCase() + v2.toLowerCase();
        });
      case 2:
        return str.replace(/^(\w)(\w+)/, function (v, v1, v2) {
          return v1.toLowerCase() + v2.toUpperCase();
        });
      case 3:
        return ToggleCase(str);
      case 4:
        return str.toUpperCase();
      case 5:
        return str.toLowerCase();
      default:
        return str;
    }
  },

  /**
  * 去除字符串空格 
  * @param str 要处理的字符串
  * @param type 1：所有空格 2：前后空格 3：前空格 4：后空格
  */
  strTrim: function (str, type) {
    switch (type) {
      case 1: return str.replace(/\s+/g, "");
      case 2: return str.replace(/(^\s*)|(\s*$)/g, "");
      case 3: return str.replace(/(^\s*)/g, "");
      case 4: return str.replace(/(\s*$)/g, "");
      default: return str;
    }
  },
  
  /**
  * 数组去重
  * 用的是Array的from方法
  * @param arr 数组 set为es6的去重
  */
  removeRepeatArray: function (arr) {
    return Array.from(new Set(arr))
  },

  /**
  * 获取数组的最大值，最小值，只针对数字类型的数组
  * @param arr 数组
  * @param type 0：最小值，1：最大值
  */
  compareArr: function (arr, type) {
    if (type == 1) {
      return Math.max.apply(null, arr);
    } else {
      return Math.min.apply(null, arr);
    }
  },

  /**
  * 简单数组排序，针对数字数组
  * @param type 1：降序，0：升序
  */
  sortArr: function (arr, type) {
      if (type == 1) {
        //降序
        arr.sort(function (a, b) {
          return b - a;
        });
      } else {
        arr.sort(function (a, b) {
          return a - b;
        });
      }
      return arr;
  }


})
