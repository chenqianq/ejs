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
		
		$this->getNav($top_nav,$left_nav,$left_level3_nav,$map_nav);
		
		$data = array();
		$data['adminInfo'] 	= $this->adminInfo;

		$data['top_nav'] 	= $top_nav;

		$data['left_nav'] 	= $left_nav;

        $data['left_level3_nav'] 	= $left_level3_nav;

		$data['map_nav'] 	= $map_nav;


		
		
		return $this->renderFile ( 'init/header', $data );
	}
	
	
	
	/**
	 * 取得后台菜单    ------------------------------------------------------- ///权限跟后台地图未做
	 *
	 * top 数组是顶部菜单 ，left数组是左侧菜单
	 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
	 * @param string $permission
	 * @return
	 */
	protected final function getNav(&$top_nav,&$left_nav,&$left_level3_nav,&$map_nav)
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


        $model_nav = "<a class=\"tab-item active\" id=\"nav__nav_\" href=\"javascript:;\" onclick=\"openItem('_args_');\"><span>_text_</span></a>\n";
        $top_nav = '';

        //顶部菜单
        foreach ($arr['top'] as $k => $v) {
            $v['nav'] = $v['args'];
            $top_nav .= str_ireplace(array('_args_', '_text_', '_nav_'), $v, $model_nav);
        }
        $top_nav = str_ireplace("\n<a class=\"tab-item active\"", "\n<a class=\"tab-item\"", $top_nav);


        //左侧菜单
        $left_nav = $left_level3_nav = '';
        foreach ($arr['left'] as $k => $level2v) {
            $level2Index = 0;
            $displayNone = "";
            if ($left_nav) {
                $displayNone = "style='display: none;'";
            }

            foreach ($level2v as $v) {
                $level3Index = 0;
                if ($level2Index == 0) {
                    $left_nav .= "<ul class=\"layui-nav layui-nav-tree " . $v["parent_nav"] . " \" $displayNone>";
                }
                $left_nav .= "<li class=\"layui-nav-item  layui-nav-itemed\"><a><cite>" . $v["text"] . "</cite><span class=\"layui-nav-more\"></span></a>";
                $level2Index++;
                $displayNone = "";
                if ($left_level3_nav) {
                    $displayNone = "style='display: none;'";
                }
                $left_nav .= "<dl class=\"layui-nav-child\">";
                foreach ($v["list"] as $level3v) {
                    $args = explode(",", $level3v["args"]);
                    krsort($args);
                    $route = implode("/", $args);
                    $param = implode("-", $args);
                    $url = Zc::C("admin.http.domain") . $route;
                    $left_nav .= "<dd><a href=\"JavaScript:void(0);\" data-param=\"" . $param . "\" data-url=\"" . $url . "\">" . $level3v["text"] . "</a></dd>";
                    $level3Index++;
                }
                $left_nav .= "</dl></li>";
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
            foreach ($level2v as $currentIndx => $v) {
                foreach ($v['list'] as $xk => $xv) {
                    $tmp = explode(',', $xv['args']);
                    krsort($tmp);
                    $menuTemp = implode('/', $tmp);
                    if (!in_array($menuTemp, $this->permission)) {
                        unset($menu['left'][$k][$currentIndx]["list"][$xk]);
                    }
                }
                if(empty($menu['left'][$k][$currentIndx]["list"])){
                    unset($menu['left'][$k][$currentIndx]);
                }
            }
            if(empty($menu['left'][$k])){
                unset($menu['top'][$k]);
            }
        }
        return $menu;
    }
	
	
}