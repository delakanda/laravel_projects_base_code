@extends('dashboard.layout')

@section('content')

<div class = "card half">

  @include('errors.error_list')

  {!! Form::model($user, ['method' => 'POST','url' => ['dashboard/profile/save'], 'files'=>true ] ) !!}

    <table class = "form-element full">

      @include('dashboard.partials.form._text_field',['name' => 'first_name','displayName' => 'First Name'])

      @include('dashboard.partials.form._text_field',['name' => 'last_name','displayName' => 'Last Name'])

      @include('dashboard.partials.form._text_field',['name' => 'username','displayName' => 'Username'])

      @include('dashboard.partials.form._text_field',['name' => 'email','displayName' => 'Email'])

      @include('dashboard.partials.form._password_field',['name' => 'password','displayName' => 'Password','notice' => 'Leave password fields blank if you do not wish to update it'])

      @include('dashboard.partials.form._password_field',['name' => 'confirm_password','displayName' => 'Confirm Password'])

      @include('dashboard.partials.form._image_field',
        [
          'context' => "update",
          'name' => 'image_name',
          'displayName' => 'Image (optional)',
          'data' => ( isset($user) ? $user : null ),
          'imageColumnName' => 'image_name'
        ]
      )
<!-- 
      <tr>
        <td>{!! Form::label("image_name","Image (optional)") !!}</td>
        <td>
          {!! Form::file("image_name",['class' => 'btn btn-default btn-file']) !!}
          @if(isset($user->image_name))
            <div id = "small-image">
              <img src = "/uploads/{{$user->image_name}}" />
            </div>
            <input type = "checkbox" name = "clear_check" value = "yes" /> Clear Image (<span class = "small-text">Check to delete image</span>)
          @endif
        </td>
      </tr> -->


      <tr>
        <td colspan="2" align="right">{!! Form::submit("Save", array('class' => 'btn btn-primary')) !!}</td>
      </tr>

    </table>

    {!! Form::close() !!}

  </div>


@endsection
