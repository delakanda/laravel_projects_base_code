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
	protected $constraintRule;
	protected $successRoute;
	protected $activeLinkFlag;
	protected $dataArr;
	protected $repo;
	protected $model;
	protected $indexViewData;
	protected $addViewData;
	protected $editViewData;
	protected $viewViewData;
	protected $searchViewData;

	public function __construct()
	{
		$this->dataArr = [
			'activeLinkFlag'	=> $this->activeLinkFlag,
			'modelName'			=> $this->modelName,
			'rootRoute'			=> $this->rootRoute,
			'currentRoute'		=> $this->currentRoute,
			'permissionPrefix'	=> $this->permissionPrefix
		];

		$this->indexViewData = [
			'route'			=>	$this->successRoute,
			'permPrefix'	=>	$this->permissionPrefix,
		];

		$this->searchViewData = [
			'searchRootRoute'	=>	$this->rootRoute,
			'searchCurrentRoute'=>	$this->currentRoute,
		];
	}

	public function populateIndexData()
	{
		$this->dataArr['title'] = str_plural($this->modelName, 2);
		$this->dataArr['dbDataName'] = $this->currentRoute;

 		$data = DataPopulator::populateIndexData($this->repo,$this->dataArr);

		$data['indexViewData'] = $this->indexViewData;

		return $data;
	}

	public function populateCreateData()
	{
    	$data = DataPopulator::populateCreateData($this->dataArr);

		$data['addViewData'] = $this->addViewData;

		return $data;
	}

	public function populateEditData($id)
	{
		$this->dataArr['dbDataName'] = $this->activeLinkFlag;

		$data = DataPopulator::populateEditData($this->repo,$this->dataArr,$id);

		$data['editViewData'] = $this->editViewData;

		return $data;
	}

	public function populateShowData($id)
	{
		$this->dataArr['dbDataName'] = $this->activeLinkFlag;
		$data = DataPopulator::populateShowData($this->repo,$this->dataArr,$id);
		$data['viewViewData'] = $this->viewViewData;

		return $data;
	}

	public function populateSearchData()
	{
		$data = DataPopulator::populateCreateData($this->dataArr);

		$data['searchViewData'] = $this->searchViewData;

		return $data;
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

	public function deleteData($id)
	{
		$extraData = [ 'successRoute' => $this->successRoute,'modelName' => $this->modelName ];

		(new CommonRepository($this->repo->getItem($id)))->deleteData($extraData);
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