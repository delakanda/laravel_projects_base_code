<?php

//inner application API routes
Route::group(['middleware' => 'auth', 'prefix' => "api/v1"], function()
{
    Route::get('/permission_search/{id}','System\PermissionController@apiSearch');
    Route::get('/role_search/{id}','System\RoleController@apiSearch');
    Route::get('/user_search/{id}','System\UserController@apiSearch');
});
