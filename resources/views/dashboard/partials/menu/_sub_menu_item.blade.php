@if(isset($permissionName))
	<a href = "{{ $route }}" id = "{{ $id }}" class = "sub-link <?php if(isset($activeLink)) { if($activeLink == $activeLinkName) { echo 'active-link'; } } ?>">
		<i class = "fa {{ $icon }}"></i> &nbsp; {{ $displayName }}
	</a>
@endif