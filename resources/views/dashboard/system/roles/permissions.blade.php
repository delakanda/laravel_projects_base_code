@extends('dashboard.layout')

@section('content')

{!! Form::open(['method' => 'POST','action' => ['System\RoleController@savePermissions',$role->id] ] ) !!}

<div class = "card half">

  <h3>Permissions for role : {{ $role->role_name }}</h3>

  <table class = "details-table">
    <tr><td>Select All</td><td> <input type = "checkbox" id = "check-all" /></td></tr>
  </table>

  @foreach($permissions_parents as $parent)

    <div>
    <h3 class = "permission-header">{{$parent}}</h3>

    <table class = "details-table permission-table">

    @foreach($models as $model)

      @foreach($model::getPermissions() as $permission)

        @if($parent == explode("_",$permission)[0])

          <?php
            $checkValue = null;
            foreach($roles_permissions as $roles_permission)
            {
              if($roles_permission->permission_name == $permission)
              {
                $checkValue = "checked";
              }
            }
          ?>
            <tr><td> {{ explode("_",$permission)[2] }} {{ explode("_",$permission)[3] }} {{ explode("_",$permission)[1] }}</td> <td><input type = "checkbox" name = "{{$permission."_check"}}" value = "{{$permission}}" @if(isset($checkValue)) checked @endif /></td></tr>

        @endif

      @endforeach

    @endforeach
    </table>
    </div>
  @endforeach

  <br/><br/>


{!! Form::submit("Save", array('class' => 'btn btn-primary')) !!}

{!! Form::close() !!}

</div>

@endsection
