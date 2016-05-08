<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Application\Tasks\CommonTasks;
use App\Application\Utilities\Contracts\TasksInterface;

class CommonController extends Controller
{
	protected $permissionPrefix;
	protected $taskObject;
	protected $viewPath;

	public function index()
	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_view")) {
			$data = $this->taskObject->populateIndexData();
			return view($this->viewPath.".index",$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function create()
  	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_add")) {
			$data = $this->taskObject->populateCreateData();
		    return view($this->viewPath.'.add',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function store(Request $request)
  	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_add")) {
	    	$this->taskObject->storeData($request);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function edit($id)
  	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_edit")) {
	    	$data = $this->taskObject->populateEditData($id);
	    	return view($this->viewPath.'.edit',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

  	public function update(Request $request,$id)
  	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_edit")) {
	    	$this->taskObject->updateData($request,$id);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

	public function show($id)
	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_view")) {
			$data = $this->taskObject->populateShowData($id);
			return view($this->viewPath.'.view',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}

  	public function delete($id)
  	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_edit")) {
			$this->taskObject->deleteData($id);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

	public function search()
	{
		if(self::checkUserPermissions($this->permissionPrefix."_can_search")) {
			$data = $this->taskObject->populateSearchData();
			return view($this->viewPath.'.search',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}

	public function apiSearch($data)
	{
		$this->taskObject->apiSearch($data);
	}
}