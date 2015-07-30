<?php namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Permission;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;


class RoleController extends Controller {

	public function index()
	{
    $data['title'] = "Roles";
    $data['roles'] = Role::orderBy("updated_at","ASC")->paginate(20);
    $data['subLinks'] = array(
      array
      (
        "title" => "Add Role",
        "route" => "/system/roles/add",
        "icon" => "<i class='fa fa-plus'></i>"
      ),
      array
      (
        "title" => "Search for Role",
        "icon" => "<i class='fa fa-search'></i>"
      )
    );

    return view('dashboard.roles.index',$data);
  }

  public function add()
  {
		$data['title'] = "Add Role";
    $data['subLinks'] = array(
      array
      (
        "title" => "Role list",
				"route" => "/system/roles",
        "icon" => "<i class='fa fa-th-list'></i>"
      )
    );

    return view('dashboard.roles.add',$data);
  }

  public function create()
  {
		$rules = self::getRules();

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('/system/roles/add')
						->withErrors($validator)
						->withInput();
		}
		else
		{
			$role = new Role;

			$role -> role_name = Input::get("role_name");

			$role -> save();

			Session::flash('message','Role Added');
			return Redirect::to('/system/roles');
		}
  }

  public function edit($id)
  {
		$role = Role::find($id);

		$data['title'] = "Add Role";
		$data['role'] = $role;
    $data['subLinks'] = array(
      array
      (
        "title" => "Role list",
				"route" => "/system/roles",
        "icon" => "<i class='fa fa-th-list'></i>"
      ),
			array
      (
        "title" => "Add Role",
        "route" => "/system/roles/add",
        "icon" => "<i class='fa fa-plus'></i>"
      ),
    );

    return view('dashboard.roles.edit',$data);
  }

  public function update($id)
  {
		$role = Role::find($id);

		$rules = self::getRules();

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('/system/roles/edit/'.$id)
        		->withErrors($validator)
        		->withInput();
		}
    else
    {
			$role -> role_name = Input::get("role_name");

			$role -> push();
			Session::flash('message', "Role Details Updated");
			return Redirect::to("/system/roles");
		}

  }

  public function view($id)
  {
		$role = Role::find($id);

		$data['title'] = "View Role Details";
		$data['subLinks'] = array(
				array
				(
					"title" => "Role List",
					"route" => "/system/roles",
					"icon" => "<i class='fa fa-th-list'></i>"
				),
				array
				(
					"title" => "Add Role",
					"route" => "/system/roles/add",
					"icon" => "<i class='fa fa-plus'></i>"
				)
			);

			$data['role'] = $role;

			return view('dashboard.roles.view',$data);
  }

  public function delete($id)
  {
		$role = Role::find($id);

		//check if users are assigned to this role, if not delete role else error (to avoid cascade delete)
		$affiliatedUsers = \DB::table("users")->where("role_id",$role->id)->count();

		if($affiliatedUsers > 0)
		{
			Session::flash('message', 'Cannot delete role, Users are assigned to it');
			return Redirect::to("/system/roles");
		}
		else
		{
			$role -> delete();

			Session::flash('message', 'Role deleted');
			return Redirect::to("/system/roles");
		}

  }

	public function permissions($id)
	{
		$role = Role::find($id);

		$data['title'] = "Role Permissions";
		$data['subLinks'] = array(
				array
				(
					"title" => "Role List",
					"route" => "/system/roles",
					"icon" => "<i class='fa fa-th-list'></i>"
				),
				array
				(
					"title" => "Add Role",
					"route" => "/system/roles/add",
					"icon" => "<i class='fa fa-plus'></i>"
				)
			);

			$roles_permissions = \DB::table("permissions")->where("role_id",$role->id)->get();

			$data['role'] = $role;
			$data['permissions_parents'] = \Config::get("Permission.parents");
			$data['roles_permissions'] = $roles_permissions;
			$data['models'] = self::getModels();

			return view('dashboard.roles.permissions',$data);
	}

	public function savePermissions($id)
	{
		//all checked permissions
		$selectedPermissions = Input::all();

		//remove the form token in front of input array
		array_shift($selectedPermissions);

		//select all where role_id = selected id
		$role = Role::find($id);

		//select all permissions with that role id
		$rolesPermissions = \DB::table("permissions")->where("role_id",$role->id);

		//var_dump($rolesPermissions);die();

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
		return Redirect::to("/system/roles/permissions/$id");


	}

	public function getModels()
	{
		$scan = scandir('../app');
    $models = array();

    foreach($scan as $file)
    {
      if(!is_dir("../app/$file"))
      {
        array_push($models, str_replace(".php", "", "App\\".$file));
      }
    }

		return $models;
	}

	public function getRules()
	{
		return array(
			'role_name' => 'required',
		);
	}

}
