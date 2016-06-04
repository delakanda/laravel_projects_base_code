@extends('dashboard.layout')

@section('content')

    <div class = "card {{ $addViewData['cardSize'] or 'half' }}">

      @include('errors.error_list')

      {!! Form::open(['method' => 'POST','action' => $addViewData['controllerPath'].'@store','files' => (isset($addViewData['files']) ? true : false) ] ) !!}

        @include($addViewData['partialsPath'],['submitButtonText'=>'Save','context'=>'add'])

      {!! Form::close() !!}

    </div>

@endsection
