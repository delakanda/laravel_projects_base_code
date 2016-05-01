<?php namespace App\Http\Tasks; 

use Illuminate\Http\Request;
use App\Application\Repositories\PermissionRepository;
use App\Application\Utilities\Common\DataPopulator;
use App\Application\Repositories\CommonRepository;
use App\Http\Tasks\CommonTasks;
use App\Models\Permission;
use App\Models\Role;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;

class PermissionTasks
{
	private $modelName = "Permission";
	private $rootRoute = "system";
	private $currentRoute = "permissions";
	private $permissionPrefix = "system_permission";
	private $activeLinkFlag = "permission";
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

		$this->repo = new PermissionRepository;
	}

	public function storeData(Request $request)
	{
		$extraData = ['currentRoute' => '/system/permissions/create','successRoute' => '/system/permissions','modelName' => 'Permission'];

		(new CommonRepository(new Permission()))->saveData($request,self::getRules(),$extraData);
	}

	public function updateData(Request $request,$id)
	{
		$extraData = ['currentRoute' => '/system/permissions/'.$id.'/edit','successRoute' => '/system/permissions','modelName' => 'Permission'];

		(new CommonRepository((new PermissionRepository)->getItem($id)))->saveData($request,self::getRules(),$extraData);
	}

	public function deleteData($id)
	{
		$extraData = [ 'successRoute' => '/system/permissions','modelName' => 'Permission' ];

		(new CommonRepository((new PermissionRepository)->getItem($id)))->deleteData($extraData);
	}

	public function populateIndexData()
	{
		$this->dataArr['title'] = 'Permission';
		$this->dataArr['dbDataName'] = 'permissions'; 			

 		return DataPopulator::populateIndexData($this->repo,$this->dataArr);
	}

	public function populateCreateData()
	{
		$data = DataPopulator::populateCreateData($this->dataArr);

		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");//CommonTasks::getRolesArray();

		return $data;
	}

	public function populateEditData($id)
	{
    	$this->dataArr['dbDataName'] = "permission";
		$data = DataPopulator::populateEditData($this->repo,$this->dataArr,$id);

    	$permission = (new PermissionRepository)->getItem($id);
    	$data['permission'] = $permission;

	    $data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");//CommonTasks::getRolesArray();
	    $data['permissions_role'] = Role::where('id','=',$permission -> role_id)->first();

	    return $data;
	}

	public function populateShowData($id)
	{
		$this->dataArr['dbDataName'] = "permission";
		return DataPopulator::populateShowData($this->repo,$this->dataArr,$id);
	}

	public function populateSearchData()
	{
		return DataPopulator::populateCreateData($this->dataArr);
	}

	public static function getRules()
  	{
    	return array(
      		'permission_name' => 'required'
    	);
  	}
}