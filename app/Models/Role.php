<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Application\Utilities\Contracts\ModelInterface;

class Role extends CommonModel implements ModelInterface{

	protected $table = 'roles';

	//define relationships
	public function user()
	{
		return $this -> hasMany("App\Models\User");
	}

	public function permission()
	{
		return $this -> hasMany("App\Models\Permission");
	}

	public static function getPermissions()
	{
		return array(
			"system_role_can_add",
			"system_role_can_edit",
			"system_role_can_delete",
			"system_role_can_view",
			"system_role_can_search",
			"system_role_can_permit"
		);
	}

}
