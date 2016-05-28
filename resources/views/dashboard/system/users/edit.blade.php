@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._edit',[
        'model' => $user,
        'urlPath' => 'system/users',
        'partialsPath'   => 'dashboard.system.users.partials._form',
        'files' => true
    ])

@endsection
