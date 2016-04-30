<?php 

namespace App\Application\Utilities\Contracts;

interface RepositoryInterface
{
	public function getAllPaginated($pages);

	public function getItem($id);
}