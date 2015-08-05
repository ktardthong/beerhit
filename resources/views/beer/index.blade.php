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
			@foreach($beers as $index => $beer)
				<li>{{$beer}}</li>
			@endforeach		
		</div>
	</div>
@stop