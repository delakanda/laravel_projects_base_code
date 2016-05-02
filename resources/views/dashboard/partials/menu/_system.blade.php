@if(isset($systemPermission))
	<a id = "system" class = "main-link"> <i class="fa fa-plus"></i> &nbsp; System	</a>

	@include("dashboard.partials.menu._sub_menu_item",
		[	
			'permissionName' => $companyPermission, 'route' => '/system/company', 'id' => 'company-sub-link',
			'activeLinkName' => 'company','icon' => 'fa-user','displayName' => 'Company Details' 
		] 
	)

	@include("dashboard.partials.menu._sub_menu_item",
		[	
			'permissionName' => $permissionPermission, 'route' => '/system/permissions', 'id' => 'permission-sub-link',
			'activeLinkName' => 'permission','icon' => 'fa-key','displayName' => 'Permissions' 
		] 
	)

	@include("dashboard.partials.menu._sub_menu_item",
		[	
			'permissionName' => $rolePermission, 'route' => '/system/roles', 'id' => 'role-sub-link',
			'activeLinkName' => 'role','icon' => 'fa-gavel','displayName' => 'Roles' 
		] 
	)

	@include("dashboard.partials.menu._sub_menu_item",
		[	
			'permissionName' => $userPermission, 'route' => '/system/users', 'id' => 'user-sub-link',
			'activeLinkName' => 'user','icon' => 'fa-user','displayName' => 'Users' 
		] 
	)

@endif