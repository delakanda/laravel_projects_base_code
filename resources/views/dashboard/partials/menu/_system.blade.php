@if(isset($systemPermission))
	<a id = "system" class = "main-link"> <i class="fa fa-plus"></i> &nbsp; System	</a>

	@if(isset($companyPermission))
		<a href = "/system/company" id = "company-sub-link" class = "sub-link <?php if(isset($activeLink)) { if($activeLink == 'company') { echo 'active-link'; } } ?>">
			<i class="fa fa-user"></i> &nbsp; Company Details
		</a>
	@endif

	@if(isset($permissionPermission))
		<a href = "/system/permissions" id = "permission-sub-link" class = "sub-link <?php if(isset($activeLink)) { if($activeLink == 'permission') { echo 'active-link'; } } ?>">
			<i class="fa fa-key"></i> &nbsp; Permissions
		</a>
	@endif

	@if(isset($rolePermission))
		<a href = "/system/roles" id = "role-sub-link" class = "sub-link <?php if(isset($activeLink)) { if($activeLink == 'role') { echo 'active-link'; } } ?>">
			<i class="fa fa-gavel"></i> &nbsp; Roles
		</a>
	@endif

	@if(isset($userPermission))
		<a href = "/system/users" id = "user-sub-link" class = "sub-link <?php if(isset($activeLink)) { if($activeLink == 'user') { echo 'active-link'; } } ?>">
			<i class="fa fa-user"></i> &nbsp; Users
		</a>
	@endif
@endif