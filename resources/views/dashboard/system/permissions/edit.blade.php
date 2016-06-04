@extends('dashboard.layout')

@section('content')

    @include('dashboard.partials.pages._edit',$editViewData = [
        'model' => $model,
        'urlPath' => 'system/permissions',
        'partialsPath'   => 'dashboard.system.permissions.partials._form'
    ])

@endsection
