@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4&appId=182388651773669";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

@section('content')
    <div class="container padding_box" style="margin-top: 20px;background: #ffffff">
        <div class="container-fluid row">
            <div class="col-xs-12 col-sm-8">

                <div class="table-bordered img-rounded row shadow">

                    <div class="clearfix padding_box">

                        <a href="/beer/{!! $beer->slug !!}/edit">edit</a>
                        <div class="container-fluid">
                            <h3 class="pull-right padding_box"
                                style="border-radius: 50px;background: #4B4B4D; color: #E9DDCC; height: 60px; width: 60px;padding-top:10px" >
                                {!! $beer->scores !!}
                                <br>
                                <small><small>{!! $beer->votes !!} votes</small></small>
                            </h3>

                            <h1>
                                {!! \App\Beer::beerLogo($beer->logo) !!}
                                {{$beer->beer}}
                            </h1>

                        </div>

                        {{-- Commend Beer--}}
                        <ul class="nav nav-pills pull-left">
                            <li role="presentation">
                                <a href="#" class="" onclick="commend({!! $beer->id !!},1,'drink_stat')">
                                    Drink it
                                    <span class="badge" id="drink_stat">
                                        {!! $beer->drink !!}</span>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#" class="" onclick="commend({!! $beer->id !!},2,'like_stat')">
                                    Like it
                                    <span class="badge" id="like_stat">{!! $beer->like !!}</span> </a>
                            </li>
                            <li role="presentation">
                                <a href="#" class="" onclick="commend({!! $beer->id !!},3,'want_stat')">
                                    Want it
                                    <span class="badge" id="want_stat">{!! $beer->want !!}</span> </a>
                            </li>

                            @if(Auth::user())
                                <li role="presentation">
                                    <a href="/beer/{{$beer->slug}}/upload">
                                                              <i class="fa fa-picture-o fa-2x"></i>
                                                                Upload image
                                                        </a>
                                </li>
                                <li role="presentation">
                                    <a href="/checkin/drink/{{$beer->slug}}"><i class="fa fa-check-circle-o fa-2x"></i> Check in</a>
                                </li>
                            @endif
                        </ul>

                        <script>
                            function commend(beer_id,commend_id,container)
                            {
                                $.ajax({
                                        headers:
                                        {
                                            'X-CSRF-Token': $('input[name="_token"]').val()
                                        },
                                        method: "POST",
                                        url: "/ajax/commendBeer",
                                        data: {
                                                beer: beer_id,
                                                commend: commend_id,
                                              }
                                        })
                                        .done(function( msg ) {
                                           $('#'+container).html(msg)    ;
                                        });
                            }
                        </script>
                    </div>

                <div class="gray_bg padding_box">
                    <small>
                    <dl class="dl-horizontal">
                      <dt>Style:</dt>
                      <dd>{!! $beer->style !!}</dd>
                      <dt>ABV :</dt>
                      <dd>{!! $beer->abv !!}</dd>
                      <dt>Brewery :</dt>
                      <dd>{!! $beer->b_name !!} &bull; {!! $beer->b_city !!}</dd>
                    </dl>
                    </small>
                </div>
            </div>

            {{-- All that sharing--}}
            <div class="fb-share-button padding_box"
                                                 data-href="https://developers.facebook.com/docs/plugins/"
                                                 data-layout="button"></div>


            <div class="row" style="margin: 20px 0px 20px 0px">
                @if(!empty($beerImg))
                    @foreach($beerImg as $bimg)
                        {!! \GlobalUrl::beer_image($beer->beer,$bimg->img_id,
                                "<img src=\"$bimg->path\" class='col-xs-3'>") !!}
                    @endforeach
                @endif
            </div>

                {{--Login users only--}}
                @if(Auth::user())
                    {{-- Beer Rating --}}
                    <div class="row" style="margin-top: 10px">
                            <div class="media">
                                <div class="media-left">
                                {!!\GlobalUrl::user_profile_pic(null, Auth::user()->avatar,'img-circle') !!}
                                </div>
                                <div class="media-body">
                                {!! \UserBeerHelper::beerRatingEdit($beer->id); !!}
                                </div>
                            </div>
                    </div>

                @endif



                <div
                     style="margin-top: 20px;"
                     class="row padding_box table-bordered img-rounded">

                    <h3>
                        <strong><i class="fa fa-comments fa-1x"></i>Comments</strong>
                    </h3>
                    <div id="beerRating_container">
                        @if(empty(\UserBeerHelper::beerRatingContainer($beer->id)))
                            No comments yet
                        @else
                            {!! \UserBeerHelper::beerRatingContainer($beer->id) !!}
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-4">
                @if(!empty($beer_checkin))
                    <h3>Available at</h3>
                    @foreach($beer_checkin as $c)
                            <div class="media">
                              <div class="media-left">
                                <img src="https://graph.facebook.com/{!!$c->place_id!!}/picture?type=normal" class="img-circle" width="50px">
                              </div>
                              <div class="media-body">
                                <h4 class="media-heading">{!! \GlobalUrl::place_url($c) !!}</h4>
                                {!! $c->city !!}
                              </div>
                            </div>
                    @endforeach
                @endif

                <h3>Similar Beer</h3>
                @foreach($similar_style as $s)
                    <div class="padding_box">
                        {!! \GlobalUrl::beer_url($s->slug,$s->beer) !!}
                    </div>
                @endforeach

                <hr>

                <h3><small>from </small> {!! $beer->b_name !!} </h3>
                @foreach($from_brewery as $b)
                    <div class="padding_box">
                        {!! \GlobalUrl::beer_url($b->slug,$b->beer) !!}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop