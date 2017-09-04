@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Roles</h1>
        @can('add_roles')
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#roleModal" style="margin-top: -10px;margin-bottom: 5px" href="#">Add New</a>
        </h1>
        @endcan
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @forelse ($roles as $role)
                    {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b']) !!}

                    @if($role->sort === 1)
                        @include('partials._permissions', [
                                      'title' => $role->name .' Permissions',
                                      'options' => ['disabled'] ])
                    @else
                        @include('partials._permissions', [
                                      'title' => $role->name .' Permissions',
                                      'model' => $role ])
                        @can('edit_roles')
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        @endcan
                    @endif

                    {!! Form::close() !!}
                    <br />
                @empty
                    <p>No Roles defined, please run <code>php artisan db:seed</code> to seed some dummy data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel">
        <div class="modal-dialog" role="document">
            {!! Form::open(['method' => 'post']) !!}

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="roleModalLabel">Role</h4>
                </div>
                <div class="modal-body">
                    <!-- name Form Input -->
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Role Name']) !!}
                        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('name')) has-error @endif">
                        {!! Form::label('level', 'Name') !!}
                        {!! Form::text('sort', null, ['class' => 'form-control', 'placeholder' => 'Level']) !!}
                        @if ($errors->has('sort')) <p class="help-block">{{ $errors->first('sort') }}</p> @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <!-- Submit Form Button -->
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {
            @if(count($errors)>0)
                $('#roleModal').modal('show');
            @endif
        });
    </script>
@endsection