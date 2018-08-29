@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">User Profile</div>
                <div class="card-body">
                    {{ Html::ul($errors->all()) }}
                    {{ Form::model($user, array('route' => array('admin.user.update', $user['id']), 'role'=>'form', 'method' => 'PUT', 'enctype'=>'multipart/form-data')) }}

                        <div class="form-group">
                            {!! Form::label('name','Name:') !!}
                            {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Enter ...'])!!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('password','Password:') !!}
                            {!! Form::text('password','',['class' => 'form-control','placeholder'=>'Enter ...'])!!}
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <br>
        </div>
    </div>

</div>
@endsection
