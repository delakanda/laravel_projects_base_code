@if(isset($subLinks))
	@foreach($subLinks as $subLink)
		<?php

			$subLinkAccess = null;

			foreach($permissions as $permission)
			{
				if($permission->permission_name == $subLink['permission'])
				{
					$subLinkAccess = 1;
					break;

				}
			}

		?>

		@if(isset($subLinkAccess))
			<a @if(isset($subLink['route'])) href = "{{$subLink['route']}}" @endif>
				<div id = "{{ str_replace(" ","_",strtolower($subLink['title'])) }}" class="btn btn-primary btn-sm mini-link" title = "{{$subLink['title']}}">
					{!! $subLink['icon'] !!}
				</div>
			</a>
		@endif
	@endforeach
@endif