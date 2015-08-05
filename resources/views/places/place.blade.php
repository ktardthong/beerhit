@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
    <style>
    body{
        background: #F1F1F1;
    }
    </style>
    <div class="container-fluid">
        <div class="container padding_box shadow" style="margin-top: 20px;background: #ffffff" align="center">
            <img src="https://graph.facebook.com/{{$place_info->place_id}}/picture?type=small" class="img-circle">

            <div class="page-header">
                <h1>{!! $place_info->name !!}</h1>
                <h5>{!! $place_info->street !!} {!! $place_info->city !!} {!! $place_info->state !!}</h5>
            </div>

            <div class="page-header" align="center">
                <ul class="nav nav-pills">
                  <li role="presentation">
                    <a href="/checkin/place/{{ $place_info->name }}-{{ $place_info->place_id }}">
                                                          <i class="fa fa-check-circle-o fa-2x"></i> Checkin
                                                      </a></li>
                  <li role="presentation"><a href="#"><i class="fa fa-hand-o-up fa-2x"></i> Recomend </a></li>
                  <li role="presentation"><a href="#"><i class="fa fa-users fa-2x"></i> Friendly</a></li>
                </ul>
            </div>
        </div>

        <div class="container page-header gray_bg shadow" align="center">
            @foreach($img_here as $img)
                <img src="{!! $img->path !!}" class="img-circle holder" height="100px">
            @endforeach
        </div>

        <div class="container padding_box" style="margin-top: 20px;background: #ffffff">
            <div class="col-xs-12 col-sm-6">

                <h4>8 <small>was here</small> </h4>
                @foreach($been_here as $d)
                    <img src="{!! $d->avatar !!}" class="img-circle" height="50px">
                @endforeach



                <h2>most drink here</h2>
                    @foreach($drink_here as $d)
                        <div class="">
                            {!! \App\Beer::beerLogo($d->logo) !!}
                            <a href="#">{!! \GlobalUrl::beer_url($d->slug,$d->beer) !!} <span class="badge">42</span></a>
                        </div>
                    @endforeach
            </div>
            <div class="col-xs-12 col-sm-6">
                <img src="https://maps.googleapis.com/maps/api/staticmap?center={{$place_info->latitude}},{{$place_info->longitude}}&zoom=16&size=280x140&markers=color:blue%7Clabel:S%7C{{$place_info->latitude}},{{$place_info->longitude}}">
            </div>
        </div>
    </div>
@stop