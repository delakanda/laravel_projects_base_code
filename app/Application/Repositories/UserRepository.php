<?php namespace App\Application\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Application\Utilities\Contracts\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
	/**
	* This class is the repository for all user queries
	*/

	public static function getAllUsers()
	{
		return User::all();
	}

	public function getAllPaginated($pages)
	{
		return User::orderBy("updated_at","DESC")->paginate($pages);
	}

	public function getItem($id)
	{
		return User::find($id);
	}

	public static function getAffiliatedToCount($fieldName,$id)
	{
		return User::where($fieldName,$id)->count();
	}

	public function search($data)
	{
		return \DB::table("users")->select("users.id","first_name","last_name","email","username","role_name")
			->join("roles","roles.id","=","users.role_id")
			->where("first_name","ilike","%$data%")
			->orWhere("last_name","ilike","%$data%")
			->orWhere("email","ilike","%$data%")
			->orWhere("username","ilike","%$data%")
			->orWhere("role_name","ilike","%$data%")->get();

	}

}