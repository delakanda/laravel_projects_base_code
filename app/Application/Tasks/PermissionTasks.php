<?php namespace App\Application\Tasks;

use Illuminate\Http\Request;
use App\Application\Repositories\PermissionRepository;
use App\Application\Utilities\Common\DataPopulator;
use App\Application\Repositories\CommonRepository;
use App\Application\Tasks\CommonTasks;
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

class PermissionTasks extends CommonTasks
{
	protected $modelName = "Permission";
	protected $rootRoute = "system";
	protected $currentRoute = "permissions";
	protected $permissionPrefix = "system_permission";
	protected $activeLinkFlag = "permission";
	protected $constraintRule = null;
	protected $successRoute = "/system/permissions";
	protected $dataArr = null;
	protected $repo = null;
	protected $model = null;

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
		$this->model = new Permission;
	}

	public function populateCreateData()
	{
		$data = DataPopulator::populateCreateData($this->dataArr);

		$data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");

		return $data;
	}

	public function populateEditData($id)
	{
    	$this->dataArr['dbDataName'] = "permission";
		$data = DataPopulator::populateEditData($this->repo,$this->dataArr,$id);

    	$permission = (new PermissionRepository)->getItem($id);
    	$data['permission'] = $permission;

	    $data['roles'] = CommonTasks::getSelectArray("roles","role_name","ASC");
	    $data['permissions_role'] = Role::where('id','=',$permission -> role_id)->first();

	    return $data;
	}

	public static function getRules()
  	{
    	return array('permission_name' => 'required');
  	}
}