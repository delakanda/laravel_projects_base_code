@extends('dashboard.layout')

@section('content')

    <div class = "search-wrapper">

      <input type = "text" name = "search" onkeyup="handleSearch('permission_search','system','permissions')" class = "form-control form-control-sm"
        placeholder = "Search Permissions by Name"/>

    </div>

    <div class = "result-wrapper">

    </div>


@endsection
