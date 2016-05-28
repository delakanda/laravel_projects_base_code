@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._edit',[
        'model' => $permission,
        'urlPath' => 'system/permissions',
        'partialsPath'   => 'dashboard.system.permissions.partials._form'
    ])

@endsection
