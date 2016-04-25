<?php namespace App\Application\Repositories;

use Validator;
use Illuminate\Http\Request;

class CommonRepository
{
	/**
	* Repo for common queries
	*/
	public static function insertData($request)
	{
		// if(isset($params['unique'])) {
		// 	$params['rules'][$params['unique']['']]
		// }

		foreach($request as $req) {
			var_dump($req);
		}

		die();
	}

}