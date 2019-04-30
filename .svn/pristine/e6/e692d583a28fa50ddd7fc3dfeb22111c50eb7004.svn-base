<?php
class ZcPermission {
	private $zcPermissionId;
	private $name;
	private $desc;
    /**
     * 权限所在模块名
     * @var
     */
    private $site = '';
    /**
     * 权限所在模块名
     * @var
     */
	private $moduleName = '';
    /**
     * 权限所在模块描述
     * @var
     */
	private $moduleDesc = '';
    /**
     * 权限所在类名
     * @var
     */
	private $className = '';
    /**
     * 权限所在类描述
     * @var
     */
	private $classDesc = '';

    /**
     * @var AdminService
     */
    private $adminService;

    private $adminInfo;

	public function __construct($zcPermissionId='', $name='', $desc='')
    {
        $this->zcPermissionId = $zcPermissionId;
        $this->name = $name;
        $this->desc = $desc;


        $this->adminService = ServiceFactory::getAdminService();

        $this->adminInfo = $this->adminService->getAdminInfo();
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

	public function getZcPermissionId() {
		return $this->zcPermissionId;
	}

	public function setZcPermissionId($zcPermissionId) {
		$this->zcPermissionId = $zcPermissionId;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getDesc() {
		return $this->desc;
	}

	public function setDesc($desc) {
		$this->desc = $desc;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param mixed $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return mixed
     */
    public function getModuleDesc()
    {
        return $this->moduleDesc;
    }

    /**
     * @param mixed $moduleDesc
     */
    public function setModuleDesc($moduleDesc)
    {
        $this->moduleDesc = $moduleDesc;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @return mixed
     */
    public function getClassDesc()
    {
        return $this->classDesc;
    }

    /**
     * @param mixed $classDesc
     */
    public function setClassDesc($classDesc)
    {
        $this->classDesc = $classDesc;
    }

    protected function checkAccess($route)
    {
        $admin = $this->adminInfo;

        $permissionArray = $this->adminService->getAdminPermissionListByAdminGroupId($admin['adminGid']);
        $routeArray = array_column($permissionArray, 'admin_permission_name');

        if (!in_array($route, $routeArray)) {
            return false;
        }

        return true;

    }
}