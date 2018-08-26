@extends('layouts.site')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add new Link</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {!! Form::open(['url' => route('site.store'), 'role'=>'form', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
                        <div class="form-group">
                            {!! Form::label('url_origin', 'Original URL:') !!}
                            {!! Form::text('url_origin', old('url_origin'), ['class' => 'form-control', 'placeholder'=>'Enter ...'])!!}
                        </div>
                        <button id="shorten" type="sumbit" class="btn btn-primary">Shorten</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
