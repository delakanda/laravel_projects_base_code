<?php 

namespace App\Utilities\Contracts;

interface RepositoryInterface
{
	public function getAllPaginated($pages);

	public function getItem($id);
}