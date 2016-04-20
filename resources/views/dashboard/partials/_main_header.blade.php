<header id = "main-header">

	<div id = "dash-heading" class = "float-left">
		<div id = "menu-btn"><h4><i class="fa fa-bars"></i></div>
		<a id = "dashboard" href = "/dashboard">
			Dashboard
		</a>
	</div>
	<div class = "float-right">
			@if(isset(Auth::user()->image_name))
				<div class = "box">
					<div id = "profile-pic">
						<img src = "/uploads/{{Auth::user()->image_name}}" />
					</div>
				</div>
			@else
				<div class = "box-padding">
					<i class="fa fa-user"></i>
				</div>
			@endif
		<div class = "box-padding" id = "user-name">
			{{Auth::user()->first_name}} {{Auth::user()->last_name}}
		</div>
	</div>
	<div class = "float-right">
		<a href = "/dashboard/profile">
			<div class = "box-padding" id = "profile-btn" title = "Profile Settings">
				<i class="fa fa-cog"></i>
			</div>
		</a>
		<a href = "/auth/logout">
			<div class = "box-padding" id = "logout-btn" title = "Logout">
				<i class="fa fa-power-off"></i>
			</div>
		</a>
	</div>
	
</header>