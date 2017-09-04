<table class="table table-responsive" id="users-table">
    <thead>
        <th>Name</th>
        <th>E-mail</th>
        <th>Role</th>
        <th>Created Date</th>
        @can('edit_users', 'delete_users')
        <th colspan="3">Action</th>
        @endcan
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{!! $user->name !!}</td>
            <td>{!! $user->email !!}</td>
            <td>@foreach($user->roles->pluck('name') as $name)
                    {!! $name !!}<br />
                @endforeach
            </td>
            <td>{!! $user->created_at !!}</td>
            @can('edit_users', 'delete_users')
            <td>
                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                   <!-- <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>-->
                    <a href="{!! route('users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            @endcan
        </tr>
    @endforeach
    </tbody>
</table>