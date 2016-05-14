<?php

namespace App\Models;

class Company extends CommonModel{

	protected $table = 'company';

	public static function getPermissions()
	{
		return array(
			"system_company_can_edit"
		);
	}

}
