@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._add',$addViewData = [
        'controllerPath' => 'System\PermissionController',
        'partialsPath'   => 'dashboard.system.permissions.partials._form'
    ])

@endsection
