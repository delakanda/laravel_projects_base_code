@extends('dashboard.layout')

@section('content')

    <div class = "card {{ $cardSize or 'half' }}">

      @include('errors.error_list')

      {!! Form::model($model, ['method' => 'PATCH','url' => [$editViewData['urlPath'],$model->id] , 'files' => (isset($editViewData['files']) ? true : false) ] ) !!}

        @include($editViewData['partialsPath'],['submitButtonText'=>'Update','context'=>'update'])

      {!! Form::close() !!}

    </div>

@endsection