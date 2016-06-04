@extends('dashboard.layout')

@section('content')


    @include('dashboard.partials._details', array
    (
        "data" => $model,

        "properties" => $viewViewData['propertiesArray'],

        'foreign' => (isset($viewViewData['foreignArray']) ? $viewViewData['foreignArray'] : null)
    )
)

@endsection
