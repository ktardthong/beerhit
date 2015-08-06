@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')

	<div class="container-fluid">
        <div class="container white_bg">
            <div class="container-fluid">
                <div class="col-xs-4">
                    {!!\GlobalUrl::user_profile_pic(null, $user->avatar,'img-thumbnail',null) !!}
                </div>
                <div class="col-xs-8 pull-left">
                    <h1>
                        {{$user->firstname}} {{$user->lastname}}
                    </h1>

                    <div class="container-fluid">
                        @if(!empty(Auth::User()->id))
                            {{--Check is user is user --}}
                            @if($user_flg == FALSE)
                                <ul class="nav nav-pills pull-left">
                                    <li role="presentation">
                                        <a href="#">Follow</a>
                                    </li>
                                </ul>
                            @else
                                <ul class="nav nav-pills pull-left">
                                    <li role="presentation">
                                        <a href="/profile/{{$user->username}}/edit"><i class="fa fa-user fa-1x"></i> Edit profile</a>
                                    </li>
                                    <li>
                                        <a href="/profile/{{$user->username}}/logout"><i class="fa fa-unlock fa-x"></i> logout</a></li>
                                    </li>
                                </ul>
                            @endif
                        @endif
                    </div>

                    <div class="container-fluid">
                        <ul class="nav nav-pills">
                            <li role="presentation">
                                <strong>{!! $drink_cnt !!}</strong>
                                Drink
                            </li>

                            <li role="presentation">
                                <strong>{!! $drink_cnt !!}</strong> Pics
                            </li>

                            <li role="presentation">
                                <strong>{!! $drink_cnt !!}</strong> Followers
                            </li>

                            <li role="presentation">
                                <strong>{!! $drink_cnt !!}</strong> Review
                            </li>
                        </ul>
                    </div> <!--./container fluid -->
                    @if($user_flg == TRUE)
                        <div class="form-group page-header">
                            <div id="user_container" class="table-bordered padding_box img-rounded" style="border-color: #fefefe">
                                {!! Form::open(array('id' => 'userPost',
                                                             'method' => 'POST',
                                                             'url' => "/userPost_callback",
                                                             'files' => true)) !!}

                                {!! Form::text('beer_search', '' , array('placeholder' => 'What are you drinking?..',
                                                                         'class'=>'form-control',
                                                                         'id'=>'beer_search')) !!}

                                <div id="extra_container">
                                    <ul class="nav nav-pills pull-left">
                                        <li role="presentation">
                                            <span class="fa fa-camera fa-1x">
                                                <input type="file" name="image" class="" id="image_src">
                                            </span>
                                        </li>
                                        <li role="presentation">
                                            <span class="fa fa-map-marker fa-1x">
                                                <a href="#" class="padding_box" id="toggleCheckin">{!! $location->cityName !!}</a>
                                            </span>
                                        </li>
                                        <li role="presentation">
                                            <span class="fa fa-bar-chart fa-1x">
                                                <a href="#" class="padding_box" id="toggleRating"></a>
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                                {!! Form::text('location', isset($place->name)?$place->name:null,
                                                            array(  'placeholder'   =>  'where are you?',
                                                                    'class'         =>  'form-control',
                                                                    'id'            =>  'location'))!!}
                                <div id="location_map"></div>
                                <div id="rating_container">
                                    {!! \UserBeerHelper::beerRatingUser(); !!}
                                </div>
                                {!! Form::hidden('beer_id',null,array('id'=>'beer_id')) !!}

                                {{-- FB INFO--}}
                                {!! Form::hidden('fb_place_id', isset($place->place_id)?$place->place_id:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_place_id')) !!}
                                {!! Form::hidden('fb_place_name', isset($place->name)?$place->name:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_place_name')) !!}
                                {!! Form::hidden('fb_address', isset($place->street)?$place->street:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_address')) !!}
                                {!! Form::hidden('fb_lat', isset($place->latitude)?$place->latitude:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_lat')) !!}
                                {!! Form::hidden('fb_lng', isset($place->longitude)?$place->longitude:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_lng')) !!}
                                {!! Form::hidden('fb_category', isset($place->category)?$place->category:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_category')) !!}
                                {!! Form::hidden('fb_city', isset($place->city)?$place->city:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_city')) !!}
                                {!! Form::hidden('fb_state', isset($place->state)?$place->state:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_state')) !!}
                                {!! Form::hidden('fb_country', isset($place->country)?$place->country:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_country')) !!}
                                {!! Form::hidden('fb_zip', isset($place->zip)?$place->zip:null , array('placeholder'=>'', 'class' => 'form-control','id'=>'fb_zip')) !!}


                                <input type="submit" class="btn btn-default form-control gold_bg" value="Post">

                                {!! Form::close() !!}
                            </div>
                        </div> <!-- ./form-group -->
                    @endif
                </div> <!-- ./LEFT -->
            </div>

            <div class="container-fluid white_bg">
                <div class="col-xs-12 col-sm-8">
                    @if(empty($drink_log))
                        Drink up! your feed will appear here
                    @endif

                    @foreach($drink_log as $dl)
                        <div class="media img-rounded col-xs-12" style="border-bottom: dotted #add8e6 1px;padding-bottom: 8px ">
                          <div class="media-left">
                            <img src="{!! $user->avatar !!}" class="img-circle" width="80px">
                          </div>
                          <div class="media-body">
                            <h5 class="media-heading">
                                <strong>
                                    {!! \UserBeerHelper::type_return_icon($dl->type_id) !!}
                                    <a href="/beer/{!! $dl->slug !!}">{!!  $dl->beer !!}</a>
                                    <small class="pull-right">{!! \UserBeerHelper::humanTime($dl->created_at) !!}</small>
                                </strong>

                                <p class="padding_box">
                                    @if($user_flg == FALSE)
                                        {!! $user->firstname !!}
                                     @else
                                        You
                                     @endif
                                    {{--{!! $dl->img_id !!}--}}
                                    {!! \UserBeerHelper::type_return($dl->type_id,
                                                                        $dl->user_id,
                                                                        $dl->beer_id,
                                                                        null,
                                                                        $dl->place_id,
                                                                        $dl->img_id) !!}</p>
                                @if(!empty($dl->place_id))
                                    <img src="https://graph.facebook.com/{{$dl->place_id}}/picture?type=small" class="img-circle" width="50px">
                                @endif

                                {!! \App\Beer::beerLogo($dl->logo) !!}
                            </h5>

                          </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-xs-12 col-sm-4">
                    <h4>Visited</h4>
                    @If(!empty($user_checkin))
                        @foreach($user_checkin as $c)
                             <div class="padding_box">
                                <img src="https://graph.facebook.com/{{$c->place_id}}/picture?type=small" class="img-circle" width="60px">
                                {!! \GlobalUrl::place_url($c) !!}
                            </div>
                        @endforeach
                    @endif

                    <h4>Uploaded</h4>
                    @If(!empty($drink_uploaded))
                        @foreach($drink_uploaded as $uploaded)
                            <p>
                            {!! \GlobalUrl::beer_image($uploaded->beer,$uploaded->img_id,"<img src=\"$uploaded->path\" class='img-thumbnail img-responsive'>") !!}
                            </p>
                        @endforeach
                    @endif

                    <h4><i class="fa fa-thumbs-o-up fa-1x"></i>Like</h4>
                    @If(!empty($user_like))
                        @foreach($user_like as $l)
                            <img src="{!! $l->logo !!}" width="60px">
                            {!! $l->beer !!}
                        @endforeach
                    @endif
                </div>
            </div>

		</div><!-- ./container -->
	</div>

<script>
  $("#user_rating").hide();
  $("#user_comment").hide();
  $("#confirm_drink_lbl").hide();
  $("#place_check").hide();
  $("#extra_container").hide();
  $("#location").hide();
  $("#rating_container").hide();

  $('#beer_search').on("keyup", function(){
    if($("#beer_id").length > 0)
    {
        $("#extra_container").show();
    }
  });

    $("#toggleRating").click(function(){
        $.ajax({
                headers: {'X-CSRF-Token': $('input[name="_token"]').val()},
                method: "POST",
                url: "/ajax/userRating",
                data: {   beer: $("#beer_id").val() }
            })
            .done(function( msg ) {
                $('#rating_container').html(msg)
             });
    });

    $("#toggleCheckin").click(function(){
        $("#location").show();
    });

    $("#toggleRating").click(function(){
      $("#rating_container").show();
    });


  function postBeer()
  {
    $.ajax({
        headers:
            {
            'X-CSRF-Token': $('input[name="_token"]').val()
            },
        method: "POST",
        url: "/ajax/drinking_this",
        data: {   beer: $("#beer_ref").val() }
    })
     .done(function( msg ) {
        alert( "Data Saved: " + msg );
     });
  }
</script>

<script src="/js/ajax_places.js"></script>


<style>
.select-wrapper {
    background: url(http://s10.postimg.org/4rc0fv8jt/camera.png) no-repeat;
    background-size: cover;
    /*display: block;*/
    /*position: relative;*/
    /*width: 33px;*/
    height: 16px;
}
#image_src {
    width: 26px;
    height: 16px;
    /*margin-right: 100px;*/
    opacity: 0;
    filter: alpha(opacity=0); /* IE 5-7 */
}
</style>
@stop

