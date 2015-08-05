@extends('master')

@section('title')
    {{ $page_title }}
@stop

@section('description')
    {{$page_descs}}
@stop


@section('content')
    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
	<div class="container">
		<div class="container" style="max-width: 600px">

		    <img src="{!! $user->avatar !!}">
            {!! Form::open(array('id' => 'edit',
                                             'method' => 'POST',
                                             'url' => '/profile/'.$user->username.'/edit_callback',
                                             'files' => true)) !!}
            {!! Form::label('Profile Image') !!}
            {!! Form::file('image', null) !!}
            <div>

            <div class="container-fluid page-header">
                <div class="col-xs-12 padding_box">
                    @if($user->username_flg ==0)
                        <div class="alert alert-info" role="alert">You can only change your username once</div>
                        {!! Form::text('username', $user->username, array('class' => 'form-control disabled ')) !!}
                    @else
                    @endif
                </div>
            </div>

            <div class="container-fluid">
                <div class="col-xs-12 col-sm-6 padding_box">
                    {!! Form::text('firstname', $user->firstname, array('class' => 'form-control','placeholder'=>'firstname')) !!}
                </div>

                <div class="col-xs-12 col-sm-6 padding_box">
                    {!! Form::text('lastname', $user->lastname, array('class' => 'form-control','placeholder'=>'lastname')) !!}
                </div>
            </div>



	        {!! Form::label('email', 'E-Mail Address'); !!}
            {!! Form::text('email', $user->email, array('class' => 'form-control')) !!}

            <div class="container-fluid">
                <div class="col-xs-12 col-sm-6">
                    {!! Form::text('city', $user->city, array('class' => 'form-control','placeholder'=>'city')) !!}
                </div>
                <div class="col-xs-12 col-sm-6">
                    {!! Form::text('country', $user->country, array('class' => 'form-control','placeholder'=>'country')) !!}
                </div>
            </div>
            {!! Form::submit('Update!'); !!}
            {!! Form::close() !!}
		</div>
	</div>
@stop