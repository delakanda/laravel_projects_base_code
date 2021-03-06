<?php namespace App\Application\Repositories;

use Validator;
use Session;
use Redirect;
use Illuminate\Http\Request;
use App\Application\Utilities\Contracts\ModelInterface;

class CommonRepository
{
	/**
		* Repo for common queries
		* Important : Before using this repo, All request names should match column names in the table to insert into
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

			return Redirect::back()
				->withErrors($validator)->withInput()->send();

		} else {

			foreach($request->all() as $key=>$req) {
				if($key == '_token' || $key == '_method') {
					continue;
				}

				$this->model->$key = $req;
			}

			$this->model->save();

			Session::flash('message',$extraData['modelName'].' Saved');
			return Redirect::to($extraData['successRoute'])->send();
		}
	}

	public function constructModel($request)
	{
		foreach($request->all() as $key=>$req) {

			if($key == '_token' || $key == '_method') {
				continue;
			}

			$this->model->$key = $req;
		}

		return $this->model;
	}

	public function deleteData($extraData)
	{
		$this->model->delete();

		Session::flash('message',$extraData['modelName'].' Deleted');
		return Redirect::to($extraData['successRoute'])->send();
	}

	public static function switchConnection($conn,$table=null)
	{
		return ($table == null ? \DB::connection($conn) : \DB::connection($conn)->table($table));
	}

}