<table class = "view-table">

  <tr>

    @foreach($cols as $col)
      <th>{{$col}}</th>
    @endforeach

    @if(isset($foreign))
      <?php

        foreach($foreign as $f) {

          echo "<th>".$f['name']."</th>";

        }

       ?>
    @endif

    @foreach($actions as $action)
      <th></th>
    @endforeach

  </tr>


  @foreach($data as $d)

  <tr>

    @foreach($cols as $col)
      <?php
        $val = str_replace(' ', '_', strtolower($col));
      ?>
      <td>{{ $d-> $val }}</td>
    @endforeach

    @if(isset($foreign))
      <?php

        foreach($foreign as $f) {

          echo "<td>".$f['model']::find($d->$f['key'])->$f['property']."</td>";

        }

       ?>
    @endif

    @foreach($actions as $action)

        <td>

          @if($action == 'delete')
            <a href = '#' title = 'delete' ><i class='fa fa-trash delete_btn'></i></a>
            <div class = 'hidden_question'> Are You sure you want to delete?
              <a href = '/{{ strtolower($route) }}/{{ $action }}/{{ strtolower($d->id) }}'><button class = 'mini_btn confirm_delete'>yes</button></a>
              <button class = 'mini_btn cancel_delete'>no</button>
            </div>
            </a>
          @else
            <a href = '/{{ strtolower($route) }}/{{ $action }}/{{ strtolower($d->id) }}' title = {{$action}} >
          @endif


          @if($action == 'view')
            <i class='fa fa-eye'></i>
          @endif

          @if($action == 'edit')
            <i class='fa fa-pencil'></i>
          @endif


          </a>

        </td>

    @endforeach

  </tr>

  @endforeach

</table>

<div class = "page_links">	{{ $data -> render() }} </div>
