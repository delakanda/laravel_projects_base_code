<table class = "form-element full">

  <tr>
    <td>{!! Form::label("rank_code","Rank Code*") !!}</td>
    <td>{!! Form::text("rank_code", null , ['placeholder' => 'Rank Code','class'=>'text-input']) !!}</td>
  </tr>

  <tr>
    <td>{!! Form::label("rank_name","Rank Name*") !!}</td>
    <td>{!! Form::text("rank_name", null , ['placeholder' => 'Rank Name','class'=>'text-input']) !!}</td>
  </tr>

  <tr>
    <td colspan="2" align="right">{!! Form::submit($submitButtonText, array('class' => 'submit-button')) !!}</td>
  </tr>

</table>