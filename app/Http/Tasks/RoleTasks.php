<?php namespace App\Http\Tasks; 

use Illuminate\Http\Request;
use App\Utilities\Common\DataPopulator;
use App\Application\Repositories\RoleRepository;
use App\Application\Repositories\PermissionRepository;
use App\Application\Repositories\UserRepository;
use App\Http\Controllers\RoleController;
use App\Http\Tasks\CommonTasks;
use App\Models\Role;
use App\Models\Permission;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;

class RoleTasks
{
	private $modelName = "Role";
	private $rootRoute = "system";
	private $currentRoute = "roles";
	private $permissionPrefix = "system_role";
	private $activeLinkFlag = "role";
	private $dataArr = null;
	private $repo = null;
	
	public function __construct()
	{
		$this->dataArr = [
			'activeLinkFlag'	=> $this->activeLinkFlag,
			'modelName'			=> $this->modelName,
			'rootRoute'			=> $this->rootRoute,
			'currentRoute'		=> $this->currentRoute,
			'permissionPrefix'	=> $this->permissionPrefix
		];

		$this->repo = new RoleRepository;
	}

	public static function storeRoleData(Request $request)
	{
		$rules = self::getRules();
		$rules["role_name"] = "required | unique:roles";

		$validator = Validator::make($request -> all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('/system/roles/create')
				->withErrors($validator)->withInput()->send();
		}
		else
		{
			RoleRepository::saveRole($request);

			Session::flash('message','Role Added');
			return Redirect::to('/system/roles')->send();
		}
	}

	public static function updateRoleData(Request $request, $id)
	{
		$rules = self::getRules();

		$validator = Validator::make($request -> all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('/system/roles/'.$id.'/edit')
        		->withErrors($validator)->withInput()->send();
		}
	    else
	    {
			RoleRepository::saveRole($request,$id);

			Session::flash('message', "Role Details Updated");
			return Redirect::to("/system/roles")->send();
		}
	}

	public static function deleteRoleData($id)
	{
		$role = RoleRepository::getRole($id);

		//check if users are assigned to this role, if not delete role else error (to avoid cascade delete)
		$affiliatedUsers = UserRepository::getAffiliatedToCount("role_id",$id);

		if($affiliatedUsers > 0)
		{
			Session::flash('warning', 'Cannot delete role, Users are assigned to it');
			return Redirect::to("/system/roles")->send();
		}

		//check if permissions are assigned to this role, if not delete role else error (to avoid cascade delete)
		$affiliatedPermissions = PermissionRepository::getAffiliatedToCount("role_id",$id);

		if($affiliatedPermissions > 0)
		{
			Session::flash('warning', 'Cannot delete role, Permissions are assigned to it');
			return Redirect::to("/system/roles")->send();
		}

		$role -> delete();

		Session::flash('message', 'Role deleted');
		return Redirect::to("/system/roles")->send();
	}

	public static function savePermissions(Request $request,$id)
	{
		//all checked permissions
		$selectedPermissions = $request -> all();

		//remove the form token in front of input array
		array_shift($selectedPermissions);

		//select all where role_id = selected id
		$role = RoleRepository::getRole($id);

		//select all permissions with that role id
		$rolesPermissions = PermissionRepository::getWhere("role_id",$id,"MODEL_MODE");

		//delete all permissions with that role id
		$rolesPermissions->delete();

		//add all selected permissions
		foreach($selectedPermissions as $selectedPermission)
		{
			$permission = new Permission;

			$permission -> permission_name = $selectedPermission;
			$permission -> role_id = $role->id;

			$permission -> save();
		}

		Session::flash('message', 'Permissions Saved');
		return Redirect::to("/system/roles/permissions/$id")->send();
	}

	public function populateIndexData()
	{
		$this->dataArr['title'] = 'Roles';
		$this->dataArr['dbDataName'] = 'roles'; 			

 		return DataPopulator::populateIndexData($this->repo,$this->dataArr);
	}

	public function populateCreateData()
	{
    	return DataPopulator::populateCreateData($this->dataArr);
	}

	public function populateEditData($id)
	{
		$this->dataArr['dbDataName'] = "role";
		return DataPopulator::populateEditData($this->repo,$this->dataArr,$id);
	}

	public function populateShowData($id)
	{
		$this->dataArr['dbDataName'] = "role";
		return DataPopulator::populateShowData($this->repo,$this->dataArr,$id);
	}

	public static function populatePermissionsData($id)
	{
		$data['title'] = "Role Permissions";
		$data['activeLink'] = "role";
		$data['subTitle'] = "Role Permissions";
		$data['subLinks'] = array(
			array
			(
				"title" => "Role List",
				"route" => "/system/roles",
				"icon" => "<i class='fa fa-th-list'></i>",
				"permission" => "system_role_can_view"
			),
			array
			(
				"title" => "Add Role",
				"route" => "/system/roles/create",
				"icon" => "<i class='fa fa-plus'></i>",
				"permission" => "system_role_can_add"
			)
		);

		$role = RoleRepository::getRole($id);
		$roles_permissions = PermissionRepository::getWhere("role_id",$id,"DATA_MODE");

		$data['role'] = $role;
		$data['permissions_parents'] = \Config::get("Permission.parents");
		$data['roles_permissions'] = $roles_permissions;
		$data['models'] = RoleController::getModels();

		return $data;
	}

	public function populateSearchData()
	{
		return DataPopulator::populateCreateData($this->dataArr);
	}

	public static function getRules()
	{
		return array(
			'role_name' => 'required',
		);
	}
}