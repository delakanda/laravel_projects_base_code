@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._add',[
        'controllerPath' => 'System\UserController',
        'partialsPath'   => 'dashboard.system.users.partials._form',
        'files' => true
    ])

@endsection
