<tr>
	<td>{!! Form::label($name,$displayName) !!}</td>
	<td>
		{!! Form::text($name, null , ['placeholder' => $displayName,'class'=>'form-control form-control-sm']) !!}
	</td>
</tr>