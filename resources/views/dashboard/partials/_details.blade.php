<div class = "card @if(isset($size)) {{ $size }} @else half @endif">

  @if(isset($data->image_name))
    <div class = "details-image">
      <img src="/uploads/{{$data->image_name}}" />
    </div>
  @endif

  @if(isset($data->picture_name))
    <div class = "details-image">
      <img src="/uploads/{{$data->picture_name}}" />
    </div>
  @endif

  <table class = "details-table">

    @foreach($properties as $property)
      @if(isset($data -> {$property['property']}))
        <tr>
          <th> {{ $property['name'] }} </th>
          @if(isset($property['symbolic']))
              <td> {{ $property['symbolic'][$data -> {$property['property']}] }} </td>
          @else
              <td> {{ $data -> {$property['property']} }} </td>
          @endif
        </tr>
      @endif
    @endforeach

    @if(isset($foreign))
      @foreach($foreign as $f)
      @if(isset($f['model']::find($data->{$f['key']})->{$f['property']}))
        <tr>
          <th> {{ $f['name'] }} </th><td> {{ $f['model']::find($data->{$f['key']})->{$f['property']} }}</td>
        </tr>
      @endif
    @endforeach

    @endif

  </table>
</div>
