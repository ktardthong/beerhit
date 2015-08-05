@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
	<div class="container">
        <div align="center">
            {!! \App\Beer::beerLogo($beer->logo) !!}
            <h1>{!! \GlobalUrl::beer_url($beer->slug,$beer->beer) !!}   </h1>
        </div>
        @foreach($beerImg as $img)
            {{--$path = $img->path;--}}
            {{--$content = "<img src="$path" class="img-responsive">";--}}

            <div class="col-xs-4 table-bordered padding_box">
                {!! \GlobalUrl::beer_image($beer->beer,$img->img_id,"<img src=\"$img->path\" class=\"img-responsive\">") !!}
                <p>
                    Cheers!
                </p>
            </div>
        @endforeach
    </div>
@stop

