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

class UserTasks extends CommonTasks
{
	protected $modelName = "User";
	protected $rootRoute = "system";
	protected $currentRoute = "users";
	protected $permissionPrefix = "system_user";
	protected $activeLinkFlag = "user";
	protected $successRoute = "system/users";
	protected $dataArr;
	protected $repo;
	protected $model;

	public function __construct()
	{
		parent::__construct();

		$this->repo = new UserRepository;
		$this->model = new User;

		//view data
		$this->indexViewData += [
			'colArray'		=>	['First Name','Last Name'],
			'foreignArray'	=>	[
				['name'=>'Role','model'=> 'App\Models\Role','key'=> 'role_id','property' => 'role_name']
			],
			'extraActions' 	=> [
				["route" => "system/users/reset_password","title" => "Reset-Password","icon" => "<i class='fa fa-refresh'></i>","permission" => "system_user_can_reset-password"]
		  	],
			'actionsArray'	=>	['view','edit','delete']
		];

		$this->addViewData = [
			'controllerPath' => 'System\UserController',
	        'partialsPath'   => 'dashboard.system.users.partials._form',
			'files' => true
		];

		$this->editViewData = [
	        'urlPath' => 'system/users',
	        'partialsPath'   => 'dashboard.system.users.partials._form',
			'files' => true
		];

		$this->viewViewData = [
			'propertiesArray' 	=> [
				['name' => 'First Name','property' => 'first_name'],
				['name' => 'Last Name','property' => 'last_name'],
				['name' => 'Email','property' => 'email'],
				['name' => 'Username','property' => 'username']
			],
	        'foreignArray'  	=> [
	         	['name'=>'Role','model'=> 'App\Models\Role','key'=> 'role_id','property' => 'role_name']
	        ]
		];
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

	public function updateData(Request $request,$id)
	{
		$rules = self::getRules();

		$validator = Validator::make($request -> all(), $rules);

		if ($validator->fails()) {
			return Redirect::to('/system/users/'.$id.'/edit')
        		->withErrors($validator)
        		->withInput()
        		->send();
		} else {

			$user = (new CommonRepository($this->repo->getItem($id)))->constructModel($request);

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

	public function deleteData($id)
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

	public function populateCreateData()
	{
		$data = DataPopulator::populateCreateData($this->dataArr);
		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");

		$data['addViewData'] = $this->addViewData;

		return $data;
	}

	public function populateEditData($id)
	{
		$this->dataArr['dbDataName'] = "user";
		$data = DataPopulator::populateEditData($this->repo,$this->dataArr,$id);

		$user = (new UserRepository)->getItem($id);

		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");//CommonTasks::getRolesArray();
		$data['users_role'] = Role::where('id','=',$user -> role_id)->first();

		$data['editViewData'] = $this->editViewData;

		return $data;
	}

	public static function getRules()
	{
		return array('first_name' => 'required','last_name' => 'required','username' => 'required','email' => 'required');

	}
}