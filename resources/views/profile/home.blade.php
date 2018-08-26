@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">User Profile</div>
                <div class="card-body">
                    {{ Html::ul($errors->all()) }}
                    {{ Form::model($user, array('route' => array('profile.update'), 'role'=>'form', 'method' => 'POST', 'enctype'=>'multipart/form-data')) }}
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
            <div class="card">
                <div class="card-header">User Links</div>
                <div class="card-body">
                    {{--{{ Form::model($user, array('route' => array('profile.update'), 'role'=>'form', 'method' => 'POST', 'enctype'=>'multipart/form-data')) }}--}}

                    {{--{!! Form::close() !!}--}}
                    <table>
                        <tr>
                            <th>Original URL</th>
                            <th>Short URL</th>
                            <th>Amount of Clicks</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Analytics</th>
                            <th></th>
                        </tr>
                        @foreach ($links as $link)
                            <tr>
                                <td><a href="{{ $link->url_origin }}">{{ $link->url_origin }}</a></td>
                                <td><a href="{{ url('/').'/'.$link->url_short }}">{{ url('/').'/'.$link->url_short }}</a></td>
                                <td>{{ $link->visits->count() }}</td>
                                <td>{{ date('d.m.Y', $link->date_start) }}</td>
                                @if ($link->date_end)
                                    <td>{{ date('d.m.Y', $link->date_end) }}</td>
                                @else
                                    <td></td>
                                @endif
                                @if($link->visits->count() > 0)
                                    <td>{!! Html::link(route('site.link',['link_id'=>$link->id]), 'view') !!}</td>
                                @else
                                    <td>no data</td>
                                @endif
                                <td>
                                    {!! Form::open(['url'=>route('profile.link.destroy',['id'=>$link->id]), 'class'=>'form-horizontal','method' => 'POST']) !!}
                                    {{ method_field('DELETE') }}
                                    {!! Form::button('Delete',['class'=>'btn btn-danger','type'=>'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
                <br>
{{--                {{ $links->links() }}--}}
            </div>
        </div>
    </div>

</div>
@endsection
