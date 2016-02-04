<table class = "form-element full">

  @include('dashboard.partials.form._text_field',['name' => 'permission_name','displayName' => 'Permission Name'])

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

  @include('dashboard.partials.form._button_field',['buttonText' => $submitButtonText])

</table>
