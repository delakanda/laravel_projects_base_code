<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Application\Utilities\Contracts\ModelInterface;

class CommonModel extends Model
{
    public function changeConnection($conn)
    {
        $this->connection = $conn;
    }

    public static function getPermissions()
	{
		return array(
            
		);
	}
}