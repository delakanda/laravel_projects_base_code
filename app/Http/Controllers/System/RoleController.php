<?php namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;
use App\Application\Tasks\RoleTasks;
use App\Application\Tasks\CommonTasks;


class RoleController extends Controller {

	public function index()
	{
		if(self::checkUserPermissions("system_role_can_view")) {
	    	$data = (new RoleTasks)->populateIndexData();
	    	return view('dashboard.system.roles.index',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function create()
  	{
		if(self::checkUserPermissions("system_role_can_add")) {
			$data = (new RoleTasks)->populateCreateData();
	    	return view('dashboard.system.roles.add',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function store(Request $request)
  	{
		if(self::checkUserPermissions("system_role_can_add")) {
			(new RoleTasks)->storeData($request);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function edit($id)
  	{
		if(self::checkUserPermissions("system_role_can_edit")) {
			$data = (new RoleTasks)->populateEditData($id);
	    	return view('dashboard.system.roles.edit',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function update(Request $request,$id)
  	{
		if(self::checkUserPermissions("system_role_can_edit")) {
			(new RoleTasks)->updateData($request, $id);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function show($id)
  	{
		if(self::checkUserPermissions("system_role_can_view")) {
			$data = (new RoleTasks)->populateShowData($id);
			return view('dashboard.system.roles.view',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function delete($id)
  	{
		if(self::checkUserPermissions("system_role_can_delete")) {
			RoleTasks::deleteRoleData($id);
		} else {
			CommonTasks::throwUnauthorized();
		}
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

	public function search()
	{
		if(self::checkUserPermissions("system_role_can_search")) {
			$data = (new RoleTasks)->populateSearchData();
			return view('dashboard.system.roles.search',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}

	public function apiSearch($data)
	{
		(new RoleTasks)->apiSearch($data);
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
