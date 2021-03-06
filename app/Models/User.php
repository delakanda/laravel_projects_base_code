<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Application\Utilities\Contracts\ModelInterface;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract,ModelInterface
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'username', 'first_name', 'last_name','password','status','image_name','role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

	  //define relationships
    public function role()
    {
      return $this -> belongsTo("App\Models\Role");
    }


    public static function getPermissions()
    {
      return array(
        "system_user_can_add",
        "system_user_can_edit",
        "system_user_can_delete",
        "system_user_can_view",
        "system_user_can_search",
        "system_user_can_reset-password"
      );
    }
}
