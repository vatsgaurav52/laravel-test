@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>B2C Purchase Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td> @foreach ($user->roles as $role)
                                            {{ $role->name }}
                                         @endforeach
                                    </td>
                                    <td>{{$user->card}}</td>
                                    <td><form method="post" action="{{route('admin.refund')}}" onsubmit="return confirm('Are you sure you want to delete this subscription?');" >
                                        {{ csrf_field() }}
                                        <input type="hidden" name="stripe_id" value="{{$user->stripe_id}}"/>
                                        <button type="submit" id="deleteBtn" class="btn btn-danger pull-right">Cancel</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
