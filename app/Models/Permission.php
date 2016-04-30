<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model{

	protected $table = 'permissions';

	//define relationships
	public function role()
	{
		return $this -> belongsTo("App\Models\Role");
	}

	public static function getPermissions()
	{
		return array(
			"system_permission_can_add",
			"system_permission_can_edit",
			"system_permission_can_delete",
			"system_permission_can_view",
			"system_permission_can_search"
		);
	}

}