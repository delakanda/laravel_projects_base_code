<?php namespace App\Application\Repositories;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Application\Utilities\Contracts\RepositoryInterface;

class RoleRepository implements RepositoryInterface
{
	/**
	* This class is the repository for all roles queries
	*/

	public static function getAllRoles()
	{
		return Role::all();
	}

	public function getAllPaginated($pages)
	{
		return Role::orderBy("updated_at","DESC")->paginate($pages);
	}

	public function getItem($id)
	{
		return Role::find($id);
	}

	public static function getRole($id)
	{
		return Role::find($id);
	}

	// public static function getWhere($fieldName,$id,$mode)
	// {
	// 	if($mode == "MODEL_MODE")
	// 	{
	// 		return Role::where($fieldName,$id);
	// 	}
	// 	else if($mode == "DATA_MODE")
	// 	{
	// 		return Role::where($fieldName,$id)->get();
	// 	}
	// 	else
	// 	{
	// 		return null;
	// 	}
	// }

	public static function saveRole(Request $request,$id = null)
	{
		$role = ( $id == null ? new Role : self::getRole($id) );

        $role -> role_name = $request -> input("role_name");

        $role -> save();
	}

	public function search($data)
	{
		return \DB::table("roles")->select("id","role_name")
				->where("role_name","ilike","%$data%")
				->get();
	}

}