@if(isset($context))
  @if($context == 'add')
  <tr>
    <td>{!! Form::label($name,$displayName) !!}</td>
    <td>{!! Form::file($name,['class' => 'btn btn-default btn-file']) !!}</td>
  </tr>
  @endif

  @if($context == 'update')
    <tr>
      <td>{!! Form::label($name,$displayName) !!}</td>
      <td>
        {!! Form::file($name, ['class' => 'btn btn-default btn-file']) !!}
        @if(isset($data->{$imageColumnName}))
          <div id = "small-image">
            <img src = "/uploads/{{ $data->{$imageColumnName} }}" />
          </div>
          <input type = "checkbox" name = "clear_check" value = "yes" /> Clear Image (<span class = "small-text">Check to delete image</span>)
        @endif
      </td>
    </tr>
  @endif
@endif