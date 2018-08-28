@extends('layouts.site')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">New Link</div>

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
                    <span>Origin URL: </span>
                    <a href="{{ $link['url_origin'] }}">{{ $link['url_origin'] }}</a>
                    <br>
                    <span>Short URL: </span>
                    <a href="{{ url('/').'/'.$link['url_short'] }}">{{ url('/').'/'.$link['url_short'] }}</a>
                    <br>
                    <span>Analytics page: </span>
                    <a href="{{ url('/').'/links/'.$link['id'] }}">{{ url('/').'/links/'.$link['id'] }}</a>

                    {!! Form::open(['url' => route('profile.link.update', ['id'=>$link['id']]), 'role'=>'form', 'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
                        <div class="form-group clearfix">
                            <br>
                            <p>You can change link Short URL and lifetime:</p>
                            <div id="short_url_wrapper">
                                <span class="float-left">{{ url('/').'/' }}</span>
                                {!! Form::text('url_short', old('url_short'), ['class' => 'form-control float-left', 'placeholder'=>'Enter ...'])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('lifetime','Lifetime:') !!}
                            {!! Form::select('lifetime', ['f' => 'Forever', 'm' => 'Month', 'w' => 'Week', 'm' => 'Month', 'd' => 'Number of days', 'h' => 'Number of hours'], ['class' => 'form-control']) !!}
                        </div>
                        <div style="display:none" id="lifetime_val" class="form-group">
{{--                            {!! Form::label('lifetime_val', 'time:') !!}--}}
                            {!! Form::text('lifetime_val', old('lifetime_val'), ['class' => 'form-control', 'placeholder'=>'Enter ...'])!!}
                        </div>
                        {!! Form::hidden('link_time', old('link_time'), ['id' => 'link_time', 'class' => 'form-control', 'placeholder'=>'Enter ...'])!!}

                        <button id="update" type="button" class="btn btn-primary">Update</button>
                        <a href="{{ url('/').'/links/'.$link['id'] }}"><button id="shorten" type="button" class="btn btn-primary">Analytics page</button></a>
                    {!! Form::close() !!}

                </div>

            </div>

        </div>
    </div>
</div>
@endsection

<script>
    window.onload = function() {
        $('select[name="lifetime"]').on('change', function () {
            if($(this).val() == 'd' || $(this).val() == 'h') {
                $('#lifetime_val').show();
            } else {
                $('#lifetime_val input').val("");
                $('#lifetime_val').hide();
            }
        });

        var life_seconds = 0;
        $('#update').on('click', function(event){
            event.preventDefault();

            switch($('select[name="lifetime"]').val()) {
                case "m":
                    life_seconds = 31 * 24 * 3600;
                    break;
                case "w":
                    life_seconds = 7 * 24 * 3600;
                    break;
                case "d":
                    if ($('#lifetime_val input').val()) {
                        life_seconds = $('#lifetime_val input').val() * 24 * 3600;
                    }
                    break;
                case "h":
                    if ($('#lifetime_val input').val()) {
                        life_seconds = $('#lifetime_val input').val() * 3600;
                    }
                    break;
            }
            $('#link_time').val(life_seconds);
            $('.card-body form').submit();
        });
    }
</script>
