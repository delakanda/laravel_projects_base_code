<?php namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\Application\Tasks\RoleTasks;
use App\Application\Tasks\CommonTasks;


class RoleController extends CommonController {

	public function __construct()
	{
		$this->permissionPrefix = "system_role";
		$this->taskObject = new RoleTasks;
		$this->viewPath = "dashboard.system.roles";
		$this->genericPath = true;
	}

	public function permissions($id)
	{
		if(self::checkUserPermissions("system_role_can_permit")) {
			$data = RoleTasks::populatePermissionsData($id);
			return view('dashboard.system.roles.permissions',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}

	public function savePermissions(Request $request,$id)
	{
		if(self::checkUserPermissions("system_role_can_permit")) {
			RoleTasks::savePermissions($request,$id);
		} else {
			CommonTasks::throwUnauthorized();
		}

	}

	public static function getModels()
	{
		$scan = scandir('../app/Models');
    	$models = array();

    	foreach($scan as $file) {
      		if(!is_dir("../app/Models/$file")) {
        		array_push($models, str_replace(".php", "", "App\\Models\\".$file));
      		}
    	}

		return $models;
	}
}
