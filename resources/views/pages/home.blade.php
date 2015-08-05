@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
		<div class="container-fluid">

		    <div class="alert alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Warning!</strong> You must be legal to drink in your country.
            </div>

		    {{--<div class="">--}}
                {{--<div class="row jumbotron" style="background-image: url('/img/beerhit_cover_5.jpg');--}}
                                                  {{--background-size: cover;--}}
                                                  {{--height: 420px;">--}}
                  {{--<h1>Come in</h1>--}}
                  {{--<p>test</p>--}}
                  {{--<p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a></p>--}}
                {{--</div>--}}
		    {{--</div>--}}


            <div class="container">
                @if(!empty($user_friends))
                    @foreach($user_friends as $u)
                         {!! \GlobalUrl::user_profile_pic($u->username, $u->avatar,'img-circle') !!}
                    @endforeach
                @endif;

                <div class="padding_box">
                    <h1>Rate and Share the Beer's Moments <small>yes, that moments</small></h1>
                    <p>
                        Beerhit is a free and simple way to share your beer and passion with other people.
                        <br>
                        <br>
                        Take a picture, then customize it with filters and creative tools.
                        Post it on Beerhit and share instantly on Facebook.
                        Find people to follow based on things you’re into, and be part of an inspirational community.
                    </p>
                </div>

            </div>

            <div class="">
                <div class="container padding_box">
                    <div class="col-xs-12 col-sm-6">
                        <h2>Recently drink</h2>

                        @foreach($recent_drink as $r)
                            <div class="media">
                              <div class="media-left">
                                <a href="#">
                                  {!! \App\Beer::beerLogo($r->logo) !!}
                                </a>
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">{!! \GlobalUrl::beer_url($r->slug,$r->beer) !!}</h4>
                                {!! \UserBeerHelper::humanTime($r->created_at) !!}
                                {!! $r->scores !!}
                              </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h2>
                            <i class="fa fa-trophy fa-1x"></i>
                            Highest Rated
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row" align="center">
                <div class="container">
                    <div class="col-xs-12 col-sm-6">
                        <h2>
                            <i class="fa fa-picture-o fa-1x"></i>
                            Recently share
                        </h2>
                        @foreach($beer_img as $img)
                            {!! \GlobalUrl::beer_image($img->beer,$img->img_id,"<img src=\"$img->path\" class='col-xs-3'>") !!}
                        @endforeach
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        @foreach($recent_checkin as $r)
                            <div class="media">
                              <div class="media-left">
                                  <img src="https://graph.facebook.com/{!! $r->place_id !!}/picture?type=small" class="img-circle">
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">{!! \GlobalUrl::place_url($r) !!}</h4>
                                {!! $r->city !!} {!! $r->country !!}
                              </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


		</div>
@stop