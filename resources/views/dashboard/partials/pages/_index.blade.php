@extends('dashboard.layout')

@section('content')

  @include('dashboard.partials._view_all', array
    (
      'cols' => $indexViewData['colArray'],

      'data' => $paginationData,

      'route' => $indexViewData['route'],

      'permission_prefix' => $indexViewData['permPrefix'],

      'foreign' => (isset($indexViewData['foreignArray']) ? $indexViewData['foreignArray'] : null),

      'actions' => $indexViewData['actionsArray'],

      'extraActions' => (isset($indexViewData['extraActions']) ? $indexViewData['extraActions'] : null)

    )
  )

@endsection
