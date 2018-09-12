@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! link_to_route(config('quickadmin.route') . 'users.create', 'Add new', [], ['class' => 'btn btn-success']) !!}</p>

    @if($users->count() > 0)
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">Users list</div>
            </div>
            <div class="portlet-body table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <?php
                                $role = $user->role_id ? $roles[$user->role_id] : '';
                            ?>
                            <td>{{ $role }}</td>
                            <td>{{ $user->type }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                {!! htmlspecialchars_decode(link_to_route('users.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$user->id], ['class' => 'btn btn-xs btn-info'])) !!}
                                {!! Form::open(['style' => 'display: inline-block;', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'' . 'Are you sure?' . '\');',  'route' => ['users.destroy', $user->id]]) !!}
                                {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        No entries found
    @endif

@endsection