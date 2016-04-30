<?php 

namespace App\Application\Utilities\Common;

use App\Application\Utilities\Contracts\RepositoryInterface;

class DataPopulator
{
	public static function populateIndexData(RepositoryInterface $repo, $dataArr)
	{
		$data['title'] = $dataArr['title'];
		$data['activeLink'] = $dataArr['activeLinkFlag'];
		$data['subTitle'] = $dataArr['title'];
		$data[$dataArr['dbDataName']] = $repo->getAllPaginated(13);
		$data['subLinks'] = array(
      		self::addLink($dataArr),
			array
			(
				"title" => "Search for ".$dataArr['modelName'],
				"route" => "/".$dataArr['rootRoute']."/".$dataArr['currentRoute']."/search",
				"icon" => "<i class='fa fa-search'></i>",
				"permission" => $dataArr['permissionPrefix']."_can_search"
			)
    	);

    	return $data;
	}

	public static function populateCreateData($dataArr)
	{
		$data['title'] = "Add ".$dataArr['modelName'];
		$data['activeLink'] = $dataArr['activeLinkFlag'];
		$data['subTitle'] = "Add ".$dataArr['modelName'];
    	$data['subLinks'] = array(
			self::listLink($dataArr)
    	);

    	return $data;
	}

	public static function populateEditData(RepositoryInterface $repo,$dataArr,$id) 
	{
		$data['title'] = "Edit ".$dataArr['modelName'];
		$data['activeLink'] = $dataArr['activeLinkFlag'];
		$data['subTitle'] = "Edit ".$dataArr['modelName'];
    	$data['subLinks'] = array(
			self::listLink($dataArr),
			self::addLink($dataArr)
    	);

		$data[$dataArr['dbDataName']] = $repo->getItem($id);

		return $data;
	}

	public static function populateShowData(RepositoryInterface $repo,$dataArr,$id)
	{
		$data['title'] = "View ".$dataArr['modelName']." Details";
		$data['activeLink'] = $dataArr['activeLinkFlag'];
		$data['subTitle'] = "View ".$dataArr['modelName']." Details";
		$data['subLinks'] = array(
			self::listLink($dataArr),
			self::addLink($dataArr),
			array
			(
				"title" => "Edit ",$dataArr['modelName'],
				"route" => "/".$dataArr['rootRoute']."/".$dataArr['currentRoute']."/".$id.'/edit',
				"icon" => "<i class='fa fa-pencil'></i>",
				"permission" => $dataArr['permissionPrefix']."_can_edit"
			),
			array
			(
				"title" => "Delete ".$dataArr['modelName'],
				"route" => "/".$dataArr['rootRoute']."/".$dataArr['currentRoute']."/delete/".$id,
				"icon" => "<i class = 'fa fa-trash'></i>",
				"permission" => $dataArr['permissionPrefix']."_can_delete"
			)
		);

		$data[$dataArr['dbDataName']] = $repo->getItem($id);

		return $data;
	}

	public static function populateSearchData()
	{
		$data['title'] = "Search for ".$dataArr['modelName'];
		$data['activeLink'] = $dataArr['activeLinkFlag'];
		$data['subTitle'] = "Search For ".$dataArr['modelName'];
		$data['subLinks'] = array(
			self::listLink($dataArr),
			self::addLink($dataArr),
		);

		return $data;
	}

	private static function addLink($dataArr)
	{
		return
		[
			"title" => "Add ".$dataArr['modelName'],
			"route" => "/".$dataArr['rootRoute']."/".$dataArr['currentRoute']."/create",
			"icon" => "<i class='fa fa-plus'></i>",
			"permission" => $dataArr['permissionPrefix']."_can_add"
		];
	}

	private static function listLink($dataArr)
	{
		return 
		[
			"title" => $dataArr['modelName']." list",
			"route" => "/".$dataArr['rootRoute']."/".$dataArr['currentRoute'],
			"icon" => "<i class='fa fa-th-list'></i>",
			"permission" => $dataArr['permissionPrefix']."_can_view"
		];
	}
}