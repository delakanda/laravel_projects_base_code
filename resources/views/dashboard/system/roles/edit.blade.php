@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._edit',[
        'model' => $role,
        'urlPath' => 'system/roles',
        'partialsPath'   => 'dashboard.system.roles.partials._form'
    ])

@endsection
