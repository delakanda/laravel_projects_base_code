@extends('dashboard.layout')

@section('content')

  @include('dashboard.partials._view_all', array
    (
      'cols' => $colArray,

      'data' => $mainData,

      'route' => $route,

      'permission_prefix' => $permPrefix,

      'foreign' => $foreignArray,

      'actions' => $actionsArray

    )
  )

@endsection
