<?php

class CpaZcDbRbac extends ZcRbac {
    private $db;

    public function __construct($db = null) {
        $this->db = !empty($db) ? $db : Zc::getDb();
    }

    private function updateRoleOnly(ZcRole $role) {
        $this->db->update('zc_role', array (
            'name' => $role->getName()
        ), 'zc_role_id = %i AND name <> %s', $role->getZcRoleId(), $role->getName());

        $this->db->update('zc_role', array (
            'desc' => $role->getDesc(),
            'date_modified' => new ZcDbEval('now()')
        ), 'zc_role_id = %i', $role->getZcRoleId());
    }

    private function addRoleOnly(ZcRole $role) {

        $res = $this->db->insert('zc_role', array (
            'name' => $role->getName(),
            'desc' => $role->getDesc(),
            'date_added' => new ZcDbEval('now()'),
            'date_modified' => new ZcDbEval('now()')
        ));
        return $this->db->lastInsertId();
    }

    public function checkPartialOrder($roleId = null, $subRoleIds = array(), $upperRoleIds = array()) {
        $nodes = $this->db->queryFirstColumn("select zc_role_id from zc_role order by zc_role_id desc");
        $where = ($roleId > 0 ? (' where zc_role_id <> ' . $roleId) : '');
        $edges = $this->db->queryAllLists("select zc_role_id, zc_sub_role_id from zc_role_hierarchy" . $where);
        if ($roleId > 0 && is_array($subRoleIds) && !empty($subRoleIds)) {
            foreach ($subRoleIds as $subRoleId) {
                $edges[] = array (
                    $roleId,
                    $subRoleId
                );
            }
        }
        if ($roleId > 0 && is_array($upperRoleIds) && !empty($upperRoleIds)) {
            foreach ($upperRoleIds as $upperRoleId) {
                $edges[] = array (
                    $upperRoleId,
                    $roleId
                );
            }
        }

        // 用链地址法初始化整个有向图和入度数组
        $graph = array ();
        $inDegree = array ();
        foreach ($nodes as $node) {
            $graph[(int)$node] = array ();
            $inDegree[(int)$node] = 0;
        }
        foreach ($edges as $edge) {
            $src = (int)$edge[0];
            $dst = (int)$edge[1];
            $graph[$src][$dst] = $dst;
            $inDegree[$dst] ++;
        }

        // 建立入度为0的结点栈
        $zeroStack = array ();
        foreach ($inDegree as $key => $value) {
            if ($value == 0) {
                $zeroStack[] = $key;
            }
        }

        $count = 0;
        while (!empty($zeroStack)) {
            $node = array_pop($zeroStack);
            $count ++;
            foreach ($graph[$node] as $dst) {
                $inDegree[$dst] --;
                if ($inDegree[$dst] === 0) {
                    $zeroStack[] = $dst;
                }
            }
        }

        return ($count === count($nodes));
    }

    private function addSubRoleIds($roleId, $subRoleIds) {
        $checkPartialOrder = $this->checkPartialOrder($roleId, $subRoleIds);
        if (!$checkPartialOrder) {
            throw new ZcRolePartialOrderException('checkPartialOrder failed');
        }
        $this->db->delete('zc_role_hierarchy', 'zc_role_id = %i', $roleId);
        if (!empty($subRoleIds)) {
            $roleHierarchy = array ();
            foreach ($subRoleIds as $subRoleId) {
                $roleHierarchy[] = array (
                    'zc_role_id' => $roleId,
                    'zc_sub_role_id' => $subRoleId,
                    'date_added' => new ZcDbEval('now()')
                );
            }
            $this->db->insert('zc_role_hierarchy', $roleHierarchy);
        }
    }

    public function addUpperRoleIds($roleId, $upperRoleIds) {
        $checkPartialOrder = $this->checkPartialOrder($roleId, array(), $upperRoleIds);
        if (!$checkPartialOrder) {
            throw new ZcRolePartialOrderException('checkPartialOrder failed');
        }

        if (!empty($upperRoleIds)) {
            $roleHierarchy = array ();
            foreach ($upperRoleIds as $upperRoleId) {
                $roleHierarchy[] = array (
                    'zc_role_id' => $upperRoleId,
                    'zc_sub_role_id' => $roleId,
                    'date_added' => new ZcDbEval('now()')
                );
            }
            $this->db->insert('zc_role_hierarchy', $roleHierarchy);
        }
    }

    private function addRoleToPermissions($roleId, $permissionIds) {
        $this->db->delete('zc_role_to_permission', 'zc_role_id = %i', $roleId);

        if (!empty($permissionIds)) {
            $roleToPermissions = array ();
            foreach ($permissionIds as $permissionId) {
                $roleToPermissions[] = array (
                    'zc_role_id' => $roleId,
                    'zc_permission_id' => $permissionId,
                    'date_added' => new ZcDbEval('now()')
                );
            }
            $this->db->insert('zc_role_to_permission', $roleToPermissions);
        }
    }

    private function addOrUpdateRole(ZcRole $role) {
        $transStatus = $this->db->startTransaction();
        $oldErrorMode = $this->db->setErrorMode(ZcDB::ERROR_MODE_EXCEPTION);

        try {
            $roleId = $role->getZcRoleId();
            if ($roleId > 0) {
                $this->updateRoleOnly($role);
            } else {
                $roleId = $this->addRoleOnly($role);
            }
            $this->addSubRoleIds($roleId, $role->getSubRoleIds());

            $this->addRoleToPermissions($roleId, $role->getPermissionIds());

            $this->db->commit($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return $roleId;
        } catch (Exception $ex) {
            $this->db->rollback($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return false;
        }
    }

    public function addRole(ZcRole $role) {
        $role->setZcRoleId(null);
        return $this->addOrUpdateRole($role);
    }

    public function updateRole(ZcRole $role) {
        return $this->addOrUpdateRole($role);
    }

    public function deleteRoles($roleIds) {
        if (empty($roleIds)) {
            return 0;
        }
        if (is_int($roleIds)) {
            $tmp[] = $roleIds;
            $roleIds = $tmp;
        }

        $transStatus = $this->db->startTransaction();
        $oldErrorMode = $this->db->setErrorMode(ZcDB::ERROR_MODE_EXCEPTION);

        try {
            $this->db->delete('zc_role_hierarchy', 'zc_role_id in %li or zc_sub_role_id in %li', $roleIds, $roleIds);
            $this->db->delete('zc_role_to_permission', 'zc_role_id in %li', $roleIds);
            $this->db->delete('zc_user_to_role', 'zc_role_id in %li', $roleIds);
            $this->db->delete('zc_role', 'zc_role_id in %li', $roleIds);

            $this->db->commit($transStatus);
            $this->db->setErrorMode($oldErrorMode);
        } catch (Exception $ex) {
            $this->db->rollback($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return false;
        }
    }

    public function getAllRoles($userId = null) {
        $sql = '';
        if ($userId > 0) {
            $rps = $this->getUserAllRoleIdsAndPermissionIds($userId);
            $userRoleIds = $rps[0];
            if(empty($userRoleIds)) {
                return array();
            }
            $sql = $this->db->prepare("select * from zc_role where zc_role_id in %li", $userRoleIds);
        } else {
            $sql = "select * from zc_role";
        }

        $ret = array ();

        $rows = $this->db->query($sql);
        foreach ($rows as $row) {
            $ret[] = new ZcRole($row['zc_role_id'], $row['name'], $row['desc']);
        }

        return $ret;
    }

    public function getAllSubRoleIds($roleId = null) {
        if (empty($roleId)) {
            return $this->db->queryFirstColumn("SELECT DISTINCT zc_sub_role_id FROM zc_role_hierarchy");
        }

        $allRoleIds = $allPermissionIds = array();
        $this->getRoleIdsAndPermissionIdsRecursive($roleId, $allRoleIds, $allPermissionIds);
        array_shift($allRoleIds);
        return $allRoleIds;
    }



    public function updatePermission(ZcPermission $permission) {
        $transStatus = $this->db->startTransaction();
        $oldErrorMode = $this->db->setErrorMode(ZcDB::ERROR_MODE_EXCEPTION);


        try {
//			$this->db->update('zc_permission', array (
//					'name' => $permission->getName(),
//			), 'zc_permission_id = %i AND name <> %s', $permission->getZcPermissionId(), $permission->getName());

            $this->db->update('cpa_user_permission', array (
                'user_permission_desc' => $permission->getDesc(),
                'gmt_edit' => date('Y-m-d H:i:s',time())
            ), 'user_permission_id = %i', $permission->getZcPermissionId());

            $this->db->commit($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return true;
        } catch (Exception $ex) {
            $this->db->rollback($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return false;
        }
    }



    public function getPermission($permissionId) {
        if(!is_numeric($permissionId)) {
            return false;
        }
        $permissionRow = $this->db->queryFirstRow("select * from cpa_user_permission where user_permission_id = %i", $permissionId);
        return new ZcPermission($permissionRow['user_permission_id'], $permissionRow['user_permission_name'], $permissionRow['user_permission_desc']);
    }

    /**
     * 获取权限组
     * @param null $userId
     * @param int  $page
     * @param int  $pageSize
     * @return array
     */
    public function getAllPermission($userId = null, $page=1, $pageSize=20) {
        $sql = '';
        if ($userId > 0) {
            $rps = $this->getUserAllRoleIdsAndPermissionIds($userId);
            $userPermissionIds = $rps[1];
            $sql = $this->db->prepare("select * from cpa_user_permission where user_permission_id in %li ORDER BY `user_permission_id`", $userPermissionIds);
        } else {

            $sql = "select * from cpa_user_permission ORDER BY `user_permission_id`";
        }

        $offset = ($page - 1) * $pageSize;

        $sql .= " limit " . $offset . ", " . $pageSize; 

        $rows = $this->db->query($sql);

        if ( !$rows ) {
            return false;
        }

        return $rows;
    }

    /**
     * 删除用户组权限
     * @param $permissionIds
     * @return bool|int
     */
    public function deletePermissions($permissionIds) {
        if (empty($permissionIds)) {
            return 0;
        }
        if (is_int($permissionIds)) {
            $tmp[] = $permissionIds;
            $permissionIds = $tmp;
        }

        $transStatus = $this->db->startTransaction();
        $oldErrorMode = $this->db->setErrorMode(ZcDB::ERROR_MODE_EXCEPTION);

        try {
            $this->db->delete('cpa_user_permission', 'user_permission_id in %li', $permissionIds);
            $this->db->delete('cpa_user_role_to_permission', 'user_permission_id in %li', $permissionIds);
            
            $this->db->commit($transStatus);
            $this->db->setErrorMode($oldErrorMode);
        } catch (Exception $ex) {
            $this->db->rollback($transStatus);
            $this->db->setErrorMode($oldErrorMode);
            return false;
        }
    }

    /**
     * 添加用户组权限
     * @param ZcPermission $permission
     * @return bool
     */
    public function addPermission(ZcPermission $permission) {
        $ret = $this->db->insert('cpa_user_permission', array (
            'user_permission_name' => $permission->getName(),
//			'desc' => $permission->getDesc(),
            'gmt_create' => date('Y-m-d H:i:s',time())

        ));
        if ($ret === false) {
            return false;
        }
        return $this->db->lastInsertId();
    }



    public function assign($userId, $roleIds = array()) {
        if (empty($roleIds)) {
            return 0;
        }
        if (is_int($roleIds)) {
            $tmp[] = $roleIds;
            $roleIds = $tmp;
        }

        $userToRoleIds = array ();
        foreach ($roleIds as $roleId) {
            $userToRoleIds[] = array (
                'zc_user_id' => $userId,
                'zc_role_id' => $roleId,
                'date_added' => new ZcDbEval('now()')
            );
        }
        return $this->db->insert('zc_user_to_role', $userToRoleIds);
    }

    public function revokeAllRoleByUserId($userId) {
        return $this->db->delete('zc_user_to_role', 'zc_user_id = %i', $userId);
    }

    public function revoke($userId, $roleIds = array()) {
        if (empty($roleIds)) {
            return 0;
        }
        if (is_int($roleIds)) {
            $tmp[] = $roleIds;
            $roleIds = $tmp;
        }

        return $this->db->delete('zc_user_to_role', 'zc_user_id = %i and zc_role_id in %li', $userId, $roleIds);
    }

    public function getRole($roleId) {
        $roleRow = $this->db->queryFirstRow("select * from zc_role where zc_role_id = %i", $roleId);
        if (empty($roleRow)) {
            return null;
        }
        $subRoleIds = $this->db->queryFirstColumn("select zc_sub_role_id from zc_role_hierarchy where zc_role_id = %i", $roleId);
        $permissionIds = $this->db->queryFirstColumn("select zc_permission_id from zc_role_to_permission where zc_role_id = %i", $roleId);
        return new ZcRole($roleId, $roleRow['name'], $roleRow['desc'], $subRoleIds, $permissionIds);
    }

    public function getSubRoles($roleId) {
        if(!is_numeric($roleId)) {
            return false;
        }
        $sql = "SELECT r.name, r.desc, rh.zc_sub_role_id
				FROM zc_role_hierarchy rh
				LEFT JOIN zc_role r ON r.zc_role_id = rh.zc_sub_role_id
				WHERE rh.zc_role_id = %i";
        $res = $this->db->query($sql, $roleId);
        if(!empty($res)) {
            foreach($res as $v) {
                $roleArray[] = new ZcRole($v['zc_sub_role_id'], $v['name'], $v['desc']);
            }
        }
        return $roleArray;
    }

    public function getUpperRoleIds($roleId) {
        if(!is_numeric($roleId)) {
            return false;
        }
        return $this->db->queryFirstColumn("SELECT zc_role_id FROM zc_role_hierarchy WHERE zc_sub_role_id = %i", $roleId);
    }

    public function getDirectUserIdsByRoleId($roleId) {
        $sql = "SELECT zc_user_id FROM zc_user_to_role WHERE zc_role_id = %i";
        $rows = $this->db->queryFirstColumn($sql,$roleId);
        return $rows;
    }

    /**
     * 这个方法之所以不会陷入死循环，是因为角色是偏序关系的
     *
     * @param int $roleId
     * @param int $permissionId
     * @return boolean
     */
    private function checkAccessRecursive($roleId, $permissionId) {
        $role = $this->getRole($roleId);
        if (!$role) {
            return false;
        }

        $permissionIds = $role->getPermissionIds();
        if (empty($permissionIds)) {
            return false;
        }
        foreach ($role->getPermissionIds() as $pid) {
            if ($pid == $permissionId) {
                return true;
            }
        }

        $subRoleIds = $role->getSubRoleIds();
        if (empty($subRoleIds)) {
            return false;
        }
        $pass = false;
        foreach ($role->getSubRoleIds() as $roleId) {
            if ($this->checkAccessRecursive($roleId, $permissionId)) {
                $pass = true;
                break;
            }
        }
        return $pass;
    }

    private function getRoleIdsAndPermissionIdsRecursive($roleId, &$allRoleIds, &$allPermissionIds) {
        $allRoleIds[] = $roleId;
        $role = $this->getRole($roleId);
        $allPermissionIds = array_merge($allPermissionIds, $role->getPermissionIds());

        $subRoleIds = $role->getSubRoleIds();
        foreach($subRoleIds as $subRoleId) {
            $this->getRoleIdsAndPermissionIdsRecursive($subRoleId, $allRoleIds, $allPermissionIds);
        }
    }

    private function getUserAllRoleIdsAndPermissionIds($userId) {
        $allRoleIds = array();
        $allPermissionIds = array();

        $directRoleIds = $this->getUserDirectRoleIds($userId);
        foreach($directRoleIds as $roleId) {
            $this->getRoleIdsAndPermissionIdsRecursive($roleId, $allRoleIds, $allPermissionIds);
        }
        return array(array_unique($allRoleIds), array_unique($allPermissionIds));
    }

    private function getUserDirectRoleIds($userId) {
        return $this->db->queryFirstColumn("select zc_role_id from zc_user_to_role where zc_user_id = %i", $userId);
    }

    public function checkAccess($userId, $permissionName, $assert = null) {
        if ($assert) {
            if ($assert instanceof ZcIAuthAssertion) {
                if (!$assert->assert($this)) {
                    return false;
                }
            } elseif (is_callable($assert)) {
                if (!$assert($this)) {
                    return false;
                }
            } else {
                throw new Exception('Assertions must be a Callable or an instance of ZcIAssertionInterface');
            }
        }

        $permissionId = $this->db->queryFirstField("select zc_permission_id from zc_permission where name = %s", $permissionName);
        $roleIds = $this->getUserDirectRoleIds($userId);

        $pass = false;
        foreach ($roleIds as $roleId) {
            if ($this->checkAccessRecursive($roleId, $permissionId)) {
                $pass = true;
                break;
            }
        }
        return $pass;
    }
}