<div class = "card {{ $cardSize or 'half' }}">

  @include('errors.error_list')

  {!! Form::open(['method' => 'POST','action' => $controllerPath.'@store','files' => (isset($files) ? true : false) ] ) !!}

    @include($partialsPath,['submitButtonText'=>'Save','context'=>'add'])

  {!! Form::close() !!}

</div>