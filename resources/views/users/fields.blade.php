<!-- Title Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Body Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('email', 'E-mail:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Published Field -->
<div class="form-group col-sm-12">
    {!! Form::label('password', 'Password:') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}

</div>

<div class="form-group col-sm-12">
    {!! Form::label('roles', 'Roles:') !!}
    {!! Form::select('roles', $roles->pluck('name','id'), isset($user) ? $user->roles->pluck('id')->toArray() : null, ['class' => 'form-control']) !!}

</div>

@if(isset($user))
    <div class="form-group col-sm-12">
    @include('partials._permissions', ['closed' => true, 'model' => $user ])
            </div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
