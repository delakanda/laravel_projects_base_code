<?php namespace App\Http\Controllers\System;

use App\Http\Controllers\CommonController;
use App\Application\Tasks\UserTasks;
use App\Application\Tasks\CommonTasks;

class UserController extends CommonController {

	public function __construct()
	{
		$this->permissionPrefix = "system_user";
		$this->taskObject = new UserTasks;
		$this->viewPath = "dashboard.system.users";
		$this->genericPath = true;
	}

	public function resetUserPassword($id)
	{
		if(self::checkUserPermissions("system_user_can_reset-password")) {
			UserTasks::resetUserPassword($id);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}

	// public function apiGetUsers($data)
	// {
	// 	$data = ucfirst($data);
	// 	$users = \DB::table("users")->where("first_name","like","%$data%")->orWhere("last_name","like","%$data%")->get();
	// 	return Response::json(
	//     	$users
	// 	);
	// }
}
