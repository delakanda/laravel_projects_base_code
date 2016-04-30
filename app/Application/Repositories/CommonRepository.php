<?php namespace App\Application\Repositories;

use Validator;
use Session;
use Redirect;
use Illuminate\Http\Request;
use App\Application\Utilities\Contracts\ModelInterface;

class CommonRepository
{
	/**
		Repo for common queries
		Important : Before using this repo, All request names should match column names in the table to insert into 
	*/

	protected $model;

	public function __construct(ModelInterface $model) {
		$this->model = $model;
	}

	public function saveData($request,$rules,$extraData,$constraintRule=null)
	{
		$modelRules = $rules;

		if(isset($constraintRule)) {
			$modelRules[$constraintRule['attribute']] = $constraintRule['rule'];
		}

		$validator = Validator::make($request -> all(), $modelRules);

		if ($validator->fails()) {

			return Redirect::to($extraData['currentRoute'])
				->withErrors($validator)->withInput()->send();

		} else {

			foreach($request->all() as $key=>$req) {
				if($key == '_token' || $key == '_method') {
					continue;
				}

				$this->model->$key = $req;
			}

			$this->model->save();

			Session::flash('message',$extraData['modelName'].' Added');
			return Redirect::to($extraData['successRoute'])->send();
		}
	}

	public function deleteData()
	{
		
	}

}