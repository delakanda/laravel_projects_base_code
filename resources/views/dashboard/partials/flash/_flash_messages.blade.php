@if(isset($warningMessage))
  	<div id = "warning-box">
    	{{ $warningMessage }}
  	</div>
@endif

@if(Session::has('message'))
	<div id = "session-box">
		{{ Session::get('message') }}
	</div>
@endif