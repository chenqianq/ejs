var app = getApp();

Page({

	data: {
		params: {},
	},

	// 生命周期
	onLoad: function(params){

		// 这是进入的路径地址及参数
		this.setData({
			params: params
		})
	},
	
	authInfo: function(res){

		if( res.detail.rawData ){

			var userInfo =  JSON.parse(res.detail.rawData);

			app.globalData.userInfo = userInfo;

			// 助力
			if( this.data.params.from == 'friendHelp/friendHelp' ){
				if( this.data.params.code ){
					wx.redirectTo({
						url: '../'+this.data.params.from+'?code='+this.data.params.code
					})
				}else{
					wx.redirectTo({
						url: '../'+this.data.params.from+'?goods_id='+this.data.params.goods_id+'&activity_id='+this.data.params.activity_id
					})
				}
			// 红包
			} else if (this.data.params.from == 'bonuses/bonuses' ){

				if( this.data.params.code ){
					wx.redirectTo({
						url: '../'+this.data.params.from+'?code='+this.data.params.code
					})
				}else{
					wx.redirectTo({
						url: '../'+this.data.params.from+'?envelope_id='+this.data.params.envelope_id+'&order_sn='+this.data.params.order_sn
					})
				}
		    } else if (this.data.params.from == 'game/game') {
			    wx.redirectTo({
					url: '../' + this.data.params.from + '?title=抽奖活动'
			    })
		    }
	    }
	}
})