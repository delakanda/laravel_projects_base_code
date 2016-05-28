@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._add',[
        'controllerPath' => 'System\RoleController',
        'partialsPath'   => 'dashboard.system.roles.partials._form'
    ])
    
@endsection
