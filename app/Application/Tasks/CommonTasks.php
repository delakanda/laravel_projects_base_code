<?php namespace App\Application\Tasks;

use Image;
use Response;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Application\Repositories\CommonRepository;
use App\Application\Utilities\Common\DataPopulator;

class CommonTasks
{
	protected $modelName;
	protected $rootRoute;
	protected $currentRoute;
	protected $permissionPrefix;
	protected $activeLinkFlag;
	protected $dataArr;
	protected $repo;

	public function populateIndexData()
	{
		$this->dataArr['title'] = str_plural($this->modelName, 2);
		$this->dataArr['dbDataName'] = $this->currentRoute;

 		return DataPopulator::populateIndexData($this->repo,$this->dataArr);
	}

	public function populateCreateData()
	{
    	return DataPopulator::populateCreateData($this->dataArr);
	}

	public function populateEditData($id)
	{
		$this->dataArr['dbDataName'] = $this->activeLinkFlag;
		return DataPopulator::populateEditData($this->repo,$this->dataArr,$id);
	}

	public function populateShowData($id)
	{
		$this->dataArr['dbDataName'] = $this->activeLinkFlag;
		return DataPopulator::populateShowData($this->repo,$this->dataArr,$id);
	}

	public function populateSearchData()
	{
		return DataPopulator::populateCreateData($this->dataArr);
	}

	public function apiSearch($data)
	{
		$results = $this->repo->search($data);

		return Response::json($results)->send();
	}

	public function storeData(Request $request)
	{
		$constraintRule = ($this->constraintRule == null ? null : $this->constraintRule);

		$extraData = ['successRoute' => $this->successRoute,'modelName' => $this->modelName];

		(new CommonRepository($this->model))->saveData($request,$this->getRules(),$extraData,$constraintRule);
	}

	public function updateData(Request $request, $id)
	{
		$extraData = ['successRoute' => $this->successRoute,'modelName' => $this->modelName];

		(new CommonRepository($this->repo->getItem($id)))->saveData($request,$this->getRules(),$extraData);
	}

	public static function getSelectArray($tableName,$orderByColumn,$orderByMode)
	{
		$data = \DB::table($tableName)->orderBy($orderByColumn,$orderByMode)->get();

		$dataArray = array();

		foreach($data as $d) {
			$dataArray[$d -> id] = $d -> {$orderByColumn};
		}

		return $dataArray;
	}

	public static function prepareImage($image,$width,$height)
	{
		$storageImageName = time()."_".str_replace(" ","_",$image->getClientOriginalName());
		$destinationImagePath = public_path('uploads/' . $storageImageName);
		$resizedImage = Image::make($image)->resize($width,$height);
		$resizedImage -> save($destinationImagePath);

		return $storageImageName;
	}

	public static function deleteImage($imageName)
	{
		if (file_exists(public_path('uploads/'.$imageName))) {
			unlink(public_path('uploads/'.$imageName));
		}
	}

	public static function throwUnauthorized()
	{
		abort(403,'Unauthorized');
	}
}