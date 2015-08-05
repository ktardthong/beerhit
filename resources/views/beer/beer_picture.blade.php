@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
		<div class="container-fluid padding_box table-bordered img-rounded" style="max-width: 640px; margin-top:20px">
		     <div class="clearfix">
		        <div class="pull-left">
                    <p>
                        <img src="{!! $data->avatar !!}"  width="50px" class="img-circle">
                        {!! \GlobalUrl::user_url($data->username); !!}

                        @if(isset($data->description))
                            {!! $data->description !!}
                        @endif
                    </p>

                </div>
                <div class="pull-right">
                    {!! UserBeerHelper::humanTime($data->beer_created); !!}
                    <br>
                    <p>
                        {!! \GlobalUrl::beer_url($data->slug,$data->beer) !!}
                    </p>
                    <p>
                        <a href="/beer/{!! $data->slug !!}/gallery/">Gallery</a>
                    </p>
                </div>
             </div>


             <div class="container-fluid padding_box" align="center">
		        <img src="{!! $data->path !!}" class="img-responsive" align="center">
		     </div>
		</div>
@stop