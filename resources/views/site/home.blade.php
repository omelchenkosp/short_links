@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Links <a href="/add"><button type="button" class="btn btn-primary float-right">Add new link</button></a></div>
                <div class="card-body">
                    <table class="links_table">
                        <tr>
                            <th>Original URL</th>
                            <th>Short URL</th>
                            <th>Amount of Clicks</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Analytics</th>
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
