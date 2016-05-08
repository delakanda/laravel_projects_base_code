<?php

Route::group(['middleware' => ['auth'], 'prefix' => "system"], function()
{
      //users routes
      Route::get('/users/search','System\UserController@search');
      Route::get('/users/delete/{id}','System\UserController@delete');
      Route::get('/users/reset_password/{id}','System\UserController@resetUserPassword');
      Route::resource('users','System\UserController');

      //Role routes
      Route::get('/roles/search','System\RoleController@search');
      Route::get('/roles/delete/{id}','System\RoleController@delete');
      Route::get('/roles/permissions/{id}','System\RoleController@permissions');
      Route::post('/roles/save_permissions/{id}','System\RoleController@savePermissions');
      Route::resource('roles','System\RoleController');

      //permission routes
      Route::get('/permissions/search','System\PermissionController@search');
      Route::get('/permissions/delete/{id}','System\PermissionController@delete');
      Route::resource('permissions','System\PermissionController');

      //company info routes
      Route::get('/company','System\CompanyController@index');
      Route::post('/company/save','System\CompanyController@save');
});