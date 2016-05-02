<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		//get company details
		$company = App\Models\Company::all()->first();
	?>
	<title>@if(isset($company->company_name)) {{ $company->company_name }} @else Company @endif | {{$title}}</title>

	@include('dashboard.partials.links._external_resources')

</head>
<body>
	<!-- check if user has permissions -->
		<?php
			//variables for permissions

			$permissions = \DB::table("permissions")->where("role_id",Auth::user()->role_id)->get();
			$configParentPermissions = \Config::get("Permission.parents");
			$models = App\Http\Controllers\RoleController::getModels();

			foreach($permissions as $permission)
			{

				foreach($configParentPermissions as $configPerm)
				{

					if(explode("_",$permission->permission_name)[0] == $configPerm)
					{
						${$configPerm . "Permission"} = 1;
						break;
					}

				}

				foreach($models as $model)
				{
					if(explode("_",$permission->permission_name)[1] == str_replace("app\\models\\","",strtolower($model)))
					{
						${explode("_",$permission->permission_name)[1] . "Permission"} = 1;
						break;
					}

				}

			}
		?>

	@include('dashboard.partials._main_header')

	@include('dashboard.partials._main_nav')  	

  	<div id = "content-wrapper" class = "width-normal">
    	
  		@include('dashboard.partials.flash._flash_messages')

		@if(isset($subTitle))
			<h4 class = "sub-title">
				{{ $subTitle }}
			</h4>
		@endif

    <div id = "content">

		@include('dashboard.partials.links._sublinks')

      	@yield("content")

    </div>

  </div>

  <div class = "clear-floats"></div>

</body>

</html>
