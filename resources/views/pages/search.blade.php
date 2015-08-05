@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop

{{--
@foreach($beers as $index => $beer)
				<li>{{$beer}}</li>
@endforeach
--}}
<?php
$dummy_img = "http://cdn.instructables.com/F6J/OYGR/FABRWRG0/F6JOYGRFABRWRG0.LARGE.jpg";
?>


@section('content')
    <div class="container">
        <h1>Search results:</h1>
            @if(!empty($beers))
                <h2>Beers</h2>
                @foreach($beers as $b)
                    <p>
                        {!! \GlobalUrl::beer_url($b->slug,$b->beer) !!}
                    </p>
                @endforeach
            @else
                No beer found, damn
            @endif


            @if(!empty($brewery))
                <h2>Brewery</h2>
                @foreach($brewery as $brew)
                    <div class="padding_box">
                        {!! $brew->name !!}
                        &bull;
                        {!! $brew->city !!}
                    </div>
                @endforeach
            @else

            @endif
    </div>
@stop
