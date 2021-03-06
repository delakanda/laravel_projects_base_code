<?php namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Application\Tasks\CommonTasks;
use App\Application\Tasks\CompanyTasks;
use Validator;
use Image;
use Hash;
use Session;
use Input;
use Redirect;
use Response;
use Auth;

class CompanyController extends Controller {

	public function index()
	{
		if(self::checkUserPermissions("system_company_can_edit")){
	    	$data = CompanyTasks::populateIndexData();
			return view('dashboard.system.company.index',$data);
		} else {
			CommonTasks::throwUnauthorized();
		}
  	}

	public function save(Request $request)
	{
		if(self::checkUserPermissions("system_company_can_edit")) {
			CompanyTasks::saveCompanyDetails($request);
		} else {
			CommonTasks::throwUnauthorized();
		}
	}
}
