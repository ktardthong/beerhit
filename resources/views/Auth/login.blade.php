@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
	<div class="container-fluid">
	   <div class="container-fluid">
            <div class="row jumbotron" style="background-image: url('/img/beerhit_bg.jpg');
                                              background-size: cover;
                                              height: 320px;">
              <h1>Log in</h1>
              <p><a class="btn btn-primary btn-lg darkgold_bg" href="/login/fb" role="button">Login</a></p>
            </div>
       </div>
@stop