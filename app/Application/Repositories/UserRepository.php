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

}