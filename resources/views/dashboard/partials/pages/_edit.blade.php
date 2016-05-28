<div class = "card {{ $cardSize or 'half' }}">

  @include('errors.error_list')

  {!! Form::model($model, ['method' => 'PATCH','url' => [$urlPath,$model->id] , 'files' => (isset($files) ? true : false) ] ) !!}

    @include($partialsPath,['submitButtonText'=>'Update','context'=>'update'])

  {!! Form::close() !!}

</div>