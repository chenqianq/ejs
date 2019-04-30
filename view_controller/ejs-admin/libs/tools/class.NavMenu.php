<?php
class NavMenu {
    public static $menu = array(
        'top' => array(
            0 => array(
                'args' 	=> 'dashboard',
                'text' 	=> '控制台'),
            1 => array(
                'args'  => 'account',
                'text'  => '我的账号'
            ),
            // 2 => array(
            //     'args'  => 'merchant',
            //     'text'  => '商户信息'
            // ),
            3 => array(
                'args'  => 'market',
                'text'  => '小程序商城'
            ),
            // 4 => array(
            //     'args'  => 'order_apply',
            //     'text'  => '订单与申报'
            // ),
	        5 => array(
                 'args'  => 'crm',
                 'text'  => 'CRM'
	        ),
            6 => array(
                'args'  => 'setting',
                'text'  => '平台设置'
            ),
        ),
        //left中同一级菜单下（parent_nav相同）的nav命名不能相同，要具有唯一性，用来获取三级菜单，parent_nav为top中的args
        'left' =>array(
            0 => array(
                [
                    'nav' => 'dashboard1',
                    'parent_nav' => 'dashboard',
                    'text' => '常用操作',
                    'list' => array(
                        array('args'=>'welcome,dashboard,dashboard',			'text'=>'欢迎页面'),
                    )
                ]
            ),
            1 => array(
                [
                    'nav' => 'account',
                    'parent_nav' => 'account',
                    'text' => '我的账号',
                    'list' => array(
                        array('args'=>'admin_change_password,admin_limit,setting',			'text'=>'修改密码'),
                        array('args'=>'admin_bind_mobile,admin_limit,setting',			'text'=>'验证手机号'),
                    )
                ]
            ),
            // 2 => array(
            //     [
            //         'nav' => 'account_info',
            //         'parent_nav' => 'merchant',
            //         'text' => '账号信息',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'账户信息'),
            //         )
            //     ],
            //     [
            //         'nav' => 'xcx_info',
            //         'parent_nav' => 'account',
            //         'text' => '小程序信息',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'商户信息'),
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'绑定小程序信息'),
            //         )
            //     ],
            //     [
            //         'nav' => 'merchant_setting',
            //         'parent_nav' => 'account',
            //         'text' => '基础配置',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'基础配置'),
            //         )
            //     ],
            // ),
            3 => array(
            //     [
            //         'nav' => 'order',
            //         'parent_nav' => 'market',
            //         'text' => '订单',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'订单列表'),
            //         )
            //     ],
                [
                    'nav' => 'goods',
                    'parent_nav' => 'market',
                    'text' => '商品',
                    'list' => array(
                        array('args'=>'goods_list,goods,goods',			'text'=>'商品列表'),
                        array('args'=>'admin_list,admin_limit,setting',			'text'=>'分类列表'),
                    )
                ],
            //     [
            //         'nav' => 'activity',
            //         'parent_nav' => 'market',
            //         'text' => '活动',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'活动列表'),
            //         )
            //     ],
            //     [
            //         'nav' => 'custom_page',
            //         'parent_nav' => 'market',
            //         'text' => '商城装修',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'模板管理'),
            //         )
            //     ],
            //     [
            //         'nav' => 'member',
            //         'parent_nav' => 'market',
            //         'text' => '会员',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'会员列表'),
            //         )
            //     ],
            //     [
            //         'nav' => 'pre_sale',
            //         'parent_nav' => 'market',
            //         'text' => '会员',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'待审核列表'),
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'全部售后列表'),
            //         )
            //     ],
            // ),
            // 4 => array(
            //     [
            //         'nav' => 'order_apply',
            //         'parent_nav' => 'order_apply',
            //         'text' => '订单与申报',
            //         'list' => array(
            //             array('args'=>'admin_list,admin_limit,setting',			'text'=>'小程序订单'),
            //             array('args'=>'group_list,admin_limit,setting',			'text'=>'订单申报'),
            //         )
            //     ]
            ),
            5 => array(
                 [
                     'nav' => 'crm1',
                     'parent_nav' => 'crm',
                     'text' => '客户管理',
                     'list' => array(
	                     array('args'=>'client_list,crm,crm',			'text'=>'客户管理'),
                         array('args'=>'merchant_list,crm,crm',			'text'=>'商户账号管理'),
                     )
                 ],
	            [
		            'nav' => 'crm1',
		            'parent_nav' => 'crm',
		            'text' => '商务管理',
		            'list' => array(
			       
			            array('args'=>'business_manager_list,crm,crm',			'text'=>'商务经理管理'),
		            )
	            ]
            ),
            6 => array(
                [
                    'nav' => 'setting',
                    'parent_nav' => 'setting',
                    'text' => '平台用户管理',
                    'list' => array(
                        array('args'=>'admin_list,admin_limit,setting',			'text'=>'管理员管理'),
                        array('args'=>'group_list,admin_limit,setting',			'text'=>'用户组管理'),
                        array('args'=>'index,permission,setting',			        'text'=>'权限列表'),
                    )
                ],
                [
                    'nav' => 'setting',
                    'parent_nav' => 'setting',
                    'text' => '平台设置',
                    'list' => array(
                        array('args'=>'setting_list,setting,setting',			'text'=>'平台设置'),
                    )
                ],
            ),

        ),
    );

}
