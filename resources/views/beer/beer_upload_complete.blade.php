@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
	<div class="container">
		<div class="container">
		     Thanks!
		     {!! $message !!}
		     {!! $beer_meta !!}
		     <img src="{!! $beer_meta->path !!}" width="400px">
		</div>
	</div>
@stop