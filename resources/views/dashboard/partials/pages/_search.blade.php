@extends('dashboard.layout')

@section('content')

    <div class = "search-wrapper">

      <input type = "text" name = "search" onkeyup="handleSearch('{{$searchViewData['searchRouteName']}}','{{$searchViewData['searchRootRoute']}}','{{$searchViewData['searchCurrentRoute']}}')" class = "form-control form-control-sm"
        placeholder = "{{$searchViewData['searchText']}}"/>

    </div>

    <div class = "result-wrapper">

    </div>


@endsection
