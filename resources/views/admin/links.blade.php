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
                <div class="card-header">Links</div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Original URL</th>
                            <th>Short URL</th>
                            <th>Amount of Clicks</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Analytics</th>
                            <th>Edit</th>
                            <th></th>
                        </tr>
                        @foreach ($links as $link)
                            <tr>
                                <td><a href="{{ $link->url_origin }}">{{ $link->url_origin }}</a></td>
                                <td><a href="{{ url('/').'/'.$link->url_short }}">{{ url('/').'/'.$link->url_short }}</a></td>
                                <td>{{ $link->visits->count() }}</td>
                                <td>{{ date('d.m.Y H:m', $link->date_start) }}</td>
                                @if ($link->date_end)
                                    <td>{{ date('d.m.Y H:m', $link->date_end) }}</td>
                                @else
                                    <td></td>
                                @endif
                                @if($link->visits->count() > 0)
                                    <td>{!! Html::link(route('site.link',['link_id'=>$link->id]), 'view') !!}</td>
                                @else
                                    <td>no data</td>
                                @endif
                                <td>
                                    {!! Form::open(['url'=>route('admin.link.edit',['id'=>$link->id]), 'class'=>'form-horizontal','method' => 'GET']) !!}
                                    {!! Form::button('Edit',['class'=>'btn btn-primary','type'=>'submit']) !!}
                                    {!! Form::close() !!}
                                </td>
                                <td>
                                    {!! Form::open(['url'=>route('admin.link.destroy',['id'=>$link->id]), 'class'=>'form-horizontal','method' => 'POST']) !!}
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
    {{ $links->links() }}
</div>
@endsection