<table class = "form-element full">

  @include('dashboard.partials.form._text_field',['name' => 'first_name','displayName' => 'First Name'])

  @include('dashboard.partials.form._text_field',['name' => 'last_name','displayName' => 'Last Name'])

  @include('dashboard.partials.form._text_field',['name' => 'username','displayName' => 'Username'])

  @include('dashboard.partials.form._text_field',['name' => 'email','displayName' => 'Email'])

  @include('dashboard.partials.form._select_field',
    [
      'name' => 'role_id',
      'displayName' => 'Roles',
      'data' => $roles,
      'datas_data' => ( isset($users_role) ? $users_role : null ),
      'primary_key' => 'id',
      'display_value' => 'role_name'
    ]
  )

  @include('dashboard.partials.form._image_field',
    [
      'context' => $context,
      'name' => 'image_name',
      'displayName' => 'Image (optional)',
      'data' => ( isset($model) ? $model : null ),
      'imageColumnName' => 'image_name'
    ]
  )

  @include('dashboard.partials.form._button_field',['buttonText' => $submitButtonText])

</table>
