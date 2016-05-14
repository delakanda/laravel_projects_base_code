<?php namespace App\Application\Repositories;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Application\Utilities\Contracts\RepositoryInterface;

class PermissionRepository implements RepositoryInterface
{
	/**
	* This class is the repository for all permission queries
	*/
	public static function getAllPermissions()
	{
		return Permission::all();
	}

	public function getAllPaginated($pages)
	{
		return Permission::orderBy("permission_name","ASC")->paginate($pages);
	}

	public function getItem($id)
	{
		return Permission::find($id);
	}

	public static function getPermission($id)
	{
		return Permission::find($id);
	}

	public static function getAffiliatedToCount($fieldName,$id)
	{
		return Permission::where($fieldName,$id)->count();
	}

	public static function getAffiliatedRolePermissions($roleId)
	{
		return Permission::where("role_id",$roleId);
	}

	public static function getWhere($fieldName,$id,$mode)
	{
		if($mode == "MODEL_MODE")
		{
			return Permission::where($fieldName,$id);
		}
		else if($mode == "DATA_MODE")
		{
			return Permission::where($fieldName,$id)->get();
		}
		else
		{
			return false;
		}
	}

	public static function savePermission(Request $request,$id = null)
	{
		$permission = ( $id == null ? new Permission : self::getPermission($id) );

        $permission -> permission_name = $request -> input("permission_name");
      	$permission -> role_id = $request -> input("role_id");

		$permission -> save();
	}

	public static function deletePermission($id)
	{
		$permission = Permission::find($id);

	    $permission -> delete();
	}

	public function search($data)
	{
		return \DB::table("permissions")->select("permissions.id","permission_name","role_name")
			->join("roles","roles.id","=","permissions.role_id")
			->where("permission_name","ilike","%$data%")
			->orWhere("role_name","ilike","%$data%")
			->get();
	}

}