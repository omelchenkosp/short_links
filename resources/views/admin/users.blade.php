@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tables</div>
                <div class="card-body">
                    <span><a href="{{ url('/').'/admin/users' }}">Users</a></span>
                    <span><a href="{{ url('/').'/admin/links' }}">Links</a></span>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Enabled</th>
                            <th></th>
                        </tr>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
{{--                                    {{ Form::checkbox('name', 'value', true) }}--}}
                                    @if(!$user->deleted_at)
                                        {!! Form::open(['url'=>route('admin.user.disable',['id'=>$user->id]), 'class'=>'form-horizontal','method' => 'POST']) !!}
                                        {{ method_field('DELETE') }}
                                        {!! Form::button('Disable',['class'=>'btn btn-danger','type'=>'submit']) !!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['url'=>route('admin.user.enable',['id'=>$user->id]), 'class'=>'form-horizontal','method' => 'POST']) !!}
                                        {{--{{ method_field('DELETE') }}--}}
                                        {{ csrf_field() }}
                                        {!! Form::button('Enable',['class'=>'btn btn-primary','type'=>'submit']) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                                <td>
                                    {!! Form::open(['url'=>route('admin.user.delete',['id'=>$user->id]), 'class'=>'form-horizontal','method' => 'POST']) !!}
                                    {{ method_field('DELETE') }}
                                    {!! Form::button('Delete',['class'=>'btn btn-danger','type'=>'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    {{ $users->links() }}
</div>
@endsection