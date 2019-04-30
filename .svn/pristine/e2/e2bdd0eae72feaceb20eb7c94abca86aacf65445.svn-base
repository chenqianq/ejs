<?php
class HeaderWidget extends ZcWidget {
	
	private $adminInfo;

    /**
     * @var AdminService
     */
	private $adminService;
	protected $permission;
	
	  public function __construct(){
        $this -> adminService = ServiceFactory::getAdminService();
		
		$this->adminInfo = $this -> adminService -> systemLogin();
		if($this->adminInfo['id'] != 1){
			//权限过滤
		}
		
    }
	
	
	public function render($data = null) {

		$this->getNav('',$top_nav,$left_nav,$left_level3_nav,$map_nav);

		$data = array();
		$data['adminInfo'] 	= $this->adminInfo;

		$data['top_nav'] 	= $top_nav;

		$data['left_nav'] 	= $left_nav;

        $data['left_level3_nav'] 	= $left_level3_nav;

		$data['map_nav'] 	= $map_nav;



		
		return $this->renderFile ( 'common/header', $data );
	}
	
	
	
	/**
	 * 取得后台菜单    ------------------------------------------------------- ///权限跟后台地图未做
	 *
	 * top 数组是顶部菜单 ，left数组是左侧菜单
	 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
	 * @param string $permission
	 * @return
	 */
	protected final function getNav($permission = '',&$top_nav,&$left_nav,&$left_level3_nav,&$map_nav)
    {


        ///////////$permission权限赋值begin

        if ($this->adminInfo['sp'] != 1 && empty($this->permission)) {
            $permission = $this->adminService->getAdminPermissionListByAdminGroupId($this->adminInfo['adminGid']);
            $permission = array_column($permission, 'admin_permission_name');
            $classArray = [];
            foreach ($permission as $value) {

                $classArray[] = $value;
            }
            $this->permission = $classArray;
        }

        //////////权限赋值end

        $arr = NavMenu::$menu;
        $arr = $this->parseMenu($arr);  ///权限过滤


        //管理地图
        $map_nav = $arr['left'];
        unset($map_nav[0]);


        $model_nav = "<a class=\"layui-btn\" id=\"nav__nav_\" href=\"javascript:;\" onclick=\"openItem('_args_');\"><span>_text_</span></a>\n";
        $top_nav = '';

        //顶部菜单
        foreach ($arr['top'] as $k => $v) {
            $v['nav'] = $v['args'];
            $top_nav .= str_ireplace(array('_args_', '_text_', '_nav_'), $v, $model_nav);
        }
        $top_nav = str_ireplace("\n<a class=\"layui-btn\"", "\n<a class=\"layui-btn layui-btn-primary\"", $top_nav);


        //左侧菜单
        $left_nav = $left_level3_nav = '';
        foreach ($arr['left'] as $k => $level2v) {
            $level2Index = 0;
            $displayNone = "";
            if ($left_nav) {
                $displayNone = "style='display: none;'";
            }
            $left_nav .= "<ul class=\"layui-nav layui-nav-tree\" $displayNone>";
            foreach ($level2v as $v) {
                $layuiThis = "";
                $level3Index = 0;
                if ($level2Index == 0) {
                    $layuiThis = "layui-this";
                }
                $left_nav .= "<li class=\"layui-nav-item " . $layuiThis . "\"><a href=\"JavaScript:void(0);\" onclick=\"openLevel2Item('" . $v['nav'] . "');\">" . $v["text"] . "</a></li>";
                $level2Index++;
                $displayNone = "";
                if ($left_level3_nav) {
                    $displayNone = "style='display: none;'";
                }
                $left_level3_nav .= "<ul class=\"layui-nav layui-nav-tree\" $displayNone>";
                foreach ($v["list"] as $level3v) {
                    $layuiThis ="";
                    if ($level3Index == 0) {
                        $layuiThis = "layui-this";
                    }
                    $left_level3_nav .= "<li class=\"layui-nav-item " . $layuiThis . "\"><a href=\"JavaScript:void(0);\" onclick=\"openLevel3Item('" . $level3v['args'] . "');\">" . $level3v["text"] . "</a></li>";
                    $level3Index++;
                }
                $left_level3_nav .= "</ul>";
            }
            $left_nav .= "</ul>";
        }
    }

	/**
	 * 过滤掉无权查看的菜单
	 *
	 * @param array $menu
	 * @return array
	 */
	private final function parseMenu($menu = array())
    {
        if ($this->adminInfo['sp'] == 1) return $menu;

        foreach ($menu['left'] as $k => $level2v) {
            foreach ($level2v as $v) {
                foreach ($v['list'] as $xk => $xv) {
                    $tmp = explode(',', $xv['args']);
                    krsort($tmp);
                    $menuTemp = implode('/', $tmp);
                    if (!in_array($menuTemp, $this->permission)) {

                        unset($menu['left'][$k]['list'][$xk]);
                    }
                }
                if (empty($menu['left'][$k]['list'])) {
                    unset($menu['top'][$k]);
                    unset($menu['left'][$k]);
                }
            }
        }
        return $menu;
    }
	
	
}