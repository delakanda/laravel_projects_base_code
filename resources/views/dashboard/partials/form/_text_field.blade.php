<tr>
	<td>{!! Form::label($name,$displayName) !!}</td>
	<td>
		{!! Form::text($name, null , ['placeholder' => $displayName,'class'=>'form-control']) !!}
	</td>
</tr>