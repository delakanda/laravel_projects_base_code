@if(isset($data))
  <tr>
    <td>{!! Form::label($name,$displayName) !!}</td>

    @if(isset($datas_data))
    <td>
        {!! Form::select($name, array( $datas_data -> {$primary_key} => $datas_data -> {$display_value} ) +  $data, $datas_data, array('class' => 'form-control') ) !!}
    </td>
    @else
    <td>
      
      {!! Form::select($name, $data,null,array('class' => 'form-control') ) !!}

    </td>
    @endif
  </tr>
@endif