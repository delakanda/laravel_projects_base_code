<?php namespace App\Application\Tasks;

use Illuminate\Http\Request;
use App\Http\Controllers\System\RoleController;
use App\Application\Utilities\Common\DataPopulator;
use App\Application\Repositories\RoleRepository;
use App\Application\Repositories\PermissionRepository;
use App\Application\Repositories\CommonRepository;
use App\Application\Repositories\UserRepository;
use App\Application\Tasks\CommonTasks;
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

class RoleTasks extends CommonTasks
{
	protected $modelName = "Role";
	protected $rootRoute = "system";
	protected $currentRoute = "roles";
	protected $permissionPrefix = "system_role";
	protected $activeLinkFlag = "role";
	protected $constraintRule = ['attribute' => 'role_name','rule' => 'required | unique:roles'];
	protected $successRoute = "system/roles";
	protected $dataArr;
	protected $repo;
	protected $model;

	public function __construct()
	{
		parent::__construct();

		$this->repo = new RoleRepository;
		$this->model = new Role;

		//view data
		$this->indexViewData += [
			'colArray'		=>	['Role Name'],
			'actionsArray'	=>	['view','edit','delete'],
			'extraActions' 	=> [
				["route" => "system/roles/permissions","title" => "Permissions","icon" => "<i class='fa fa-key'></i>","permission" => "system_role_can_permit"]
		  	]
		];

		$this->addViewData = [
			'controllerPath' => 'System\RoleController',
	        'partialsPath'   => 'dashboard.system.roles.partials._form'
		];

		$this->editViewData = [
	        'urlPath' => 'system/roles',
	        'partialsPath'   => 'dashboard.system.roles.partials._form'
		];

		$this->viewViewData = [
			'propertiesArray' 	=> [
				['name' => 'Role Name','property' => 'role_name']
			]
		];

		$this->searchViewData += [
			'searchRouteName'	=>	'role_search',
			'searchText'		=>	'Search Role by Name'
		];
	}

	public function deleteData($id)
	{
		$role = (new RoleRepository)->getItem($id);

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
			$permissions = PermissionRepository::getAffiliatedRolePermissions($id);

			$permissions->delete();
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
		$role = (new RoleRepository)->getItem($id);

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

		$role = (new RoleRepository)->getItem($id);
		$roles_permissions = PermissionRepository::getWhere("role_id",$id,"DATA_MODE");

		$data['role'] = $role;
		$data['permissions_parents'] = \Config::get("permission.parents");
		$data['roles_permissions'] = $roles_permissions;
		$data['models'] = RoleController::getModels();

		return $data;
	}

	public function getRules()
	{
		return ['role_name' => 'required'];
	}
}