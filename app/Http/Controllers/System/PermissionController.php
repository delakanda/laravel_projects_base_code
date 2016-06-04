<?php namespace App\Http\Controllers\System;

use App\Http\Controllers\CommonController;
use App\Application\Tasks\PermissionTasks;

class PermissionController extends CommonController {

	public function __construct()
	{
		$this->permissionPrefix = "system_permission";
		$this->taskObject = new PermissionTasks;
		$this->viewPath = "dashboard.system.permissions";
		$this->genericPath = true;
	}
}
