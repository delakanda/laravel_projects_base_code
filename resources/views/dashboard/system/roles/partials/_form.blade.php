<table class = "form-element full">

  @include('dashboard.partials.form._text_field',['name' => 'role_name','displayName' => 'Role Name'])  

  @include('dashboard.partials.form._button_field',['buttonText' => $submitButtonText])

</table>
