<?php namespace App\Application\Repositories;

use App\Models\Company;

class CompanyRepository
{
	/**
	* This class is the repository for all Company queries
	*/

	public static function count()
	{
		return Company::all()->count();
	}

	public static function getCompanyDetails()
	{
		return Company::all()->first();
	}

}