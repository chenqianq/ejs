<?php
	class permissionController extends ZcController{
		private $config;
		private $zcDbRbac;
		private $msgStack;
		private $permissionName = array();
		private $urlHelper;
        /**
         * @var ReturnMessage
         */
		private $returnMessageHelper;
        /**
         * @var AdminService
         */
		private $adminService;
		
		public function __construct($route) {
			parent::__construct ( $route );

			$this->config = ZcFactory::getConfig();
			$this->zcDbRbac = new ZcDbRbac();
			$this->urlHelper = HelperFactory::getUrlHelper();
			$this -> returnMessageHelper = HelperFactory::getReturnMessageHelper();
            $this -> adminService = ServiceFactory::getAdminService();
		}

        /**
         * 用户权限列表页
         */
        public function index()
        {
            $page = $this -> urlHelper ->  getValue("page");
            if((int)$page <=0 || (int)$page != $page) {
                $page = 1;
            }
            $permissionList = $this->zcDbRbac->getAllPermission(null,$page,20,[]);
            $menu = NavMenu::$menu;
            $menu = $menu['top'];

            $menuTranslateArray = $this->adminService->getAssociatedInfo($menu);
            $data = [];

            foreach ($permissionList as $key => $object) {
                $rs = $this->adminService->checkPermissionTranslate($object['admin_permission_name'], $menuTranslateArray);
                if ($rs) {
                    $data[$rs['nameLangKey']]['topName'] = $rs['topName'];
                }else{
                    $data[$rs['nameLangKey']]['topName'] = "其他";
                }
                $data[$rs['nameLangKey']]['child'][$key]['id'] = $object['admin_permission_id'];
                $data[$rs['nameLangKey']]['child'][$key]['route'] = $object['admin_permission_name'];
                $data[$rs['nameLangKey']]['child'][$key]['desc'] = $object['admin_permission_desc'];
                $data[$rs['nameLangKey']]['child'][$key]['permission_type'] = $object['permission_type'];
                $data[$rs['nameLangKey']]['child'][$key]['module_desc'] = $object['module_desc'];
                $data[$rs['nameLangKey']]['child'][$key]['class_desc'] = $object['class_desc'];
            }

            $renderData = array(
                'permissionList' => $data,
            );
            $totalNum = $this -> adminService ->getPermissionNum($this->getPermissionSite());
            $renderData['pageHtml'] = $this -> getPage($totalNum);
            $this->render($renderData);
        }

        /**
         * 权限编辑
         * @return bool
         */
        public function permissionEdit(){

            $permissionId = $this->urlHelper->getValue('permissionId');
            $formSubmit = $this->urlHelper->getValue('form_submit');
            $permissionDescription = $this->urlHelper->getValue('permissionDescription');
            $permissionName = $this->urlHelper->getValue('permissionName');

            if((int)$permissionId <= 0 || (int)$permissionId != $permissionId){
                return false;
            }

            $permissionInfo = $this->zcDbRbac->getPermission($permissionId);

            if(!$permissionInfo){
                $this->render([],null,false,'children');exit;
            }


            if($formSubmit == 'ok'){
                $permission = new ZcPermission($permissionId, $permissionName, $permissionDescription);
                $res = $this->zcDbRbac->updatePermission($permission);

                if(!$res){
                    $this -> returnMessageHelper -> incomingMessage('编辑失败');exit;
                }else{
                    $this -> returnMessageHelper -> incomingMessage('编辑成功',Zc::url(YfjRouteConst::permissionIndex));exit;
                }

            }

            $menu = NavMenu::$menu;
            $menu = $menu['top'];

            $menuTranslateArray = $this->adminService->getAssociatedInfo($menu);

            $rs = $this -> adminService -> checkPermissionTranslate($permissionInfo ->getName(),$menuTranslateArray);
            $data = [];
            if($rs){
                $data['topName'] = $rs['topName'];
                $data['id'] = $permissionInfo ->getZcPermissionId();
                $data['route'] = $permissionInfo ->getName();
                $data['desc'] = $permissionInfo ->getDesc();

            }

            $renderData = array(
                'permissionInfo' => $data

            );
            $this->render($renderData,null,false,'children');
        }

				
		private function getAssociatedDesc($menuArray, $returnMenu = array(),$level = 1) {
			static $topName;
			foreach ($menuArray as $k => $v) {
				$name = isset($v['nameLangKey']) ? Zc::l($v['nameLangKey']) : $v['name'];
				if($level == 1) {
					$topName = $name;			//获取顶级导航名
				}
				if(!empty($v['route'])) {
					$returnMenu[$topName][$v['route']] = Zc::l($v['nameLangKey']);
				}
				if(!empty($v['subMenus'])) {
					foreach($v['subMenus'] as $subv) {
						$returnMenu = array_merge($this->getAssociatedDesc($v['subMenus'],$returnMenu, $level +1));				
					}
				}
			}
			return $returnMenu;
		}

        /**
         * 更新用户权限详细信息
         */
		public function updateExistsPermission()
        {

            // 获取当前的站点
            $site = $this->getPermissionSite();
            list($moduleArray, $classArray) = $this->getAllModuleAndClassArray();

            // 读取后台所有的方法作为权限
            $resourcePermission = $this->getAllPermisionResource();
            unset($this->permissionName);
            $pageSize = 30;
            $removedPermission = array();                       //移除的权限
            $removedPermissionIds = array();                    //移除的权限id
            $existsPermission = array();                        //已存在的权限

            for ($page = 1; $page <= 9999; $page++) {
                $permissionList = $this->zcDbRbac->getAllPermissionBySite(null, $page, $pageSize, $site);
                if (empty($permissionList)) {
                    break;
                }
                foreach ($permissionList as $permissionInfo) {

                    $permissionName = $permissionInfo['admin_permission_name'];
                    $permissionId = $permissionInfo['admin_permission_id'];

                    if (in_array($permissionName, $resourcePermission)) {

                        $existsPermission[] = $permissionName;                //找出所有已经添加到数据库的public 方法

                        $res = $this->updatePermissionDetail($permissionInfo, $moduleArray, $classArray);
                    } else {
                        $removedPermission[] = $permissionName;                //找出所有已经不存在的public 方法
                        $removedPermissionIds[] = $permissionId;
                    }
                }
            }

            $addedPermission = array_diff($resourcePermission, $removedPermission, $existsPermission);  //未添加到数据库的public 方法

            if (!empty($removedPermissionIds)) {
                $this->zcDbRbac->deletePermissions($removedPermissionIds);        //删除已经不存在的权限
            }

            $permission = new ZcPermission();
            if (!empty($addedPermission)) {
                foreach ($addedPermission as $adminPermissionName) {                //添加新增的权限
                    $this->addPermissionDetail($permission, $adminPermissionName, $site, $moduleArray, $classArray);
                }
            }
            $this->returnMessageHelper->incomingMessage('更新成功', Zc::url(YfjRouteConst::permissionIndex));
            exit;

        }
				
		private function getAllPermisionResource() {
//			var_dump(ZcConfigConst::DirFsLibs);exit;
			$dirFsLibs = $this->config->get(ZcConfigConst::DirFsLibsController);

			if(!is_dir($dirFsLibs)) {
				return false;
			}

			$this->getAllLibFile($dirFsLibs, '');
			return $this->permissionName;
		}
		
		private function getAllLibFile($path, $directory) {

			$allDir = dir($path);
//			var_dump($path);exit;
			while($file = $allDir->read()) {

				$curObject = "{$path}/{$file}";

				if(is_dir($curObject) && $file != '.' && $file != '..') {		//当目标为目录时

					$this->getAllLibFile($curObject, $file);
//					var_dump(111);exit;
				} elseif ($file != '.' && $file != '..') {						//当目标为文件时							
					preg_match('/class\.(.*)Controller/', $file,$source);					
					$source = HtmlTool::transLateRoute($source[1]);
					if(!empty($source)) {
						$controllers = $this->getFilePublicAction($curObject);

						foreach($controllers as $v) {
							$this->permissionName[] = $directory . "/" . $source . "/" . $v;
						}						
					}
				}
			}			
		}
		
		private function getFilePublicAction($file) {
			$controllers = array();
			if($fp = fopen($file,"r")) {
				@flock($fp,LOCK_SH);
				while (!feof($fp))
				{
					$line = fgets($fp);		//取一行
					if(preg_match("/public\s*function\s*([a-z]*)\s*\(/i",$line,$match)){	//匹配找出所有public方法					
						$controllers[] = HtmlTool::transLateRoute($match[1]);
					}
				}	
				@flock($fp,LOCK_UN);
				@fclose($fp);
			}			
			return $controllers;
		}

        /**
         * 分页html
         * @param $total
         * @return mixed
         */
        private function getPage($total,$limit = 20)
        {
            $pageTool = new PageSplit();
            $pageTool->total = $total;
            $pageTool->limit = $limit;
            $pageTool->render();
            return $pageTool->page_html;
        }

        /**
         * 获取权限的当前站点
         * @return string
         */
        private function getPermissionSite() {
            // 获取当前的站点
            $site = G_CURRENT_DOAMIN_CONST_NAME;
            /*$requestUri = $_SERVER['REQUEST_URI'];
            $serverName = $_SERVER['SERVER_NAME'];

            if ($serverName == 'www.yifanjie.com' || $site == 'G_WWW_YIFANJIE_COM_DOMAIN') {
                $pattern = '/\/(.*?)\//';
                preg_match($pattern, $requestUri, $match);
                $siteTag = $match[1] ? : 'new-yfj-admin-center';
                $site = 'G_WWW_YIFANJIE_COM_DOMAIN';
                if ($siteTag == 'options-admin-center') {
                    $site = 'G_WWW_YIFANJIE_COM_OPTIONS';
                } else if ($siteTag == 'storage-admin-center') {
                    $site = 'G_WWW_YIFANJIE_COM_STORAGE';
                } else if ($siteTag == 'yfj-admin-center') {
                    $site = 'G_WWW_YIFANJIE_COM_OlD';
                }
            } else if ($serverName == 'www.xinritao.com' || $site == 'G_WWW_XINRITAO_COM_DOMAIN') {
                $site = 'G_WWW_XINRITAO_COM_DOMAIN';
            } else if ($serverName == 'c.qinkaint.com' || $site == 'G_C_QINKAINT_COM_DOMAIN') {
                $site = 'G_C_QINKAINT_COM_DOMAIN';
            } else {

            }*/

            if (!$site) {
                $this->returnMessageHelper->incomingMessage('获取站点名错误,请检查站点名称', Zc::url(YfjRouteConst::permissionIndex));
                exit();
            }

            return $site;

        }

        /**
         * 获取所有的一级栏目名和二级栏目对应名
         * @return array
         */
        private function getAllModuleAndClassArray()
        {
            $navMenu = NavMenu::$menu;
            // 获取当前站点的所有模块
            $topNav = $navMenu['top'];
            $moduleArray = array();
            foreach ($topNav as $moduleInfo) {
                $moduleArray[$moduleInfo['args']] = $moduleInfo['text'];
            }

            $leftNav = $navMenu['left'];
            $classArray = [];
            foreach ($leftNav as $subLeftNav) {
                foreach ($subLeftNav as $navDetail) {
                    // $classArray[$navDetail['parent_nav']][$navDetail['nav']]['text'] = $navDetail['text'];
                    foreach ($navDetail['list'] as $pageList) {
                        $pageRoute = $pageList['args'];
                        $res = explode(',', $pageRoute);
                        if (!$res) {
                            continue;
                        }
                        $className = $res[1];
                        $classArray[$navDetail['parent_nav']][$className] = $navDetail['text'];
                    }
                }
            }

            return [$moduleArray, $classArray];
        }

        /**
         * @param $permissionInfo /权限信息数组
         * @param $moduleArray /一级模块名列表
         * @param $classArray  /二级类名列表
         * @return bool
         */
        private function updatePermissionDetail($permissionInfo, $moduleArray, $classArray)
        {
            $permissionId = $permissionInfo['admin_permission_id'];
            $permissionName = $permissionInfo['admin_permission_name'];
            $permissionDesc = $permissionInfo['admin_permission_desc'];
            $moduleName = $permissionInfo['module_name'];
            $moduleDesc = $permissionInfo['module_desc'];
            $className = $permissionInfo['class_name'];
            $classDesc = $permissionInfo['class_desc'];

            if (!$permissionId || !$permissionName) {
                return false;
            }

            $res = explode('/', $permissionName);
            $setModuleName = $res[0];
            $setClassName = $res[1];

            $setModuleDesc = $moduleArray[$setModuleName];
            // 当模块归属不存在,则不更新
            if (!isset($setModuleDesc)) {
                return false;
            }
            $setClassDesc = isset($classArray[$setModuleName][$setClassName]) ? $classArray[$setModuleName][$setClassName] : '其他';

            // 当存在模块和类归属,且未更新注释,则不更新
            if (!empty($moduleName) && !empty($className) && $moduleDesc == $setModuleDesc && $classDesc == $setClassDesc) {
                return false;
            }

            $permission = new ZcPermission($permissionId, $permissionName, $permissionDesc);

            $permission->setModuleName($setModuleName);
            $permission->setModuleDesc($setModuleDesc);
            $permission->setClassName($setClassName);
            $permission->setClassDesc($setClassDesc);
            $res = $this->zcDbRbac->updatePermissionModuleAndClass($permission);
            if (!$res) {
                return false;
            }
            return true;
        }

        /**
         * @param ZcPermission $permission  权限对象
         * @param $adminPermissionName /权限名
         * @param $site  /站点
         * @param $moduleArray /一级模块名列表
         * @param $classArray  /二级类名列表
         * @return bool
         */
        private function addPermissionDetail(ZcPermission $permission, $adminPermissionName,$site, $moduleArray, $classArray)
        {

            if (!$permission || !$adminPermissionName || !$site || !$moduleArray || !$classArray) {
                return false;
            }
            $permission->setName($adminPermissionName);
            $permission->setSite($site);

            $res = explode('/', $adminPermissionName);
            $moduleName = $res[0];
            $className = $res[1];
            if (isset($moduleArray[$moduleName])) {
                $permission->setModuleName($moduleName);
                $permission->setModuleDesc($moduleArray[$moduleName]);
                $permission->setClassName($className);
                $permission->setClassDesc(isset($classArray[$moduleName][$className]) ? $classArray[$moduleName][$className] : '其他');
            } else {
                $permission->setModuleName('');
                $permission->setModuleDesc('');
                $permission->setClassName('');
                $permission->setClassDesc('');
            }

            $lastInsertId = $this->zcDbRbac->addPermissionWithModuleAndClass($permission);
            if (!$lastInsertId) {
                return false;
            }
            return $lastInsertId;
        }
	}