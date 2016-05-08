<?php namespace App\Application\Tasks; 

use Illuminate\Http\Request;
use App\Application\Utilities\Common\DataPopulator;
use App\Application\Repositories\UserRepository;
use App\Application\Repositories\RoleRepository;
use App\Http\Controllers\RoleController;
use App\Application\Repositories\CommonRepository;
use App\Application\Tasks\CommonTasks;
use App\Models\User;
use App\Models\Role;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;

class UserTasks
{
	private $modelName = "User";
	private $rootRoute = "system";
	private $currentRoute = "users";
	private $permissionPrefix = "system_user";
	private $activeLinkFlag = "user";
	private $dataArr = null;
	private $repo = null;
	private $model = null;
	
	public function __construct()
	{
		$this->dataArr = [
			'activeLinkFlag'	=> $this->activeLinkFlag,
			'modelName'			=> $this->modelName,
			'rootRoute'			=> $this->rootRoute,
			'currentRoute'		=> $this->currentRoute,
			'permissionPrefix'	=> $this->permissionPrefix
		];

		$this->repo = new UserRepository;
	}

	public function storeData(Request $request)
	{
		$rules = self::getRules();
		$rules["username"] = "required | unique:users";

		$validator = Validator::make($request -> all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('/system/users/create')
				->withErrors($validator)->withInput()->send();
		}
		else {

			$user = (new CommonRepository(new User()))->constructModel($request);

			if($request -> file('image_name')) {
				$storageName = CommonTasks::prepareImage($request -> file('image_name'),200,200);
				$user -> image_name = $storageName;
			} else {
				$user -> image_name = null;
			}

			$user -> status = 2;
			$user -> password = Hash::make("password");

			$user -> save();
			Session::flash('message','User Added');
			return Redirect::to('/system/users')->send();
	  	}
	}

	public static function updateData(Request $request,$id)
	{
		$rules = self::getRules();

		$validator = Validator::make($request -> all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('/system/users/'.$id.'/edit')
        		->withErrors($validator)
        		->withInput()
        		->send();
		} else {

			$user = (new CommonRepository((new UserRepository)->getItem($id)))->constructModel($request);

			//DEAL WITH IMAGE FILE
			if($request -> file('image_name')) {
				if($user->image_name != null) {
					CommonTasks::deleteImage($user->image_name);
				}

				$storageName = CommonTasks::prepareImage($request -> file('image_name'),200,200);
				$user -> image_name = $storageName;
			} else {	
				if($request -> input("clear_check") == 'yes') {
					CommonTasks::deleteImage($user->image_name);
	          		$user->image_name = null;
	        	}	
			}

			$user -> push();
			Session::flash('message', "User Details Updated");
			return Redirect::to("/system/users")->send();
		}
	}

	public static function deleteData($id)
	{
		$user = (new UserRepository)->getItem($id);

    	if($user -> image_name != null) {
      		if (file_exists(public_path('uploads/'.$user -> image_name))) {
	        	unlink(public_path('uploads/'.$user -> image_name));
	  		}
    	}

	    $user -> delete();

	    Session::flash('message', 'User deleted');
		return Redirect::to("/system/users")->send();
	}

	public static function resetUserPassword($id)
	{
		$user = (new UserRepository)->getItem($id);

		$user -> status = 2;
		$user -> password = Hash::make("password");

		$user -> push();

		Session::flash('message', 'User\'s password reset');
		return Redirect::to("/system/users")->send();
	}

	public function populateIndexData()
	{
		$this->dataArr['title'] = 'Users';
		$this->dataArr['dbDataName'] = 'users'; 			

 		return DataPopulator::populateIndexData($this->repo,$this->dataArr);
	}

	public function populateCreateData()
	{
		$data = DataPopulator::populateCreateData($this->dataArr);
		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");

		return $data;
	}

	public function populateEditData($id)
	{
		$this->dataArr['dbDataName'] = "user";
		$data = DataPopulator::populateEditData($this->repo,$this->dataArr,$id);

		$user = (new UserRepository)->getItem($id);

		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");//CommonTasks::getRolesArray();
		$data['users_role'] = Role::where('id','=',$user -> role_id)->first();

		return $data;
	}

	public function populateShowData($id)
	{
		$this->dataArr['dbDataName'] = "user";
		return DataPopulator::populateShowData($this->repo,$this->dataArr,$id);
	}

	public function populateSearchData()
	{
		return DataPopulator::populateCreateData($this->dataArr);
	}

	public function apiSearch($data)
	{
		$users = UserRepository::search($data);

		return Response::json(
			$users
		)->send();
	}

	public static function getRules()
	{
		return array(
			'first_name' => 'required',
			'last_name' => 'required',
			'username' => 'required',
			'email' => 'required',
		);

	}
}